<?php 

namespace Modules\Core;

class Controller {
    
    public function view($view, $data = []){
        require_once APP_PATH . '/views/' . $view . '.php';
    }

}