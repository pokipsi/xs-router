<?php

define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/init.php';
require_once APP_PATH . '/routes.php';

use Modules\Core\App;

new App();