<?php
// ajax/puntos.ajax.php
require_once "../controllers/puntos.controller.php";
require_once "../models/puntos.model.php";

class AjaxPuntos {
  public $idMovimiento;

  public function ajaxEditarMovimiento() {
    $item  = "pmv_id";
    $valor = (int)$this->idMovimiento;
    $mov   = ControllerPuntos::ctrMostrarMovimientos($item, $valor);
    header('Content-Type: application/json; charset=utf-8'); // (OPT) JSON limpio
    echo json_encode($mov ?: []);
    exit;
  }

  public $idCliente;
  public function ajaxSaldoCliente() {
    $cli = (int)$this->idCliente;
    $r   = ControllerPuntos::ctrSaldoCliente($cli);
    header('Content-Type: application/json; charset=utf-8'); // (OPT)
    echo json_encode($r);
    exit;
  }
}

if (isset($_POST["idMovimiento"])) {
  $a = new AjaxPuntos();
  $a->idMovimiento = $_POST["idMovimiento"];
  $a->ajaxEditarMovimiento();
}

if (isset($_POST["idCliente"])) {
  $a = new AjaxPuntos();
  $a->idCliente = $_POST["idCliente"];
  $a->ajaxSaldoCliente();
}
