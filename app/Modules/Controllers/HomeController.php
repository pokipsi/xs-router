<?php

namespace Modules\Controllers;

use Modules\Core\Controller;

class HomeController extends Controller {

    public function index(){
        $data = [
            'param' => "XS Router"
        ];
        $this->view('home/index', $data);
    }

}