<?php
require_once "../controllers/sucursales.controller.php";
require_once "../models/sucursales.model.php";

class AjaxSucursales
{

    public $idSucursal;

    public function ajaxEditarSucursal()
    {
        $item  = "suc_id";
        $valor = $this->idSucursal;
        $respuesta = ControllerSucursales::ctrMostrarSucursales($item, $valor);
        echo json_encode($respuesta);
    }
}

if (isset($_POST["idSucursal"])) {
    $editar = new AjaxSucursales();
    $editar->idSucursal = $_POST["idSucursal"];
    $editar->ajaxEditarSucursal();
}