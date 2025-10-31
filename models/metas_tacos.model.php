<?php
require_once "conexion.php";

class ModelMetasTacos
{

    static public function mdlMostrarMetas($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT) menor uso de memoria

        // (OPT) Incluye nombres legibles de sucursal/categoría/producto
        $sqlBase = "SELECT m.*, 
                       s.suc_nombre, 
                       c.cat_nombre, 
                       p.pro_nombre
                FROM $tabla m
                JOIN sucursales s ON s.suc_id = m.suc_id
                LEFT JOIN categorias c ON c.cat_id = m.cat_id
                LEFT JOIN productos  p ON p.pro_id = m.pro_id";

        if ($item != null) {
            // (OPT) whitelist defensiva para evitar inyección en nombre de columna
            $permitidos = ["met_id"];
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE m.$item = :val LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = $sqlBase . " ORDER BY m.met_fecha DESC, m.met_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarMeta($tabla, $d)
    {
        $sql = "INSERT INTO $tabla
            (suc_id, cat_id, pro_id, met_fecha, met_cantidad, met_nota, met_activa)
            VALUES
            (:suc_id, :cat_id, :pro_id, :met_fecha, :met_cantidad, :met_nota, :met_activa)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);

        if ($d["cat_id"] === null) $stmt->bindValue(":cat_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cat_id", $d["cat_id"], PDO::PARAM_INT);

        if ($d["pro_id"] === null) $stmt->bindValue(":pro_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pro_id", $d["pro_id"], PDO::PARAM_INT);

        $stmt->bindParam(":met_fecha", $d["met_fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":met_cantidad", $d["met_cantidad"], PDO::PARAM_INT);

        if ($d["met_nota"] === null) $stmt->bindValue(":met_nota", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":met_nota", $d["met_nota"], PDO::PARAM_STR);

        $stmt->bindParam(":met_activa", $d["met_activa"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarMeta($tabla, $d)
    {
        $sql = "UPDATE $tabla SET
              suc_id=:suc_id,
              cat_id=:cat_id,
              pro_id=:pro_id,
              met_fecha=:met_fecha,
              met_cantidad=:met_cantidad,
              met_nota=:met_nota,
              met_activa=:met_activa
            WHERE met_id=:met_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":met_id", $d["met_id"], PDO::PARAM_INT);
        $stmt->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);

        if ($d["cat_id"] === null) $stmt->bindValue(":cat_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cat_id", $d["cat_id"], PDO::PARAM_INT);

        if ($d["pro_id"] === null) $stmt->bindValue(":pro_id", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pro_id", $d["pro_id"], PDO::PARAM_INT);

        $stmt->bindParam(":met_fecha", $d["met_fecha"], PDO::PARAM_STR);
        $stmt->bindParam(":met_cantidad", $d["met_cantidad"], PDO::PARAM_INT);

        if ($d["met_nota"] === null) $stmt->bindValue(":met_nota", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":met_nota", $d["met_nota"], PDO::PARAM_STR);

        $stmt->bindParam(":met_activa", $d["met_activa"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarMeta($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE met_id=:met_id");
        $stmt->bindParam(":met_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    /* (OPCIONAL) Progreso real del día: suma pde_cantidad con filtros cat/pro */
    static public function mdlProgresoDia($tablaPed, $tablaDet, $tablaPro, $suc_id, $fecha, $cat_id = null, $pro_id = null)
    {
        $pdo = conexiondb::conectar();

        $ini = $fecha . " 00:00:00";
        $fin = $fecha . " 23:59:59";

        $andCat = "";
        $andPro = "";
        if ($pro_id !== null) {
            $andPro = " AND d.pro_id = :pro_id";
        } elseif ($cat_id !== null) {
            $andCat = " AND pr.cat_id = :cat_id";
        }

        $sql = "SELECT COALESCE(SUM(d.pde_cantidad), 0) AS vendidos
            FROM $tablaDet d
            JOIN $tablaPed pe ON pe.ped_id = d.ped_id
            JOIN $tablaPro pr ON pr.pro_id = d.pro_id
            WHERE pe.suc_id = :suc_id
              AND pe.ped_creado_en >= :ini
              AND pe.ped_creado_en <= :fin
              $andPro
              $andCat";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":suc_id", $suc_id, PDO::PARAM_INT);
        $stmt->bindParam(":ini", $ini, PDO::PARAM_STR);
        $stmt->bindParam(":fin", $fin, PDO::PARAM_STR);
        if ($pro_id !== null) $stmt->bindParam(":pro_id", $pro_id, PDO::PARAM_INT);
        if ($cat_id !== null) $stmt->bindParam(":cat_id", $cat_id, PDO::PARAM_INT);

        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC) ?: ["vendidos" => 0];
        return (int)$r["vendidos"];
    }
}