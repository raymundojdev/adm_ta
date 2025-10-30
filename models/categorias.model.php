<?php
require_once "conexion.php";

class ModelCategorias
{

    static public function mdlMostrarCategorias($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($item != null) {
            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $item = :val LIMIT 1");
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM $tabla ORDER BY cat_id DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    static public function mdlGuardarCategorias($tabla, $datos)
    {
        $stmt = conexiondb::conectar()->prepare(
            "INSERT INTO $tabla (cat_nombre, cat_activa) VALUES (:cat_nombre, :cat_activa)"
        );
        $stmt->bindParam(":cat_nombre", $datos["cat_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":cat_activa", $datos["cat_activa"], PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarCategorias($tabla, $datos)
    {
        $stmt = conexiondb::conectar()->prepare(
            "UPDATE $tabla SET cat_nombre=:cat_nombre, cat_activa=:cat_activa WHERE cat_id=:cat_id"
        );
        $stmt->bindParam(":cat_id", $datos["cat_id"], PDO::PARAM_INT);
        $stmt->bindParam(":cat_nombre", $datos["cat_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":cat_activa", $datos["cat_activa"], PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarCategoria($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE cat_id=:cat_id");
        $stmt->bindParam(":cat_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}