<?php
// models/dashboard.model.php
require_once "conexion.php";

class ModelDashboard {

  /* ===========================================================
     Esquema real utilizado (según tu diagrama):
     - pedidos(ped_id, suc_id, ped_tipo, ped_estado, ped_total, ped_creado_en, ...)
     - pedidos_detalles(pde_id, ped_id, pro_id, pde_cantidad, pde_precio, pde_subtotal)
     - productos(pro_id, pro_sku, pro_nombre, cat_id, pro_imagen, pro_activo, ...)
     - sucursales(suc_id, suc_nombre, suc_direccion, suc_activa, ...)
     - gastos(gas_id, suc_id, gas_concepto, gas_monto, gas_fecha, gas_estado, ...)
     - metas_tacos(met_id, suc_id, cat_id, pro_id, met_fecha, met_cantidad, met_activa, ...)
     - promociones(prm_id, prm_titulo, prm_tipo, prm_descuento, prm_activa, prm_inicio, prm_fin, ...)
     =========================================================== */

  /* KPIs de HOY */
  static public function mdlKpisHoy() {
    $db = conexiondb::conectar();

    // (OPT) Subconsultas en un solo roundtrip
    $sql = "
      SELECT
        /* ventas $ hoy: pedidos pagados */
        (SELECT COALESCE(SUM(ped_total),0)
         FROM pedidos
         WHERE ped_estado='PAGADO' AND DATE(ped_creado_en)=CURDATE()) AS ventas_hoy,

        /* tacos (unidades) hoy: suma de pde_cantidad en pedidos pagados */
        (SELECT COALESCE(SUM(d.pde_cantidad),0)
         FROM pedidos_detalles d
         JOIN pedidos p ON p.ped_id=d.ped_id
         WHERE p.ped_estado='PAGADO' AND DATE(p.ped_creado_en)=CURDATE()) AS tacos_hoy,

        /* gastos aplicados hoy */
        (SELECT COALESCE(SUM(gas_monto),0)
         FROM gastos
         WHERE gas_estado='APLICADO' AND DATE(gas_fecha)=CURDATE()) AS gastos_hoy,

        /* metas activas hoy (suma) */
        (SELECT COALESCE(SUM(met_cantidad),0)
         FROM metas_tacos
         WHERE met_activa=1 AND met_fecha=CURDATE()) AS meta_hoy
    ";
    $st = $db->prepare($sql);
    $st->execute();
    $r = $st->fetch(PDO::FETCH_ASSOC);

    $cumpl = 0;
    if ((int)$r["meta_hoy"] > 0) {
      $cumpl = min(100, (int)round(((int)$r["tacos_hoy"]) * 100 / (int)$r["meta_hoy"]));
    }

    return [
      "ventas_hoy" => (float)$r["ventas_hoy"],
      "tacos_hoy"  => (int)$r["tacos_hoy"],
      "gastos_hoy" => (float)$r["gastos_hoy"],
      "meta_hoy"   => (int)$r["meta_hoy"],
      "cumpl_meta" => (int)$cumpl
    ];
  }

  /* Serie por hora (00-23) de ventas de HOY sobre pedidos pagados */
  static public function mdlVentasPorHoraHoy() {
    $db = conexiondb::conectar();
    $sql = "
      SELECT LPAD(HOUR(ped_creado_en),2,'0') AS h, SUM(ped_total) AS total
      FROM pedidos
      WHERE ped_estado='PAGADO' AND DATE(ped_creado_en)=CURDATE()
      GROUP BY HOUR(ped_creado_en)
      ORDER BY HOUR(ped_creado_en)
    ";
    $st = $db->prepare($sql);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    // (OPT) rellena 24 horas aunque no haya registros
    $serie = array_fill(0, 24, 0.0);
    foreach ($rows as $r) {
      $idx = (int)$r["h"];
      $serie[$idx] = (float)$r["total"];
    }
    return $serie;
  }

  /* Top productos del día por cantidad (pedidos pagados) */
  static public function mdlTopProductosHoy($limit = 5) {
    $db = conexiondb::conectar();
    $sql = "
      SELECT pr.pro_nombre, SUM(d.pde_cantidad) AS qty
      FROM pedidos_detalles d
      JOIN pedidos p  ON p.ped_id=d.ped_id
      JOIN productos pr ON pr.pro_id=d.pro_id
      WHERE p.ped_estado='PAGADO' AND DATE(p.ped_creado_en)=CURDATE()
      GROUP BY pr.pro_id
      ORDER BY qty DESC
      LIMIT :lim
    ";
    $st = $db->prepare($sql);
    $st->bindValue(":lim", (int)$limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  /* Ventas por sucursal (hoy), solo pedidos pagados */
  static public function mdlVentasPorSucursalHoy() {
    $db = conexiondb::conectar();
    $sql = "
      SELECT s.suc_nombre, COALESCE(SUM(p.ped_total),0) AS total
      FROM sucursales s
      LEFT JOIN pedidos p
        ON p.suc_id=s.suc_id
       AND p.ped_estado='PAGADO'
       AND DATE(p.ped_creado_en)=CURDATE()
      WHERE s.suc_activa=1
      GROUP BY s.suc_id
      ORDER BY total DESC
    ";
    $st = $db->prepare($sql);
    $st->execute();
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  /* Progreso de metas por sucursal (hoy) */
  static public function mdlProgresoMetasHoy() {
    $db = conexiondb::conectar();
    $sql = "
      SELECT s.suc_id, s.suc_nombre,
             COALESCE(m.meta,0) AS meta,
             COALESCE(v.vendidos,0) AS vendidos
      FROM sucursales s
      LEFT JOIN (
        SELECT suc_id, SUM(met_cantidad) AS meta
        FROM metas_tacos
        WHERE met_activa=1 AND met_fecha=CURDATE()
        GROUP BY suc_id
      ) m ON m.suc_id=s.suc_id
      LEFT JOIN (
        SELECT p.suc_id, SUM(d.pde_cantidad) AS vendidos
        FROM pedidos_detalles d
        JOIN pedidos p ON p.ped_id=d.ped_id
        WHERE p.ped_estado='PAGADO' AND DATE(p.ped_creado_en)=CURDATE()
        GROUP BY p.suc_id
      ) v ON v.suc_id=s.suc_id
      WHERE s.suc_activa=1
      ORDER BY s.suc_nombre
    ";
    $st = $db->prepare($sql);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    // (OPT) calcular % en PHP para evitar CASE repetidos
    foreach ($rows as &$r) {
      $r["pct"] = ($r["meta"] > 0) ? min(100, (int)round($r["vendidos"]*100/$r["meta"])) : 0;
    }
    return $rows;
  }

  /* Pedidos recientes (últimos N) — muestra solo pagados para coherencia */
  static public function mdlPedidosRecientes($limit = 10) {
    $db = conexiondb::conectar();
    $sql = "
      SELECT p.ped_id, p.ped_total, p.ped_creado_en, s.suc_nombre, p.ped_tipo
      FROM pedidos p
      JOIN sucursales s ON s.suc_id=p.suc_id
      WHERE p.ped_estado='PAGADO'
      ORDER BY p.ped_creado_en DESC, p.ped_id DESC
      LIMIT :lim
    ";
    $st = $db->prepare($sql);
    $st->bindValue(":lim", (int)$limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll(PDO::FETCH_ASSOC);
  }

  /* Promociones activas hoy (rango de fechas) */
  static public function mdlPromocionesActivas($limit = 5) {
    $db = conexiondb::conectar();
    $sql = "
      SELECT prm_id, prm_titulo, prm_tipo, prm_descuento, prm_inicio, prm_fin
      FROM promociones
      WHERE prm_activa=1
        AND (prm_inicio IS NULL OR prm_inicio<=CURDATE())
        AND (prm_fin    IS NULL OR prm_fin>=CURDATE())
      ORDER BY prm_fin IS NULL DESC, prm_fin ASC
      LIMIT :lim
    ";
    try {
      $st = $db->prepare($sql);
      $st->bindValue(":lim", (int)$limit, PDO::PARAM_INT);
      $st->execute();
      return $st->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
      // (OPT) si aún no tienes promociones, no se rompe el dashboard
      return [];
    }
  }
}
