<?php

require_once __DIR__.'/app/controllers/default/controller.php';
require_once __DIR__.'/app/controllers/viewcontroller.php';
require_once __DIR__.'/core/route.php';
require_once __DIR__.'/app/controllers/default/routecontroller.php';

require_once __DIR__.'/app/exceptions/RouteAlreadyExistsException.php';

require_once __DIR__.'/routes/web.php';
require_once __DIR__.'/core/requests.php';
require_once __DIR__.'/core/loader.php';
require_once __DIR__.'/core/templates/engine.php';
require_once __DIR__.'/core/templates/variables.php';
require_once __DIR__.'/core/templates/functions.php';
