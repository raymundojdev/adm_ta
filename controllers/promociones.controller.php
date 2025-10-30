<?php

class ControllerPromociones
{

    /* Mostrar (uno/todos) */
    static public function ctrMostrarPromociones($item, $valor)
    {
        $tabla = "promociones";
        return ModelPromociones::mdlMostrarPromociones($tabla, $item, $valor);
    }

    /* Guardar (form individual) */
    static public function ctrGuardarPromociones()
    {
        if (isset($_POST["prm_nombre"])) {

            // OPTIMIZACIÓN: sanitizado/trim
            $datos = array(
                "prm_nombre"      => trim($_POST["prm_nombre"]),
                "prm_tipo"        => trim($_POST["prm_tipo"]),
                "prm_valor"       => (float)$_POST["prm_valor"],
                "prm_activa"      => (int)$_POST["prm_activa"],
                "prm_inicio"      => $_POST["prm_inicio"] ?: null,
                "prm_fin"         => $_POST["prm_fin"] ?: null,
                "prm_codigo"      => $_POST["prm_codigo"] ? trim($_POST["prm_codigo"]) : null,
                "prm_descripcion" => isset($_POST["prm_descripcion"]) ? trim($_POST["prm_descripcion"]) : null,
            );

            $resp = ModelPromociones::mdlGuardarPromociones("promociones", $datos);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Promoción agregada con éxito!"})
                  .then(()=>{window.location="promociones"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo guardar (código duplicado u otro error)"})
                  .then(()=>{window.location="promociones"});
                </script>';
            }
        }
    }

    /* Editar */
    static public function ctrEditarPromociones()
    {
        if (isset($_POST["prm_id"])) {
            $datos = array(
                "prm_id"          => (int)$_POST["prm_id"],
                "prm_nombre"      => trim($_POST["editar_nombre"]),
                "prm_tipo"        => trim($_POST["editar_tipo"]),
                "prm_valor"       => (float)$_POST["editar_valor"],
                "prm_activa"      => (int)$_POST["editar_activa"],
                "prm_inicio"      => $_POST["editar_inicio"] ?: null,
                "prm_fin"         => $_POST["editar_fin"] ?: null,
                "prm_codigo"      => $_POST["editar_codigo"] ? trim($_POST["editar_codigo"]) : null,
                "prm_descripcion" => isset($_POST["editar_descripcion"]) ? trim($_POST["editar_descripcion"]) : null,
            );

            $resp = ModelPromociones::mdlEditarPromociones("promociones", $datos);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Promoción editada con éxito!"})
                  .then(()=>{window.location="promociones"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo editar"})
                  .then(()=>{window.location="promociones"});
                </script>';
            }
        }
    }

    /* Eliminar (GET para respetar tu patrón actual) */
    static public function ctrEliminarPromocion()
    {
        if (isset($_GET["idPromocion"])) {
            $id = (int)$_GET["idPromocion"];
            $resp = ModelPromociones::mdlEliminarPromocion("promociones", $id);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Promoción eliminada!"})
                  .then(()=>{window.location="promociones"});
                </script>';
            }
        }
    }

    /* Importar CSV */
    static public function ctrGuardarPromocionesCsv()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
            $archivo = $_FILES['archivo_csv']['tmp_name'];
            if (!is_uploaded_file($archivo)) return;

            if (($handle = fopen($archivo, "r")) !== FALSE) {
                $linea = 0;
                $ok = 0;
                $skip = 0;

                // Encabezado esperado:
                // prm_nombre,prm_tipo,prm_valor,prm_activa,prm_inicio,prm_fin,prm_codigo,prm_descripcion
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($linea === 0) {
                        $linea++;
                        continue;
                    }

                    $datos = [
                        "prm_nombre"      => isset($data[0]) ? trim($data[0]) : "",
                        "prm_tipo"        => isset($data[1]) ? trim($data[1]) : "porcentaje",
                        "prm_valor"       => isset($data[2]) && $data[2] !== "" ? (float)$data[2] : 0,
                        "prm_activa"      => isset($data[3]) ? (int)$data[3] : 1,
                        "prm_inicio"      => isset($data[4]) && $data[4] !== "" ? $data[4] : null,
                        "prm_fin"         => isset($data[5]) && $data[5] !== "" ? $data[5] : null,
                        "prm_codigo"      => isset($data[6]) && $data[6] !== "" ? trim($data[6]) : null,
                        "prm_descripcion" => isset($data[7]) && $data[7] !== "" ? trim($data[7]) : null,
                    ];

                    if ($datos["prm_nombre"] === "" || $datos["prm_valor"] === 0) {
                        $skip++;
                        continue;
                    }

                    $resp = ModelPromociones::mdlGuardarPromociones("promociones", $datos);
                    ($resp == "ok") ? $ok++ : $skip++;
                }
                fclose($handle);

                echo '<script>
                  Swal.fire({
                    icon:"info",
                    title:"CSV procesado",
                    html:"Insertadas: ' . $ok . '<br>Saltadas: ' . $skip . '"
                  }).then(()=>{window.location="promociones"});
                </script>';
            }
        }
    }
}