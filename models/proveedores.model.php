<?php
// models/ProveedorModel.php

require_once "conexion.php";

class ProveedorModel {

    static public function mdlListar($tabla, $filtros = []) {
        $sql = "SELECT id, nombre, telefono, email, rfc, direccion, estatus, fecha_alta
                FROM {$tabla}";
        $where = [];
        $params = [];

        if (!empty($filtros["buscar"])) {
            $where[] = "(nombre LIKE :q OR telefono LIKE :q OR email LIKE :q OR rfc LIKE :q)";
            $params[":q"] = "%".$filtros["buscar"]."%";
        }
        if (isset($filtros["estatus"]) && in_array($filtros["estatus"], ["ACTIVO","INACTIVO"])) {
            $where[] = "estatus = :estatus";
            $params[":estatus"] = $filtros["estatus"];
        }

        if ($where) $sql .= " WHERE ".implode(" AND ", $where);
        $sql .= " ORDER BY fecha_alta DESC, nombre ASC";

        $stmt = conexiondb::conectar()->prepare($sql);
        foreach ($params as $k=>$v) $stmt->bindValue($k, $v, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlCrear($tabla, $data) {
        $sql = "INSERT INTO {$tabla} (nombre, telefono, email, rfc, direccion, estatus)
                VALUES (:nombre, :telefono, :email, :rfc, :direccion, :estatus)";
        $stmt = conexiondb::conectar()->prepare($sql);
        $stmt->bindValue(":nombre",     trim($data["nombre"]),     PDO::PARAM_STR);
        $stmt->bindValue(":telefono",   $data["telefono"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":email",      $data["email"] ?? null,    PDO::PARAM_STR);
        $stmt->bindValue(":rfc",        $data["rfc"] ?? null,      PDO::PARAM_STR);
        $stmt->bindValue(":direccion",  $data["direccion"] ?? null,PDO::PARAM_STR);
        $stmt->bindValue(":estatus",    $data["estatus"] ?? "ACTIVO", PDO::PARAM_STR);
        if ($stmt->execute()) {
            return conexiondb::conectar()->lastInsertId();
        }
        return false;
    }

    static public function mdlObtener($tabla, $id) {
        $stmt = conexiondb::conectar()->prepare("SELECT * FROM {$tabla} WHERE id = :id LIMIT 1");
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function mdlActualizar($tabla, $data) {
        $sql = "UPDATE {$tabla}
                SET nombre = :nombre,
                    telefono = :telefono,
                    email = :email,
                    rfc = :rfc,
                    direccion = :direccion,
                    estatus = :estatus
                WHERE id = :id";
        $stmt = conexiondb::conectar()->prepare($sql);
        $stmt->bindValue(":id",         (int)$data["id"],          PDO::PARAM_INT);
        $stmt->bindValue(":nombre",     trim($data["nombre"]),     PDO::PARAM_STR);
        $stmt->bindValue(":telefono",   $data["telefono"] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(":email",      $data["email"] ?? null,    PDO::PARAM_STR);
        $stmt->bindValue(":rfc",        $data["rfc"] ?? null,      PDO::PARAM_STR);
        $stmt->bindValue(":direccion",  $data["direccion"] ?? null,PDO::PARAM_STR);
        $stmt->bindValue(":estatus",    $data["estatus"] ?? "ACTIVO", PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Baja lógica: cambia estatus a INACTIVO (evita problemas con FKs)
    static public function mdlDesactivar($tabla, $id) {
        $stmt = conexiondb::conectar()->prepare("UPDATE {$tabla} SET estatus='INACTIVO' WHERE id=:id");
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    static public function mdlActivar($tabla, $id) {
        $stmt = conexiondb::conectar()->prepare("UPDATE {$tabla} SET estatus='ACTIVO' WHERE id=:id");
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar físico (solo si lo requieres; por defecto, recomendamos baja lógica)
    static public function mdlEliminar($tabla, $id) {
        $stmt = conexiondb::conectar()->prepare("DELETE FROM {$tabla} WHERE id=:id");
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
