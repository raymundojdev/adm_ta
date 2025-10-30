<?php
require_once "../controllers/pedidos.controller.php";
require_once "../models/pedidos.model.php";

class AjaxPedidos
{
    public $idPedido;

    public function ajaxEditarPedido()
    {
        $valor = (int)$this->idPedido;
        $pedido = ControllerPedidos::ctrMostrarPedidos("ped_id", $valor);

        // Detalles
        $detalles = ModelPedidos::mdlMostrarDetalles("pedidos_detalles", $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) JSON limpio
        echo json_encode([
            "pedido"   => $pedido ?: [],
            "detalles" => $detalles ?: []
        ]);
        exit;
    }
}

if (isset($_POST["idPedido"])) {
    $editar = new AjaxPedidos();
    $editar->idPedido = $_POST["idPedido"];
    $editar->ajaxEditarPedido();
}