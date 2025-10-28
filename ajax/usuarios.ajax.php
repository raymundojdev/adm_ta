<?php

require_once "../controllers/usuarios.controller.php";
require_once "../models/usuarios.model.php";

class  AjaxUsuarios
{
    public $idUsuario;

    public function ajaxEditarUsuario()
    {
        $item = "id";
        $valor = $this->idUsuario;
        $respuesta = ControllerUsuarios::ctrMostrarUsuarios($item, $valor);
        echo json_encode($respuesta);
    }

    //===============================================================
    //Validar si existe un CURP
    //===============================================================
    public $validarCURP;

    public function ajaxValidarCURP()
    {
        $item = "curp";
        $valor = $this->validarCURP;
        $respuesta = ControllerUsuarios::ctrMostrarUsuarios($item, $valor);
        echo json_encode($respuesta);
    }

}

if (isset($_POST["idUsuario"])) {

    $editar = new AjaxUsuarios();
    $editar->idUsuario = $_POST["idUsuario"];
    $editar->ajaxEditarUsuario();
}

//===============================================================
//Creamos objeto para validar CURP
//===============================================================
if (isset($_POST["validarCURP"])) {

    $validarCURP = new AjaxUsuarios();
    $validarCURP->validarCURP = $_POST["validarCURP"];
    $validarCURP->ajaxValidarCURP();
}
