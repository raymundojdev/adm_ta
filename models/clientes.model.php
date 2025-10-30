<?php
// models/clientes.model.php
require_once "conexion.php";

class ModelClientes
{

    static public function mdlMostrarClientes($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($item != null) {
            $permitidos = ["cli_id", "cli_telefono", "cli_email"]; // whitelist básica
            if (!in_array($item, $permitidos, true)) {
                return [];
            }

            $stmt = $pdo->prepare("SELECT * FROM $tabla WHERE $item = :val LIMIT 1");
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $stmt = $pdo->prepare("SELECT * FROM $tabla ORDER BY cli_id DESC");
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    static public function mdlGuardarClientes($tabla, $datos)
    {
        $sql = "INSERT INTO $tabla
                (cli_nombre, cli_telefono, cli_email, cli_puntos, cli_activo)
                VALUES
                (:cli_nombre, :cli_telefono, :cli_email, :cli_puntos, :cli_activo)";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":cli_nombre", $datos["cli_nombre"], PDO::PARAM_STR);

        if ($datos["cli_telefono"] === null) $stmt->bindValue(":cli_telefono", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_telefono", $datos["cli_telefono"], PDO::PARAM_STR);

        if ($datos["cli_email"] === null) $stmt->bindValue(":cli_email", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_email", $datos["cli_email"], PDO::PARAM_STR);

        $stmt->bindParam(":cli_puntos", $datos["cli_puntos"], PDO::PARAM_INT);
        $stmt->bindParam(":cli_activo", $datos["cli_activo"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEditarClientes($tabla, $datos)
    {
        $sql = "UPDATE $tabla SET
                  cli_nombre=:cli_nombre,
                  cli_telefono=:cli_telefono,
                  cli_email=:cli_email,
                  cli_puntos=:cli_puntos,
                  cli_activo=:cli_activo
                WHERE cli_id=:cli_id";
        $stmt = conexiondb::conectar()->prepare($sql);

        $stmt->bindParam(":cli_id", $datos["cli_id"], PDO::PARAM_INT);
        $stmt->bindParam(":cli_nombre", $datos["cli_nombre"], PDO::PARAM_STR);

        if ($datos["cli_telefono"] === null) $stmt->bindValue(":cli_telefono", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_telefono", $datos["cli_telefono"], PDO::PARAM_STR);

        if ($datos["cli_email"] === null) $stmt->bindValue(":cli_email", null, PDO::PARAM_NULL);
        else $stmt->bindParam(":cli_email", $datos["cli_email"], PDO::PARAM_STR);

        $stmt->bindParam(":cli_puntos", $datos["cli_puntos"], PDO::PARAM_INT);
        $stmt->bindParam(":cli_activo", $datos["cli_activo"], PDO::PARAM_INT);

        return $stmt->execute() ? "ok" : "error";
    }

    static public function mdlEliminarCliente($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE cli_id=:cli_id");
        $stmt->bindParam(":cli_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }
}