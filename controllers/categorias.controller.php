<?php
class ControllerCategorias
{

    static public function ctrMostrarCategorias($item, $valor)
    {
        $tabla = "categorias";
        return ModelCategorias::mdlMostrarCategorias($tabla, $item, $valor);
    }

    static public function ctrGuardarCategorias()
    {
        if (isset($_POST["cat_nombre"])) {
            $nombre = trim($_POST["cat_nombre"]);
            $activa = (int)$_POST["cat_activa"];

            $datos = [
                "cat_nombre" => $nombre,
                "cat_activa" => $activa
            ];

            $respuesta = ModelCategorias::mdlGuardarCategorias("categorias", $datos);

            if ($respuesta == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Categoría agregada con éxito!"})
                  .then(()=>{window.location="categorias"});
                </script>';
            }
        }
    }

    static public function ctrEditarCategorias()
    {
        if (isset($_POST["cat_id"])) {
            $datos = [
                "cat_id"     => (int)$_POST["cat_id"],
                "cat_nombre" => trim($_POST["editar_nombre"]),
                "cat_activa" => (int)$_POST["editar_activa"]
            ];

            $respuesta = ModelCategorias::mdlEditarCategorias("categorias", $datos);

            if ($respuesta == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Categoría editada con éxito!"})
                  .then(()=>{window.location="categorias"});
                </script>';
            }
        }
    }

    static public function ctrEliminarCategoria()
    {
        if (isset($_GET["idCategoria"])) {
            $id = (int)$_GET["idCategoria"];
            $respuesta = ModelCategorias::mdlEliminarCategoria("categorias", $id);

            if ($respuesta == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Categoría eliminada con éxito!"})
                  .then(()=>{window.location="categorias"});
                </script>';
            }
        }
    }

    static public function ctrGuardarCategoriasCsv()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["archivo_csv"])) {
            $archivo = $_FILES["archivo_csv"]["tmp_name"];
            if (!is_uploaded_file($archivo)) return;

            if (($handle = fopen($archivo, "r")) !== FALSE) {
                $linea = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($linea === 0) {
                        $linea++;
                        continue;
                    }

                    $datos = [
                        "cat_nombre" => trim($data[0]),
                        "cat_activa" => isset($data[1]) ? (int)$data[1] : 1
                    ];
                    ModelCategorias::mdlGuardarCategorias("categorias", $datos);
                }
                fclose($handle);
            }
        }
    }
}