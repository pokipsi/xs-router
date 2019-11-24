<?php

namespace Modules\Controllers;

use Modules\Core\Controller;

class ProductController extends Controller {

    public function index($param = "param"){

        echo "product:index:$param";

    }

    public function show($param = "param"){

        echo "product:show:$param";

    }

}