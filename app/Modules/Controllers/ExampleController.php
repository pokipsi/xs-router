<?php

namespace Modules\Controllers;

use Modules\Core\Controller;

class ExampleController extends Controller {

    public function show($lang, $param){
        echo "lang: $lang, name: $param";
    }

}