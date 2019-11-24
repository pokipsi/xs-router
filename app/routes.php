<?php

use Modules\Core\Router;

/*
First parameter is the route.
Routes can have placeholders.

Placeholders:

1) :controller (i.e. "test" -> "TestController", "test-case" -> TestCaseController)
2) :action (defaul index)
1) :params - many chunks, /param1/param2/param3/...
2) :param - one chunk /param1/

Second parameter is settings. 
1) settings as string always represents controller name
2) settings as array can hold 
    a) controller (required)
    b) action (default: index)

*/


Router::register('/', 'HomeController');

Router::register('/example/:param/:param/', [
    "controller" => "ExampleController",
    "action" => "show"
]); // i.e. /example/en/wolves/

Router::register('/:controller/:action/:params/'); // i.e. /product/show/123