<?php
// models/gastos.model.php
require_once "conexion.php";

class ModelGastos
{

    static public function mdlMostrarGastos($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT) menos memoria

        $sqlBase = "SELECT g.*, s.suc_nombre, c.cor_turno, c.cor_inicio, c.cor_fin
                FROM $tabla g
                JOIN sucursales s ON s.suc_id = g.suc_id
                LEFT JOIN cortes_caja c ON c.cor_id = g.cor_id";

        if ($item != null) {
            $permitidos = ["gas_id"]; // (OPT) whitelist defensiva
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE g.$item = :val LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = $sqlBase . " ORDER BY g.gas_fecha DESC, g.gas_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarGastos($tabla, $d)
    {
        $sql = "INSERT INTO $tabla
            (suc_id, cor_id, gas_concepto, gas_monto, gas_metodo, gas_fecha, gas_comprobante, gas_nota, gas_estado)
            VALUES
            (:suc_id, :cor_id, :gas_concepto, :gas_monto, :gas_metodo, :gas_fecha, :gas_comprobante, :gas_nota, :gas_estado)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        if ($d["cor_id"] === null) $stmt->bindValue(":cor_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cor_id", $d["cor_id"], PDO::PARAM_INT);

        $stmt->bindParam(":gas_concepto", $d["gas_concepto"], PDO::PARAM_STR);
        $stmt->bindParam(":gas_monto", $d["gas_monto"]);
        $stmt->bindParam(":gas_metodo", $d["gas_metodo"], PDO::PARAM_STR);
        $stmt->bindParam(":gas_fecha", $d["gas_fecha"], PDO::PARAM_STR);

        if ($d["gas_comprobante"] === null) $stmt->bindValue(":gas_comprobante", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":gas_comprobante", $d["gas_comprobante"], PDO::PARAM_STR);

        if ($d["gas_nota"] === null) $stmt->bindValue(":gas_nota", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":gas_nota", $d["gas_nota"], PDO::PARAM_STR);

        $stmt->bindParam(":gas_estado", $d["gas_estado"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarGastos($tabla, $d)
    {
        $sql = "UPDATE $tabla SET
              suc_id=:suc_id,
              cor_id=:cor_id,
              gas_concepto=:gas_concepto,
              gas_monto=:gas_monto,
              gas_metodo=:gas_metodo,
              gas_fecha=:gas_fecha,
              gas_comprobante=:gas_comprobante,
              gas_nota=:gas_nota,
              gas_estado=:gas_estado
            WHERE gas_id=:gas_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":gas_id", $d["gas_id"], PDO::PARAM_INT);
        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        if ($d["cor_id"] === null) $stmt->bindValue(":cor_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cor_id", $d["cor_id"], PDO::PARAM_INT);

        $stmt->bindParam(":gas_concepto", $d["gas_concepto"], PDO::PARAM_STR);
        $stmt->bindParam(":gas_monto", $d["gas_monto"]);
        $stmt->bindParam(":gas_metodo", $d["gas_metodo"], PDO::PARAM_STR);
        $stmt->bindParam(":gas_fecha", $d["gas_fecha"], PDO::PARAM_STR);

        if ($d["gas_comprobante"] === null) $stmt->bindValue(":gas_comprobante", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":gas_comprobante", $d["gas_comprobante"], PDO::PARAM_STR);

        if ($d["gas_nota"] === null) $stmt->bindValue(":gas_nota", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":gas_nota", $d["gas_nota"], PDO::PARAM_STR);

        $stmt->bindParam(":gas_estado", $d["gas_estado"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarGasto($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE gas_id=:gas_id");
        $stmt->bindParam(":gas_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}