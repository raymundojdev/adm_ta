<?php
// ajax/proveedores.ajax.php
require_once "../models/conexion.php";
require_once "../models/proveedores.model.php";
require_once "../controllers/proveedores.controller.php";

// Respuesta JSON simple (para AJAX). Los formularios se envían con FormData (no JSON).
header("Content-Type: application/json; charset=UTF-8");

$accion = $_REQUEST["accion"] ?? "listar";

try {
    switch ($accion) {
        case "listar":
            $data = ProveedoresController::ctrListar();
            echo json_encode(["ok"=>true, "data"=>$data], JSON_UNESCAPED_UNICODE);
            break;

        case "obtener":
            $res = ProveedoresController::ctrObtener();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case "crear":
            $res = ProveedoresController::ctrCrear();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case "actualizar":
            $res = ProveedoresController::ctrActualizar();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case "desactivar":
            $res = ProveedoresController::ctrDesactivar();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case "activar":
            $res = ProveedoresController::ctrActivar();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case "eliminar":
            $res = ProveedoresController::ctrEliminar();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        default:
            echo json_encode(["ok"=>false, "msg"=>"Acción no reconocida"], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["ok"=>false, "msg"=>"Error: ".$e->getMessage()], JSON_UNESCAPED_UNICODE);
}
