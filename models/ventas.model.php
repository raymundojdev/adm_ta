<?php
require_once "conexion.php";

class ModelVentas
{

    static public function mdlMostrarVentas($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT) memoria

        $sqlBase = "SELECT v.*,
                       s.suc_nombre,
                       c.cli_nombre
                FROM $tabla v
                JOIN sucursales s ON s.suc_id = v.suc_id
                LEFT JOIN clientes  c ON c.cli_id = v.cli_id";

        if ($item != null) {
            // (OPT) whitelist de columna para seguridad
            $permitidos = ["ven_id"];
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE v.$item = :val LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = $sqlBase . " ORDER BY v.ven_fecha DESC, v.ven_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarVenta($tabla, $d)
    {
        $sql = "INSERT INTO $tabla
            (suc_id, cli_id, ven_fecha, ven_total, ven_tacos_vendidos, ven_puntos_otorgados, ven_activa)
            VALUES
            (:suc_id, :cli_id, :ven_fecha, :ven_total, :ven_tacos_vendidos, :ven_puntos_otorgados, :ven_activa)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        if ($d["cli_id"] === null) $stmt->bindValue(":cli_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_id", $d["cli_id"], PDO::PARAM_INT);

        $stmt->bindParam(":ven_fecha", $d["ven_fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":ven_total", $d["ven_total"]);
        $stmt->bindParam(":ven_tacos_vendidos", $d["ven_tacos_vendidos"], PDO::PARAM_INT);
        $stmt->bindParam(":ven_puntos_otorgados", $d["ven_puntos_otorgados"], PDO::PARAM_INT);
        $stmt->bindParam(":ven_activa", $d["ven_activa"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarVenta($tabla, $d)
    {
        $sql = "UPDATE $tabla SET
              suc_id=:suc_id,
              cli_id=:cli_id,
              ven_fecha=:ven_fecha,
              ven_total=:ven_total,
              ven_tacos_vendidos=:ven_tacos_vendidos,
              ven_puntos_otorgados=:ven_puntos_otorgados,
              ven_activa=:ven_activa
            WHERE ven_id=:ven_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":ven_id", $d["ven_id"], PDO::PARAM_INT);
        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
        if ($d["cli_id"] === null) $stmt->bindValue(":cli_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_id", $d["cli_id"], PDO::PARAM_INT);

        $stmt->bindParam(":ven_fecha", $d["ven_fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":ven_total", $d["ven_total"]);
        $stmt->bindParam(":ven_tacos_vendidos", $d["ven_tacos_vendidos"], PDO::PARAM_INT);
        $stmt->bindParam(":ven_puntos_otorgados", $d["ven_puntos_otorgados"], PDO::PARAM_INT);
        $stmt->bindParam(":ven_activa", $d["ven_activa"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarVenta($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE ven_id=:ven_id");
        $stmt->bindParam(":ven_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}
