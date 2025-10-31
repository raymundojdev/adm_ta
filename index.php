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

require_once "controllers/sucursales.controller.php";
require_once "models/sucursales.model.php";

require_once "controllers/categorias.controller.php";
require_once "models/categorias.model.php";

require_once "controllers/promociones.controller.php";
require_once "models/promociones.model.php";

require_once "controllers/clientes.controller.php";
require_once "models/clientes.model.php";

require_once "controllers/pedidos.controller.php";
require_once "models/pedidos.model.php";

require_once "controllers/pagos.controller.php";
require_once "models/pagos.model.php";

require_once "controllers/cortes.controller.php";
require_once "models/cortes.model.php";

require_once "controllers/gastos.controller.php";
require_once "models/gastos.model.php";

require_once "controllers/metas_tacos.controller.php";
require_once "models/metas_tacos.model.php";

require_once "controllers/ventas.controller.php";
require_once "models/ventas.model.php";

require_once "controllers/dashboard.controller.php";

// require_once "controllers/compras.controller.php";
// require_once "models/compras.model.php";



$plantilla = new ControllerPlantilla();
$plantilla->ctrPlantilla();
