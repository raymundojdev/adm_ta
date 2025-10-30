<?php
require_once "conexion.php";

class ModelSucursales
{

    /* Mostrar sucursales */
    static public function mdlMostrarSucursales($tabla, $item, $valor)
    {
        if ($item != null) {
            $stmt = conexiondb::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = conexiondb::conectar()->prepare("SELECT * FROM $tabla ORDER BY suc_id DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /* Guardar sucursal */
    static public function mdlGuardarSucursales($tabla, $datos)
    {
        $stmt = conexiondb::conectar()->prepare(
            "INSERT INTO $tabla (suc_nombre, suc_direccion, suc_activa) 
             VALUES (:suc_nombre, :suc_direccion, :suc_activa)"
        );

        $stmt->bindParam(":suc_nombre", $datos["suc_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":suc_direccion", $datos["suc_direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":suc_activa", $datos["suc_activa"], PDO::PARAM_INT);

        return ($stmt->execute()) ? "ok" : "error";
    }

    /* Editar sucursal */
    static public function mdlEditarSucursales($tabla, $datos)
    {
        $stmt = conexiondb::conectar()->prepare(
            "UPDATE $tabla 
             SET suc_nombre=:suc_nombre, suc_direccion=:suc_direccion, suc_activa=:suc_activa 
             WHERE suc_id=:suc_id"
        );

        $stmt->bindParam(":suc_id", $datos["suc_id"], PDO::PARAM_INT);
        $stmt->bindParam(":suc_nombre", $datos["suc_nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":suc_direccion", $datos["suc_direccion"], PDO::PARAM_STR);
        $stmt->bindParam(":suc_activa", $datos["suc_activa"], PDO::PARAM_INT);

        return ($stmt->execute()) ? "ok" : "error";
    }

    /* Eliminar sucursal */
    static public function mdlEliminarSucursal($tabla, $id)
    {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE suc_id=:suc_id");
        $stmt->bindParam(":suc_id", $id, PDO::PARAM_INT);
        return ($stmt->execute()) ? "ok" : "error";
    }
}