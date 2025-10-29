<?php
/* ==========================================================
   ajax/productos.ajax.php  (REEMPLAZA COMPLETO)
   ========================================================== */
header("Content-Type: application/json; charset=UTF-8");

require_once "../models/productos.model.php";
require_once "../controllers/productos.controller.php";
require_once "../models/conexion.php";

class AjaxProductos
{

    public $idProducto;

    public function obtener()
    {
        $item = "id";
        $valor = $this->idProducto;
        $r = ControllerProductos::ctrMostrarProductos($item, $valor);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }

    public function listar()
    {
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
            "ok" => true,
            "data" => $rows,
            "total" => $total,
            "page" => $f["page"],
            "per_page" => $f["per_page"],
            "pages" => max(1, (int)ceil($total / max(1, $f["per_page"])))
        ], JSON_UNESCAPED_UNICODE);
    }

    public function crear()
    {
        $_POST["prod_activo"] = isset($_POST["prod_activo"]) ? 1 : 0;
        $resp = ModelProductos::mdlGuardarProductos("productos", [
            "nombre"       => $_POST["prod_nombre"] ?? "",
            "unidad_id"    => (int)($_POST["prod_unidad_id"] ?? 0),
            "costo_ref"    => ($_POST["prod_costo_ref"] ?? "0"),
            "proveedor_id" => ($_POST["prod_proveedor_id"] ?? ""),
            "activo"       => (int)$_POST["prod_activo"]
        ]);
        if ($resp === "ok") {
            $pdo = conexiondb::conectar();
            $id = $pdo->lastInsertId();
            $row = ModelProductos::mdlMostrarProductos("productos", "id", $id);
            echo json_encode(["ok" => true, "msg" => "Producto creado", "row" => $row], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["ok" => false, "msg" => "No se pudo crear"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actualizar()
    {
        $_POST["editar_prod_activo"] = isset($_POST["editar_prod_activo"]) ? 1 : 0;
        $resp = ModelProductos::mdlEditarProductos("productos", [
            "id"           => (int)($_POST["idProducto"] ?? 0),
            "nombre"       => $_POST["editar_prod_nombre"] ?? "",
            "unidad_id"    => (int)($_POST["editar_prod_unidad_id"] ?? 0),
            "costo_ref"    => ($_POST["editar_prod_costo_ref"] ?? "0"),
            "proveedor_id" => ($_POST["editar_prod_proveedor_id"] ?? ""),
            "activo"       => (int)$_POST["editar_prod_activo"]
        ]);
        if ($resp === "ok") {
            $row = ModelProductos::mdlMostrarProductos("productos", "id", (int)$_POST["idProducto"]);
            echo json_encode(["ok" => true, "msg" => "Producto actualizado", "row" => $row], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["ok" => false, "msg" => "No se pudo actualizar"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function eliminar()
    {
        $id = (int)($_POST["idProducto"] ?? 0);
        $resp = ModelProductos::mdlEliminarProducto("productos", $id);
        echo json_encode($resp === "ok" ? ["ok" => true, "msg" => "Eliminado"] : ["ok" => false, "msg" => "No se pudo eliminar"], JSON_UNESCAPED_UNICODE);
    }

    /* =========================
       IMPORTAR CSV DE PRODUCTOS
       =========================
       Formato CSV (cabecera):
       nombre,unidad,costo_ref,proveedor,activo
       - unidad: acepta nombre o abreviatura (ej. "Kilogramo" o "kg")
       - proveedor: opcional (coincide por nombre exacto)
       - activo: 1 o 0 (opcional, por defecto 1)
    */
    public function importar_csv()
    {
        if (!isset($_FILES["archivo_csv"]) || $_FILES["archivo_csv"]["error"] !== UPLOAD_ERR_OK) {
            echo json_encode(["ok" => false, "msg" => "Archivo no recibido o inválido"], JSON_UNESCAPED_UNICODE);
            return;
        }

        $tmp = $_FILES["archivo_csv"]["tmp_name"];
        $handle = fopen($tmp, "r");
        if (!$handle) {
            echo json_encode(["ok" => false, "msg" => "No se pudo abrir el archivo"], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Detectar si hay BOM y saltarlo
        $firstBytes = fread($handle, 3);
        if ($firstBytes !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $linea = 0;
        $insertados = 0;
        $omitidos = 0;
        $errores = [];

        // Cabecera obligatoria
        $header = fgetcsv($handle, 2000, ",");
        $linea++;

        if (!$header) {
            fclose($handle);
            echo json_encode(["ok" => false, "msg" => "El CSV está vacío"], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Normalizar cabecera
        $map = [];
        foreach ($header as $i => $h) {
            $k = strtolower(trim($h));
            $map[$k] = $i;
        }

        $required = ["nombre", "unidad", "costo_ref"];
        foreach ($required as $r) {
            if (!isset($map[$r])) {
                fclose($handle);
                echo json_encode(["ok" => false, "msg" => "Falta columna requerida: " . $r], JSON_UNESCAPED_UNICODE);
                return;
            }
        }

        // Proveedores y Unidades cache (para menos consultas)
        $cacheUnidad = [];
        $cacheProv   = [];

        // Transacción opcional para boost (inserción por filas con tolerancia a errores)
        $pdo = conexiondb::conectar();
        $pdo->beginTransaction();

        while (($data = fgetcsv($handle, 5000, ",")) !== false) {
            $linea++;

            $nombre      = trim($data[$map["nombre"]] ?? "");
            $unidadTxt   = trim($data[$map["unidad"]] ?? "");
            $costoRef    = trim($data[$map["costo_ref"]] ?? "0");
            $proveedorNm = isset($map["proveedor"]) ? trim($data[$map["proveedor"]] ?? "") : "";
            $activoTxt   = isset($map["activo"]) ? trim($data[$map["activo"]] ?? "1") : "1";

            if ($nombre === "" || $unidadTxt === "") {
                $omitidos++;
                $errores[] = ["linea" => $linea, "error" => "Nombre o unidad vacíos"];
                continue;
            }

            // Resolver unidad
            $unidadKey = strtolower($unidadTxt);
            if (isset($cacheUnidad[$unidadKey])) {
                $unidadId = $cacheUnidad[$unidadKey];
            } else {
                $unidadId = ModelProductos::mdlUnidadIdPorNombreOAbrev($unidadTxt);
                $cacheUnidad[$unidadKey] = $unidadId;
            }
            if (!$unidadId) {
                $omitidos++;
                $errores[] = ["linea" => $linea, "error" => "Unidad no encontrada: " . $unidadTxt];
                continue;
            }

            // Resolver proveedor (opcional)
            $proveedorId = null;
            if ($proveedorNm !== "") {
                $provKey = strtolower($proveedorNm);
                if (isset($cacheProv[$provKey])) {
                    $proveedorId = $cacheProv[$provKey];
                } else {
                    $proveedorId = ModelProductos::mdlProveedorIdPorNombre($proveedorNm);
                    $cacheProv[$provKey] = $proveedorId; // puede ser null si no existe
                }

                if ($proveedorId === null) {
                    // Si el proveedor no existe, lo omitimos pero dejamos continuar (proveedor_id NULL)
                    // También puedes decidir omitir toda la fila:
                    // $omitidos++; $errores[] = ["linea"=>$linea, "error"=>"Proveedor no encontrado: ".$proveedorNm]; continue;
                }
            }

            // Activo
            $activo = ($activoTxt === "" ? 1 : (int)$activoTxt);
            $activo = ($activo === 1) ? 1 : 0;

            // Costo
            $costo = is_numeric($costoRef) ? (float)$costoRef : 0.0;

            // Insertar
            $resp = ModelProductos::mdlGuardarProductos("productos", [
                "nombre"       => $nombre,
                "unidad_id"    => (int)$unidadId,
                "costo_ref"    => $costo,
                "proveedor_id" => $proveedorId,
                "activo"       => $activo
            ]);

            if ($resp === "ok") {
                $insertados++;
            } else {
                $omitidos++;
                $errores[] = ["linea" => $linea, "error" => "Error al insertar"];
            }
        }

        $pdo->commit();
        fclose($handle);

        echo json_encode([
            "ok" => true,
            "msg" => "Importación finalizada",
            "insertados" => $insertados,
            "omitidos" => $omitidos,
            "errores" => $errores
        ], JSON_UNESCAPED_UNICODE);
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
    case "importar_csv":
        $ajax->importar_csv();
        break;
    default:
        echo json_encode(["ok" => false, "msg" => "Acción no válida"], JSON_UNESCAPED_UNICODE);
        break;
}
