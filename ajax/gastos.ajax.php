<?php
// ajax/gastos.ajax.php
require_once "../controllers/gastos.controller.php";
require_once "../models/gastos.model.php";

class AjaxGastos
{
    public $idGasto;

    public function ajaxEditarGasto()
    {
        $item  = "gas_id";
        $valor = (int)$this->idGasto;
        $r = ControllerGastos::ctrMostrarGastos($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) JSON limpio
        echo json_encode($r ?: []);
        exit;
    }
}

if (isset($_POST["idGasto"])) {
    $a = new AjaxGastos();
    $a->idGasto = $_POST["idGasto"];
    $a->ajaxEditarGasto();
}