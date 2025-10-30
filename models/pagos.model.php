<?php
require_once "conexion.php";

class ModelPagos
{

    static public function mdlMostrarPagos($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT)

        $sqlBase = "SELECT pa.*, pe.ped_folio, pe.ped_total
                    FROM $tabla pa
                    JOIN pedidos pe ON pe.ped_id = pa.ped_id";

        if ($item != null) {
            $permitidos = ["pag_id", "ped_id"]; // (OPT) whitelist
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE pa.$item = :val";
            if ($item === "pag_id") $sql .= " LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return ($item === "pag_id") ? ($stmt->fetch() ?: []) : ($stmt->fetchAll() ?: []);
        } else {
            $sql = $sqlBase . " ORDER BY pa.pag_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarPagos($tabla, $datos)
    {
        $sql = "INSERT INTO $tabla
                (ped_id, pag_monto, pag_metodo, pag_recibido, pag_cambio, pag_referencia, pag_estado)
                VALUES
                (:ped_id, :pag_monto, :pag_metodo, :pag_recibido, :pag_cambio, :pag_referencia, :pag_estado)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":ped_id", $datos["ped_id"], PDO::PARAM_INT);
        $stmt->bindParam(":pag_monto", $datos["pag_monto"]);
        $stmt->bindParam(":pag_metodo", $datos["pag_metodo"], PDO::PARAM_STR);

        // Null-safe
        if ($datos["pag_recibido"] === null) $stmt->bindValue(":pag_recibido", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_recibido", $datos["pag_recibido"]);

        if ($datos["pag_cambio"] === null) $stmt->bindValue(":pag_cambio", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_cambio", $datos["pag_cambio"]);

        if ($datos["pag_referencia"] === null) $stmt->bindValue(":pag_referencia", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_referencia", $datos["pag_referencia"], PDO::PARAM_STR);

        $stmt->bindParam(":pag_estado", $datos["pag_estado"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarPagos($tabla, $datos)
    {
        $sql = "UPDATE $tabla SET
                  ped_id=:ped_id,
                  pag_monto=:pag_monto,
                  pag_metodo=:pag_metodo,
                  pag_recibido=:pag_recibido,
                  pag_cambio=:pag_cambio,
                  pag_referencia=:pag_referencia,
                  pag_estado=:pag_estado
                WHERE pag_id=:pag_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":pag_id", $datos["pag_id"], PDO::PARAM_INT);
        $stmt->bindParam(":ped_id", $datos["ped_id"], PDO::PARAM_INT);
        $stmt->bindParam(":pag_monto", $datos["pag_monto"]);
        $stmt->bindParam(":pag_metodo", $datos["pag_metodo"], PDO::PARAM_STR);

        if ($datos["pag_recibido"] === null) $stmt->bindValue(":pag_recibido", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_recibido", $datos["pag_recibido"]);

        if ($datos["pag_cambio"] === null) $stmt->bindValue(":pag_cambio", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_cambio", $datos["pag_cambio"]);

        if ($datos["pag_referencia"] === null) $stmt->bindValue(":pag_referencia", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pag_referencia", $datos["pag_referencia"], PDO::PARAM_STR);

        $stmt->bindParam(":pag_estado", $datos["pag_estado"], PDO::PARAM_STR);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarPago($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE pag_id=:pag_id");
        $stmt->bindParam(":pag_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}