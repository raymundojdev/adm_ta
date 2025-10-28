<?php
/* ============================================
   ajax/productos.ajax.php
   ============================================ */
header("Content-Type: application/json; charset=UTF-8");

require_once "../models/productos.model.php";
require_once "../controllers/productos.controller.php";

class AjaxProductos {

    public $idProducto;

    public function obtener(){
        $item = "id"; $valor = $this->idProducto;
        $r = ControllerProductos::ctrMostrarProductos($item, $valor);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }

    public function listar(){
        $f = [
            "busqueda"     => $_POST["busqueda"]     ?? "",
            "activo"       => $_POST["activo"]       ?? "",
            "proveedor_id" => $_POST["proveedor_id"] ?? "",
            "page"         => (int)($_POST["page"]     ?? 1),
            "per_page"     => (int)($_POST["per_page"] ?? 20),
        ];
        $rows  = ModelProductos::mdlListarProductosPaginado($f);
        $total = ModelProductos::mdlContarProductos($f);
        echo json_encode([
            "ok"=>true,
            "data"=>$rows,
            "total"=>$total,
            "page"=>$f["page"],
            "per_page"=>$f["per_page"],
            "pages"=> max(1, (int)ceil($total / max(1,$f["per_page"])))
        ], JSON_UNESCAPED_UNICODE);
    }

    public function crear(){
        $_POST["prod_activo"] = isset($_POST["prod_activo"]) ? 1 : 0;
        $resp = ModelProductos::mdlGuardarProductos("productos", [
            "nombre"       => $_POST["prod_nombre"] ?? "",
            "unidad_id"    => (int)($_POST["prod_unidad_id"] ?? 0),
            "costo_ref"    => ($_POST["prod_costo_ref"] ?? "0"),
            "proveedor_id" => ($_POST["prod_proveedor_id"] ?? ""),
            "activo"       => (int)$_POST["prod_activo"]
        ]);
        if($resp === "ok"){
            $pdo = conexiondb::conectar();
            $id = $pdo->lastInsertId();
            $row = ModelProductos::mdlMostrarProductos("productos","id",$id);
            echo json_encode(["ok"=>true,"msg"=>"Producto creado","row"=>$row], JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(["ok"=>false,"msg"=>"No se pudo crear"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actualizar(){
        $_POST["editar_prod_activo"] = isset($_POST["editar_prod_activo"]) ? 1 : 0;
        $resp = ModelProductos::mdlEditarProductos("productos", [
            "id"           => (int)($_POST["idProducto"] ?? 0),
            "nombre"       => $_POST["editar_prod_nombre"] ?? "",
            "unidad_id"    => (int)($_POST["editar_prod_unidad_id"] ?? 0),
            "costo_ref"    => ($_POST["editar_prod_costo_ref"] ?? "0"),
            "proveedor_id" => ($_POST["editar_prod_proveedor_id"] ?? ""),
            "activo"       => (int)$_POST["editar_prod_activo"]
        ]);
        if($resp === "ok"){
            $row = ModelProductos::mdlMostrarProductos("productos","id",(int)$_POST["idProducto"]);
            echo json_encode(["ok"=>true,"msg"=>"Producto actualizado","row"=>$row], JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(["ok"=>false,"msg"=>"No se pudo actualizar"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function eliminar(){
        $id = (int)($_POST["idProducto"] ?? 0);
        $resp = ModelProductos::mdlEliminarProducto("productos", $id);
        echo json_encode($resp === "ok" ? ["ok"=>true,"msg"=>"Eliminado"] : ["ok"=>false,"msg"=>"No se pudo eliminar"], JSON_UNESCAPED_UNICODE);
    }
}

/* Router */
$action = $_POST["__action"] ?? null;
$ajax = new AjaxProductos();

switch ($action) {
    case "obtener":
        $ajax->idProducto = $_POST["idProducto"] ?? null;
        $ajax->obtener();
        break;
    case "listar":
        $ajax->listar();
        break;
    case "crear":
        $ajax->crear();
        break;
    case "actualizar":
        $ajax->actualizar();
        break;
    case "eliminar":
        $ajax->eliminar();
        break;
    default:
        echo json_encode(["ok"=>false,"msg"=>"Acción no válida"], JSON_UNESCAPED_UNICODE);
        break;
}
