<?php
require_once "../controllers/promociones.controller.php";
require_once "../models/promociones.model.php";

class AjaxPromociones
{
    public $idPromocion;

    public function ajaxEditarPromocion()
    {
        $item  = "prm_id";
        $valor = $this->idPromocion;

        $respuesta = ControllerPromociones::ctrMostrarPromociones($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // Asegura JSON limpio
        echo json_encode($respuesta ?: []);
        exit; // Evita que se impriman warnings/notices
    }
}

if (isset($_POST["idPromocion"])) {
    $editar = new AjaxPromociones();
    $editar->idPromocion = $_POST["idPromocion"];
    $editar->ajaxEditarPromocion();
}