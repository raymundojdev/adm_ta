<?php


require_once "../controllers/productos.controller.php";
require_once "../models/productos.model.php";

class AjaxProductos
{
    public $idProducto;

    public function ajaxEditarProducto()
    {
        $item  = "pro_id";
        $valor = $this->idProducto;

        $respuesta = ControllerProductos::ctrMostrarProductos($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) asegura JSON limpio
        echo json_encode($respuesta ?: []);
        exit;
    }
}

if (isset($_POST["idProducto"])) {
    $editar = new AjaxProductos();
    $editar->idProducto = $_POST["idProducto"];
    $editar->ajaxEditarProducto();
}