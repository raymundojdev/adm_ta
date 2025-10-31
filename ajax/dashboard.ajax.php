<?php
// ajax/dashboard.ajax.php
require_once "../controllers/dashboard.controller.php";
require_once "../models/dashboard.model.php";

header('Content-Type: application/json; charset=utf-8');

$action = isset($_GET["action"]) ? $_GET["action"] : "";

switch ($action) {
  case "kpis":
    echo json_encode(ControllerDashboard::ctrKpisHoy());
    break;
  case "ventas_horas":
    echo json_encode(ControllerDashboard::ctrVentasPorHoraHoy());
    break;
  case "top_productos":
    $limit = isset($_GET["limit"]) ? (int)$_GET["limit"] : 5;
    echo json_encode(ControllerDashboard::ctrTopProductosHoy($limit));
    break;
  case "ventas_sucursal":
    echo json_encode(ControllerDashboard::ctrVentasPorSucursalHoy());
    break;
  case "progreso_metas":
    echo json_encode(ControllerDashboard::ctrProgresoMetasHoy());
    break;
  default:
    echo json_encode(["error"=>"acción no válida"]);
    break;
}
