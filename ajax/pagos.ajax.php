<?php
require_once "../controllers/pagos.controller.php";
require_once "../models/pagos.model.php";

class AjaxPagos
{
    public $idPago;

    public function ajaxEditarPago()
    {
        $item  = "pag_id";
        $valor = (int)$this->idPago;
        $respuesta = ControllerPagos::ctrMostrarPagos($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) JSON limpio
        echo json_encode($respuesta ?: []);
        exit;
    }
}

if (isset($_POST["idPago"])) {
    $editar = new AjaxPagos();
    $editar->idPago = $_POST["idPago"];
    $editar->ajaxEditarPago();
}