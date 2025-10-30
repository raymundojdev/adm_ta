<?php
// controllers/productos.controller.php
// Se ajusta al esquema: pro_sku, pro_nombre, cat_id, pro_imagen, pro_activo

class ControllerProductos
{

    static public function ctrMostrarProductos($item, $valor)
    {
        $tabla = "productos";
        return ModelProductos::mdlMostrarProductos($tabla, $item, $valor);
    }

    static public function ctrGuardarProductos()
    {
        if (isset($_POST["pro_nombre"])) {

            // (OPT) trim/sanitizado para entradas de texto
            $datos = [
                "pro_sku"     => trim($_POST["pro_sku"]),
                "pro_nombre"  => trim($_POST["pro_nombre"]),
                "cat_id"      => (int)$_POST["cat_id"],
                "pro_imagen"  => isset($_POST["pro_imagen"]) && $_POST["pro_imagen"] !== "" ? trim($_POST["pro_imagen"]) : null,
                "pro_activo"  => (int)$_POST["pro_activo"]
            ];

            $resp = ModelProductos::mdlGuardarProductos("productos", $datos);

            if ($resp == "ok") {
                echo '<script>
                    Swal.fire({icon:"success", title:"¡Producto agregado con éxito!"})
                    .then(()=>{window.location="productos"});
                </script>';
            } else {
                echo '<script>
                    Swal.fire({icon:"error", title:"No se pudo guardar (¿SKU duplicado?)"})
                    .then(()=>{window.location="productos"});
                </script>';
            }
        }
    }

    static public function ctrEditarProductos()
    {
        if (isset($_POST["pro_id"])) {
            $datos = [
                "pro_id"      => (int)$_POST["pro_id"],
                "pro_sku"     => trim($_POST["editar_sku"]),
                "pro_nombre"  => trim($_POST["editar_nombre"]),
                "cat_id"      => (int)$_POST["editar_cat_id"],
                "pro_imagen"  => isset($_POST["editar_imagen"]) && $_POST["editar_imagen"] !== "" ? trim($_POST["editar_imagen"]) : null,
                "pro_activo"  => (int)$_POST["editar_activo"]
            ];

            $resp = ModelProductos::mdlEditarProductos("productos", $datos);

            if ($resp == "ok") {
                echo '<script>
                    Swal.fire({icon:"success", title:"¡Producto editado con éxito!"})
                    .then(()=>{window.location="productos"});
                </script>';
            } else {
                echo '<script>
                    Swal.fire({icon:"error", title:"No se pudo editar"})
                    .then(()=>{window.location="productos"});
                </script>';
            }
        }
    }

    static public function ctrEliminarProducto()
    {
        if (isset($_GET["idProducto"])) {
            $id  = (int)$_GET["idProducto"];
            $resp = ModelProductos::mdlEliminarProducto("productos", $id);

            if ($resp == "ok") {
                echo '<script>
                    Swal.fire({icon:"success", title:"¡Producto eliminado!"})
                    .then(()=>{window.location="productos"});
                </script>';
            }
        }
    }

    // CSV: pro_sku,pro_nombre,cat_id,pro_imagen,pro_activo
    static public function ctrGuardarProductosCsv()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["archivo_csv"])) {
            $archivo = $_FILES["archivo_csv"]["tmp_name"];
            if (!is_uploaded_file($archivo)) return;

            if (($h = fopen($archivo, "r")) !== FALSE) {
                $linea = 0;
                $ok = 0;
                $skip = 0;

                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    if ($linea === 0) {
                        $linea++;
                        continue;
                    } // saltar encabezado

                    $datos = [
                        "pro_sku"     => isset($data[0]) ? trim($data[0]) : "",
                        "pro_nombre"  => isset($data[1]) ? trim($data[1]) : "",
                        "cat_id"      => isset($data[2]) && $data[2] !== "" ? (int)$data[2] : 0,
                        "pro_imagen"  => isset($data[3]) && $data[3] !== "" ? trim($data[3]) : null,
                        "pro_activo"  => isset($data[4]) && $data[4] !== "" ? (int)$data[4] : 1
                    ];

                    // (OPT) validación mínima
                    if ($datos["pro_sku"] === "" || $datos["pro_nombre"] === "" || $datos["cat_id"] <= 0) {
                        $skip++;
                        continue;
                    }

                    $resp = ModelProductos::mdlGuardarProductos("productos", $datos);
                    ($resp == "ok") ? $ok++ : $skip++;
                }
                fclose($h);

                echo '<script>
                  Swal.fire({
                    icon:"info",
                    title:"CSV procesado",
                    html:"Insertados: ' . $ok . '<br>Saltados: ' . $skip . '"
                  }).then(()=>{window.location="productos"});
                </script>';
            }
        }
    }
}