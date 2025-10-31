<?php
require_once "../controllers/metas_tacos.controller.php";
require_once "../models/metas_tacos.model.php";

class AjaxMetasTacos
{
    public $idMeta;

    public function ajaxEditarMeta()
    {
        $item  = "met_id";
        $valor = (int)$this->idMeta;
        $meta  = ControllerMetasTacos::ctrMostrarMetas($item, $valor);

        header('Content-Type: application/json; charset=utf-8'); // (OPT) respuesta JSON explícita
        echo json_encode($meta ?: []);
        exit;
    }
}

if (isset($_POST["idMeta"])) {
    $a = new AjaxMetasTacos();
    $a->idMeta = $_POST["idMeta"];
    $a->ajaxEditarMeta();
}