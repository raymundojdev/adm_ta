<?php
// ajax/cortes_caja.ajax.php
// Devuelve corte + totales del sistema para el rango (para llenar modal Cerrar)

require_once "../controllers/cortes.controller.php";
require_once "../models/cortes.model.php";

class AjaxCortesCaja
{
    public $idCorte;

    public function ajaxEditarCorte()
    {
        $item  = "cor_id";
        $valor = (int)$this->idCorte;

        $corte = ControllerCortes::ctrMostrarCortes($item, $valor);
        $tot   = [];

        if ($corte && !empty($corte["suc_id"]) && !empty($corte["cor_inicio"])) {
            $suc = (int)$corte["suc_id"];
            $ini = $corte["cor_inicio"];
            $fin = $corte["cor_fin"] ?: date('Y-m-d H:i:s'); // (OPT) si aún no hay fin, usar "ahora"
            $tot = ControllerCortes::ctrCalcularTotales($suc, $ini, $fin);
        }

        header('Content-Type: application/json; charset=utf-8'); // (OPT) JSON limpio
        echo json_encode([
            "corte"   => $corte ?: [],
            "totales" => $tot ?: []
        ]);
        exit;
    }
}

if (isset($_POST["idCorte"])) {
    $editar = new AjaxCortesCaja();
    $editar->idCorte = $_POST["idCorte"];
    $editar->ajaxEditarCorte();
}