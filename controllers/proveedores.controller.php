<?php


class ProveedoresController {

    static public function ctrListar() {
        $filtros = [];
        if (!empty($_GET["q"]))       $filtros["buscar"]  = trim($_GET["q"]);
        if (!empty($_GET["estatus"])) $filtros["estatus"] = $_GET["estatus"];
        return ProveedorModel::mdlListar("proveedores", $filtros);
    }

    static public function ctrCrear() {
        if (!isset($_POST["nombre"]) || trim($_POST["nombre"])==="") {
            return ["ok"=>false, "msg"=>"El nombre es obligatorio."];
        }
        $data = [
            "nombre"    => $_POST["nombre"],
            "telefono"  => $_POST["telefono"] ?? null,
            "email"     => $_POST["email"] ?? null,
            "rfc"       => $_POST["rfc"] ?? null,
            "direccion" => $_POST["direccion"] ?? null,
            "estatus"   => $_POST["estatus"] ?? "ACTIVO"
        ];
        $id = ProveedorModel::mdlCrear("proveedores", $data);
        if ($id) return ["ok"=>true, "msg"=>"Proveedor creado", "id"=>$id];
        return ["ok"=>false, "msg"=>"No se pudo crear el proveedor"];
    }

    static public function ctrObtener() {
        if (empty($_GET["id"])) return ["ok"=>false, "msg"=>"ID requerido"];
        $prov = ProveedorModel::mdlObtener("proveedores", (int)$_GET["id"]);
        if ($prov) return ["ok"=>true, "data"=>$prov];
        return ["ok"=>false, "msg"=>"Proveedor no encontrado"];
    }

    static public function ctrActualizar() {
        if (empty($_POST["id"])) return ["ok"=>false, "msg"=>"ID requerido"];
        if (!isset($_POST["nombre"]) || trim($_POST["nombre"])==="") {
            return ["ok"=>false, "msg"=>"El nombre es obligatorio."];
        }
        $data = [
            "id"        => (int)$_POST["id"],
            "nombre"    => $_POST["nombre"],
            "telefono"  => $_POST["telefono"] ?? null,
            "email"     => $_POST["email"] ?? null,
            "rfc"       => $_POST["rfc"] ?? null,
            "direccion" => $_POST["direccion"] ?? null,
            "estatus"   => $_POST["estatus"] ?? "ACTIVO"
        ];
        $ok = ProveedorModel::mdlActualizar("proveedores", $data);
        return $ok ? ["ok"=>true, "msg"=>"Proveedor actualizado"]
                   : ["ok"=>false,"msg"=>"No se pudo actualizar"];
    }

    static public function ctrDesactivar() {
        if (empty($_POST["id"])) return ["ok"=>false, "msg"=>"ID requerido"];
        $ok = ProveedorModel::mdlDesactivar("proveedores", (int)$_POST["id"]);
        return $ok ? ["ok"=>true, "msg"=>"Proveedor desactivado"] : ["ok"=>false, "msg"=>"No se pudo desactivar"];
    }

    static public function ctrActivar() {
        if (empty($_POST["id"])) return ["ok"=>false, "msg"=>"ID requerido"];
        $ok = ProveedorModel::mdlActivar("proveedores", (int)$_POST["id"]);
        return $ok ? ["ok"=>true, "msg"=>"Proveedor activado"] : ["ok"=>false, "msg"=>"No se pudo activar"];
    }

    // Opcional: eliminar físico
    static public function ctrEliminar() {
        if (empty($_POST["id"])) return ["ok"=>false, "msg"=>"ID requerido"];
        $ok = ProveedorModel::mdlEliminar("proveedores", (int)$_POST["id"]);
        return $ok ? ["ok"=>true, "msg"=>"Proveedor eliminado"] : ["ok"=>false, "msg"=>"No se pudo eliminar"];
    }
}
