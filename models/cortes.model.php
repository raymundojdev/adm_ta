<?php
// models/cortes.model.php
require_once "conexion.php";

class ModelCortes
{

    static public function mdlMostrarCortes($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT)

        $sqlBase = "SELECT c.*, s.suc_nombre
                FROM $tabla c
                JOIN sucursales s ON s.suc_id = c.suc_id";

        if ($item != null) {
            $permitidos = ["cor_id"]; // (OPT) whitelist
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE c.$item = :val LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = $sqlBase . " ORDER BY c.cor_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlAbrirCorte($tabla, $d)
    {
        $sql = "INSERT INTO $tabla
            (suc_id, usr_id, cor_turno, cor_inicio, cor_fondo_inicial, cor_observaciones, cor_estado)
            VALUES
            (:suc_id, :usr_id, :cor_turno, :cor_inicio, :cor_fondo_inicial, :cor_observaciones, 'ABIERTO')";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        if ($d["usr_id"] === null) $stmt->bindValue(":usr_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":usr_id", $d["usr_id"], PDO::PARAM_INT);

        $stmt->bindParam(":cor_turno", $d["cor_turno"], PDO::PARAM_STR);
        $stmt->bindParam(":cor_inicio", $d["cor_inicio"], PDO::PARAM_STR);
        $stmt->bindParam(":cor_fondo_inicial", $d["cor_fondo_inicial"]);
        if ($d["cor_observaciones"] === null) $stmt->bindValue(":cor_observaciones", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cor_observaciones", $d["cor_observaciones"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlCerrarCorte($tabla, $d)
    {
        $sql = "UPDATE $tabla SET
              suc_id=:suc_id,
              cor_turno=:cor_turno,
              cor_inicio=:cor_inicio,
              cor_fin=:cor_fin,
              cor_fondo_inicial=:cor_fondo_inicial,
              cor_total_efectivo=:cor_total_efectivo,
              cor_total_tarjeta=:cor_total_tarjeta,
              cor_total_transfer=:cor_total_transfer,
              cor_total_mixto=:cor_total_mixto,
              cor_total_sistema=:cor_total_sistema,
              cor_gastos=:cor_gastos,
              cor_ingresos_extra=:cor_ingresos_extra,
              cor_total_declarado=:cor_total_declarado,
              cor_diferencia=:cor_diferencia,
              cor_observaciones=:cor_observaciones,
              cor_estado=:cor_estado
            WHERE cor_id=:cor_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":cor_id", $d["cor_id"], PDO::PARAM_INT);
        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        $stmt->bindParam(":cor_turno", $d["cor_turno"], PDO::PARAM_STR);
        $stmt->bindParam(":cor_inicio", $d["cor_inicio"], PDO::PARAM_STR);
        $stmt->bindParam(":cor_fin", $d["cor_fin"], PDO::PARAM_STR);
        $stmt->bindParam(":cor_fondo_inicial", $d["cor_fondo_inicial"]);

        $stmt->bindParam(":cor_total_efectivo", $d["cor_total_efectivo"]);
        $stmt->bindParam(":cor_total_tarjeta", $d["cor_total_tarjeta"]);
        $stmt->bindParam(":cor_total_transfer", $d["cor_total_transfer"]);
        $stmt->bindParam(":cor_total_mixto", $d["cor_total_mixto"]);
        $stmt->bindParam(":cor_total_sistema", $d["cor_total_sistema"]);

        $stmt->bindParam(":cor_gastos", $d["cor_gastos"]);
        $stmt->bindParam(":cor_ingresos_extra", $d["cor_ingresos_extra"]);
        $stmt->bindParam(":cor_total_declarado", $d["cor_total_declarado"]);
        $stmt->bindParam(":cor_diferencia", $d["cor_diferencia"]);

        if ($d["cor_observaciones"] === null) $stmt->bindValue(":cor_observaciones", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cor_observaciones", $d["cor_observaciones"], PDO::PARAM_STR);

        $stmt->bindParam(":cor_estado", $d["cor_estado"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    /* Agregados por rango: suma pagos por método, filtrando sucursal y fecha */
    static public function mdlSumasPagosPorRango($tablaPagos, $tablaPedidos, $suc_id, $inicio, $fin)
    {
        $pdo = conexiondb::conectar();

        $sql = "SELECT
              SUM(CASE WHEN pa.pag_metodo='EFECTIVO'     AND pa.pag_estado='APLICADO' THEN pa.pag_monto ELSE 0 END) AS efectivo,
              SUM(CASE WHEN pa.pag_metodo='TARJETA'      AND pa.pag_estado='APLICADO' THEN pa.pag_monto ELSE 0 END) AS tarjeta,
              SUM(CASE WHEN pa.pag_metodo='TRANSFERENCIA'AND pa.pag_estado='APLICADO' THEN pa.pag_monto ELSE 0 END) AS transferencia,
              SUM(CASE WHEN pa.pag_metodo='MIXTO'        AND pa.pag_estado='APLICADO' THEN pa.pag_monto ELSE 0 END) AS mixto,
              SUM(CASE WHEN pa.pag_estado='APLICADO' THEN pa.pag_monto ELSE 0 END) AS total_sistema
            FROM $tablaPagos pa
            JOIN $tablaPedidos pe ON pe.ped_id = pa.ped_id
            WHERE pe.suc_id = :suc_id
              AND pa.pag_creado_en >= :ini
              AND pa.pag_creado_en <= :fin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":suc_id", $suc_id, PDO::PARAM_INT);
        $stmt->bindParam(":ini", $inicio, PDO::PARAM_STR);
        $stmt->bindParam(":fin", $fin, PDO::PARAM_STR);
        $stmt->execute();

        $r = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        // (OPT) aseguramos 0.00 si viene null
        foreach (["efectivo", "tarjeta", "transferencia", "mixto", "total_sistema"] as $k) {
            if (!isset($r[$k]) || $r[$k] === null) $r[$k] = 0.00;
        }
        return $r;
    }

    static public function mdlEliminarCorte($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE cor_id=:cor_id");
        $stmt->bindParam(":cor_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}