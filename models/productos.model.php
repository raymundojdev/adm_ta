<?php
/* ============================================
   models/productos.model.php
   ============================================ */
require_once "conexion.php";

class ModelProductos {

    /* ===== Listado paginado y filtrado (rápido) ===== */
    static public function mdlListarProductosPaginado($filtros){
        $pdo = conexiondb::conectar();

        $where = [];
        $params = [];

        if (!empty($filtros['busqueda'])) {
            $where[] = "(p.nombre LIKE :q OR pr.nombre LIKE :q)";
            $params[":q"] = "%".$filtros['busqueda']."%";
        }
        if ($filtros['activo'] !== '' && $filtros['activo'] !== null) {
            $where[] = "p.activo = :activo";
            $params[":activo"] = (int)$filtros['activo'];
        }
        if (!empty($filtros['proveedor_id'])) {
            $where[] = "p.proveedor_id = :proveedor_id";
            $params[":proveedor_id"] = (int)$filtros['proveedor_id'];
        }

        $sql = "SELECT p.id, p.nombre, p.costo_ref, p.unidad_id, p.proveedor_id, p.activo,
                       u.abrev AS unidad_abrev,
                       pr.nombre AS proveedor_nombre
                FROM productos p
                JOIN unidades u ON u.id = p.unidad_id
                LEFT JOIN proveedores pr ON pr.id = p.proveedor_id";
        if ($where) $sql .= " WHERE ".implode(" AND ", $where);
        $sql .= " ORDER BY p.nombre ASC";

        $page    = max(1, (int)($filtros['page'] ?? 1));
        $perPage = max(5, min(100, (int)($filtros['per_page'] ?? 20)));
        $offset  = ($page - 1) * $perPage;

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(":limit",  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset,  PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $rows;
    }

    static public function mdlContarProductos($filtros){
        $pdo = conexiondb::conectar();

        $where = [];
        $params = [];

        if (!empty($filtros['busqueda'])) {
            $where[] = "(p.nombre LIKE :q OR pr.nombre LIKE :q)";
            $params[":q"] = "%".$filtros['busqueda']."%";
        }
        if ($filtros['activo'] !== '' && $filtros['activo'] !== null) {
            $where[] = "p.activo = :activo";
            $params[":activo"] = (int)$filtros['activo'];
        }
        if (!empty($filtros['proveedor_id'])) {
            $where[] = "p.proveedor_id = :proveedor_id";
            $params[":proveedor_id"] = (int)$filtros['proveedor_id'];
        }

        $sql = "SELECT COUNT(*) AS total
                FROM productos p
                LEFT JOIN proveedores pr ON pr.id = p.proveedor_id";
        if ($where) $sql .= " WHERE ".implode(" AND ", $where);

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $total = (int)$stmt->fetchColumn();
        $stmt = null;

        return $total;
    }

    /* ===== Mostrar uno / todos (mantiene tu firma) ===== */
    static public function mdlMostrarProductos($tabla, $item, $valor){
        if($item != null){
            $stmt = conexiondb::conectar()->prepare(
                "SELECT p.*, u.nombre AS unidad_nombre, u.abrev AS unidad_abrev, pr.nombre AS proveedor_nombre
                 FROM $tabla p
                 JOIN unidades u ON u.id = p.unidad_id
                 LEFT JOIN proveedores pr ON pr.id = p.proveedor_id
                 WHERE p.$item = :$item LIMIT 1"
            );
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt = null;
            return $row;
        }else{
            $stmt = conexiondb::conectar()->prepare(
                "SELECT p.*, u.nombre AS unidad_nombre, u.abrev AS unidad_abrev, pr.nombre AS proveedor_nombre
                 FROM $tabla p
                 JOIN unidades u ON u.id = p.unidad_id
                 LEFT JOIN proveedores pr ON pr.id = p.proveedor_id
                 ORDER BY p.nombre ASC"
            );
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $stmt = null;
            return $rows;
        }
    }

    /* ===== CRUD ===== */
    static public function mdlGuardarProductos($tabla, $datos){
        $stmt = conexiondb::conectar()->prepare(
            "INSERT INTO $tabla (nombre, unidad_id, costo_ref, proveedor_id, activo)
             VALUES (:nombre, :unidad_id, :costo_ref, :proveedor_id, :activo)"
        );
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":unidad_id", $datos["unidad_id"], PDO::PARAM_INT);
        $stmt->bindParam(":costo_ref", $datos["costo_ref"]);
        if($datos["proveedor_id"] === "" || $datos["proveedor_id"] === null){
            $stmt->bindValue(":proveedor_id", null, PDO::PARAM_NULL);
        }else{
            $stmt->bindParam(":proveedor_id", $datos["proveedor_id"], PDO::PARAM_INT);
        }
        $stmt->bindParam(":activo", $datos["activo"], PDO::PARAM_INT);
        $ok = $stmt->execute();
        $stmt = null;
        return $ok ? "ok" : "error";
    }

    static public function mdlEditarProductos($tabla, $datos){
        $stmt = conexiondb::conectar()->prepare(
            "UPDATE $tabla
             SET nombre=:nombre, unidad_id=:unidad_id, costo_ref=:costo_ref, proveedor_id=:proveedor_id, activo=:activo
             WHERE id=:id"
        );
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":unidad_id", $datos["unidad_id"], PDO::PARAM_INT);
        $stmt->bindParam(":costo_ref", $datos["costo_ref"]);
        if($datos["proveedor_id"] === "" || $datos["proveedor_id"] === null){
            $stmt->bindValue(":proveedor_id", null, PDO::PARAM_NULL);
        }else{
            $stmt->bindParam(":proveedor_id", $datos["proveedor_id"], PDO::PARAM_INT);
        }
        $stmt->bindParam(":activo", $datos["activo"], PDO::PARAM_INT);
        $ok = $stmt->execute();
        $stmt = null;
        return $ok ? "ok" : "error";
    }

    static public function mdlEliminarProducto($tabla, $datos){
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE id=:id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);
        $ok = $stmt->execute();
        $stmt = null;
        return $ok ? "ok" : "error";
    }

    /* ===== Catálogos ligeros ===== */
    static public function mdlUnidades(){
        $stmt = conexiondb::conectar()->prepare("SELECT id, nombre, abrev FROM unidades ORDER BY nombre ASC");
        $stmt->execute();
        $res = $stmt->fetchAll();
        $stmt = null;
        return $res;
    }

    static public function mdlProveedoresActivos(){
        $stmt = conexiondb::conectar()->prepare("SELECT id, nombre FROM proveedores WHERE estatus='ACTIVO' ORDER BY nombre ASC");
        $stmt->execute();
        $res = $stmt->fetchAll();
        $stmt = null;
        return $res;
    }
}
