<?php
require_once "../controllers/ventas.controller.php";
require_once "../models/ventas.model.php";

class AjaxVentas
{
    public $idVenta;

    public function ajaxEditarVenta()
    {
        $item  = "ven_id";
        $valor = (int)$this->idVenta;
        $venta = ControllerVentas::ctrMostrarVentas($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) Respuesta JSON explícita
        echo json_encode($venta ?: []);
        exit;
    }
}

if (isset($_POST["idVenta"])) {
    $a = new AjaxVentas();
    $a->idVenta = $_POST["idVenta"];
    $a->ajaxEditarVenta();
}
