<?php
require_once "../controllers/categorias.controller.php";
require_once "../models/categorias.model.php";

class AjaxCategorias
{
    public $idCategoria;

    public function ajaxEditarCategoria()
    {
        $item  = "cat_id";
        $valor = $this->idCategoria;

        $respuesta = ControllerCategorias::ctrMostrarCategorias($item, $valor);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($respuesta ?: []);
        exit; // evitar impresiones extra
    }
}

if (isset($_POST["idCategoria"])) {
    $editar = new AjaxCategorias();
    $editar->idCategoria = $_POST["idCategoria"];
    $editar->ajaxEditarCategoria();
}