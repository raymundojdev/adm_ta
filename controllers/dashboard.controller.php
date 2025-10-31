<?php
// controllers/dashboard.controller.php

class ControllerDashboard {

  /* KPIs compactos para la cabecera (hoy) */
  static public function ctrKpisHoy() {
    return ModelDashboard::mdlKpisHoy();
  }

  /* Endpoints de datos para gráficas (hoy) */
  static public function ctrVentasPorHoraHoy() {
    return ModelDashboard::mdlVentasPorHoraHoy();
  }

  static public function ctrTopProductosHoy($limit = 5) {
    return ModelDashboard::mdlTopProductosHoy((int)$limit);
  }

  static public function ctrVentasPorSucursalHoy() {
    return ModelDashboard::mdlVentasPorSucursalHoy();
  }

  static public function ctrProgresoMetasHoy() {
    return ModelDashboard::mdlProgresoMetasHoy();
  }

  /* Listas auxiliares */
  static public function ctrPedidosRecientes($limit = 10) {
    return ModelDashboard::mdlPedidosRecientes((int)$limit);
  }

  static public function ctrPromocionesActivas($limit = 5) {
    return ModelDashboard::mdlPromocionesActivas((int)$limit);
  }
}
