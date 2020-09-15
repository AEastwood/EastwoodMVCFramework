<?php

ini_set('display_errors', 1);

require 'autoload.php';

use Core\Loader as App;

$app = new App;
$app->render();
