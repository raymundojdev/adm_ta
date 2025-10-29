<?php

ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "R:/xampp/htdocs/zona52/php_error_log");


require_once "controllers/plantilla.controller.php";


// require_once "controllers/inicio.controller.php";
// require_once "models/inicio.model.php";

require_once "controllers/usuarios.controller.php";
require_once "models/usuarios.model.php";

require_once "controllers/proveedores.controller.php";
require_once "models/proveedores.model.php";

require_once "controllers/productos.controller.php";
require_once "models/productos.model.php";

// require_once "controllers/compras.controller.php";
// require_once "models/compras.model.php";



$plantilla = new ControllerPlantilla();
$plantilla->ctrPlantilla();
