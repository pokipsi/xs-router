<?php

namespace Modules\Core;

class Router {

    static $routes = [];

    public static function register($route, $settings = null){
        $regex = self::getRegex($route);
        self::$routes[$route] = [
            "regex" => $regex,
            "settings" => $settings
        ];
    }

    private static function getRegex($route){
        /*
            /en/production/industrial-level-production/
            /:param|2/production/:param/
            ^\/([^\/]{2})\/production\/([^\/]+)\/$

            :params  -> (.+)
            :param   -> ([^\/]+)
            :param|m -> ([^\/]{m})
        */

        $arr = explode("/", trim($route, "/"));

        foreach ($arr as &$chunk) {
            if(substr($chunk, 0, 1 ) === ":"){

                if($chunk === ':params'){
                    $chunk = "(.+)";
                }else if($chunk === ':controller' || $chunk === ':action' || $chunk === ':param'){
                    $chunk = "([^\/]+)";
                }else{
                    $m = explode("|", $chunk)[1];
                    $chunk = "([^\/]{".$m."})";
                }

            }
        }

        $regex = "^\/" . join("\/", $arr) . "\/$";

        return $regex;

    }

    private static function getMatchesForRoute($route){
        $route = trim($route, '/');
        $route = "/" . $route . "/";
        foreach (self::$routes as $k => $v) {
            $regex = "/" . $v['regex'] . "/";

            preg_match($regex, $route, $matches, PREG_OFFSET_CAPTURE);
            if(count($matches) > 0) {
                return [
                    "matches" => $matches,
                    "forRoute" => $k
                ];
            }
        }
    }

    public static function dispatch($route){

        $matchesForRoute = self::getMatchesForRoute($route);

        if($matchesForRoute){

            $matches = $matchesForRoute["matches"];
            $forRoute = $matchesForRoute["forRoute"];
            
            $arr = explode("/", trim($forRoute, "/"));

            array_shift($matches);

            $i = 0;

            $params = [];

            $controller = "";
            $action = "index";

            foreach ($arr as $chunk) {
                if(self::isPlaceholder($chunk)){
                    if($chunk === ":controller"){
                        $cnarr = explode("-", $matches[$i][0]);
                        if(is_array($cnarr)){
                            foreach ($cnarr as $cn) {
                                $controller .= ucfirst($cn);
                            }
                        }
                        else{
                            $controller = ucfirst($matches[$i][0]);
                        }
                        $controller .= "Controller";
                        $i++;
                    }
                    else if($chunk === ":action"){
                        $action = $matches[$i][0];
                        $i++;
                    }
                    else{
                        if($chunk === ":params"){
                            $prms = explode("/", $matches[$i][0]);
                            foreach ($prms as $p) {
                                array_push($params, $p);
                            }
                        }else{
                            array_push($params, $matches[$i][0]);
                        }
                        $i++;
                    }
                    
                }
            }

            $settings = self::$routes[$forRoute]["settings"];

            if(isset($settings)){
                if(is_array($settings)){
                    if(array_key_exists("controller", $settings)){
                        $controller = $settings["controller"];
                    }
                    if(array_key_exists("action", $settings)){
                        $action = $settings["action"];
                    }
                }else{
                    $controller = $settings;
                }
            }

            $className = "Modules\\Controllers\\{$controller}";

            if(class_exists($className)){

                $c = new $className;

                call_user_func_array([$c, $action], $params);
            
            }else{
                die("Invalid route: " . $route);
            }
        }        

    }

    private static function isPlaceholder($str){
        return substr($str, 0, 1 ) === ":";
    }

}