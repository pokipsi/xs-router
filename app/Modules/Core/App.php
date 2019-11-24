<?php 

namespace Modules\Core;

use Modules\Core\Router;

class App {

    public function __construct(){
        $uri = $_SERVER['REQUEST_URI'];
        $route = filter_var($uri, FILTER_SANITIZE_URL);
        Router::dispatch($route);
    }

}