<?php
require_once "conexion.php";

class ModelPromociones
{

    /* Mostrar */
    static public function mdlMostrarPromociones($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Asegura claves asociativas

        if ($item != null) {
            // Whitelist simple por seguridad
            $permitidos = ["prm_id", "prm_codigo", "prm_nombre"];
            if (!in_array($item, $permitidos, true)) {
                return [];
            }

            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $item = :val LIMIT 1");
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $stmt = $pdo->prepare("SELECT * FROM $tabla ORDER BY prm_id DESC");
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    /* Guardar */
    static public function mdlGuardarPromociones($tabla, $datos)
    {
        $sql = "INSERT INTO $tabla
                (prm_nombre, prm_tipo, prm_valor, prm_activa, prm_inicio, prm_fin, prm_codigo, prm_descripcion)
                VALUES
                (:prm_nombre, :prm_tipo, :prm_valor, :prm_activa, :prm_inicio, :prm_fin, :prm_codigo, :prm_descripcion)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":prm_nombre", $datos["prm_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":prm_tipo", $datos["prm_tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":prm_valor", $datos["prm_valor"]);
        $stmt->bindParam(":prm_activa", $datos["prm_activa"], PDO::PARAM_INT);

        foreach (["prm_inicio", "prm_fin", "prm_codigo", "prm_descripcion"] as $k) {
            if ($datos[$k] === null || $datos[$k] === "") {
                $stmt->bindValue(":" . $k, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":" . $k, $datos[$k], PDO::PARAM_STR);
            }
        }

        return $stmt->execute() ? "ok" : "error";
    }

    /* Editar */
    static public function mdlEditarPromociones($tabla, $datos)
    {
        $sql = "UPDATE $tabla SET
                  prm_nombre=:prm_nombre,
                  prm_tipo=:prm_tipo,
                  prm_valor=:prm_valor,
                  prm_activa=:prm_activa,
                  prm_inicio=:prm_inicio,
                  prm_fin=:prm_fin,
                  prm_codigo=:prm_codigo,
                  prm_descripcion=:prm_descripcion
                WHERE prm_id=:prm_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":prm_id", $datos["prm_id"], PDO::PARAM_INT);
        $stmt->bindParam(":prm_nombre", $datos["prm_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":prm_tipo", $datos["prm_tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":prm_valor", $datos["prm_valor"]);
        $stmt->bindParam(":prm_activa", $datos["prm_activa"], PDO::PARAM_INT);

        foreach (["prm_inicio", "prm_fin", "prm_codigo", "prm_descripcion"] as $k) {
            if ($datos[$k] === null || $datos[$k] === "") {
                $stmt->bindValue(":" . $k, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":" . $k, $datos[$k], PDO::PARAM_STR);
            }
        }

        return $stmt->execute() ? "ok" : "error";
    }

    /* Eliminar */
    static public function mdlEliminarPromocion($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE prm_id=:prm_id");
        $stmt->bindParam(":prm_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}