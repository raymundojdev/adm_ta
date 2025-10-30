<?php
// models/productos.model.php
// JOIN a categorías para mostrar cat_nombre en la lista

require_once "conexion.php";

class ModelProductos
{

    static public function mdlMostrarProductos($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT) claves asociativas

        if ($item != null) {
            // (OPT) whitelist para evitar inyección en $item
            $permitidos = ["pro_id", "pro_sku"];
            if (!in_array($item, $permitidos, true)) {
                return [];
            }

            $sql = "SELECT p.*, c.cat_nombre
                    FROM $tabla p
                    JOIN categorias c ON c.cat_id = p.cat_id
                    WHERE p.$item = :val
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = "SELECT p.*, c.cat_nombre
                    FROM $tabla p
                    JOIN categorias c ON c.cat_id = p.cat_id
                    ORDER BY p.pro_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarProductos($tabla, $datos)
    {
        $sql = "INSERT INTO $tabla
                (pro_sku, pro_nombre, cat_id, pro_imagen, pro_activo)
                VALUES
                (:pro_sku, :pro_nombre, :cat_id, :pro_imagen, :pro_activo)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":pro_sku", $datos["pro_sku"], PDO::PARAM_STR);
        $stmt->bindParam(":pro_nombre", $datos["pro_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":cat_id", $datos["cat_id"], PDO::PARAM_INT);

        if ($datos["pro_imagen"] === null) $stmt->bindValue(":pro_imagen", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pro_imagen", $datos["pro_imagen"], PDO::PARAM_STR);

        $stmt->bindParam(":pro_activo", $datos["pro_activo"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarProductos($tabla, $datos)
    {
        $sql = "UPDATE $tabla SET
                  pro_sku=:pro_sku,
                  pro_nombre=:pro_nombre,
                  cat_id=:cat_id,
                  pro_imagen=:pro_imagen,
                  pro_activo=:pro_activo
                WHERE pro_id=:pro_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":pro_id", $datos["pro_id"], PDO::PARAM_INT);
        $stmt->bindParam(":pro_sku", $datos["pro_sku"], PDO::PARAM_STR);
        $stmt->bindParam(":pro_nombre", $datos["pro_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":cat_id", $datos["cat_id"], PDO::PARAM_INT);

        if ($datos["pro_imagen"] === null) $stmt->bindValue(":pro_imagen", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":pro_imagen", $datos["pro_imagen"], PDO::PARAM_STR);

        $stmt->bindParam(":pro_activo", $datos["pro_activo"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarProducto($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE pro_id=:pro_id");
        $stmt->bindParam(":pro_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}