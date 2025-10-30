<?php
// ajax/clientes.ajax.php
require_once "../controllers/clientes.controller.php";
require_once "../models/clientes.model.php";

class AjaxClientes
{
    public $idCliente;

    public function ajaxEditarCliente()
    {
        $item  = "cli_id";
        $valor = $this->idCliente;

        $respuesta = ControllerClientes::ctrMostrarClientes($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // Asegura JSON limpio
        echo json_encode($respuesta ?: []);
        exit; // Evita impresiones extra
    }
}

if (isset($_POST["idCliente"])) {
    $editar = new AjaxClientes();
    $editar->idCliente = $_POST["idCliente"];
    $editar->ajaxEditarCliente();
}