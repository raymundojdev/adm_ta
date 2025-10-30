<?php
// controllers/clientes.controller.php
class ControllerClientes
{

    /* Mostrar (uno/todos) */
    static public function ctrMostrarClientes($item, $valor)
    {
        $tabla = "clientes";
        return ModelClientes::mdlMostrarClientes($tabla, $item, $valor);
    }

    /* Guardar */
    static public function ctrGuardarClientes()
    {
        if (isset($_POST["cli_nombre"])) {
            $datos = [
                "cli_nombre"   => trim($_POST["cli_nombre"]),
                "cli_telefono" => isset($_POST["cli_telefono"]) && $_POST["cli_telefono"] !== "" ? trim($_POST["cli_telefono"]) : null,
                "cli_email"    => isset($_POST["cli_email"]) && $_POST["cli_email"] !== "" ? trim($_POST["cli_email"]) : null,
                "cli_puntos"   => isset($_POST["cli_puntos"]) && $_POST["cli_puntos"] !== "" ? (int)$_POST["cli_puntos"] : 0,
                "cli_activo"   => (int)$_POST["cli_activo"]
            ];

            $resp = ModelClientes::mdlGuardarClientes("clientes", $datos);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Cliente agregado con éxito!"})
                  .then(()=>{window.location="clientes"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo guardar (teléfono/email duplicado u otro error)"})
                  .then(()=>{window.location="clientes"});
                </script>';
            }
        }
    }

    /* Editar */
    static public function ctrEditarClientes()
    {
        if (isset($_POST["cli_id"])) {
            $datos = [
                "cli_id"       => (int)$_POST["cli_id"],
                "cli_nombre"   => trim($_POST["editar_nombre"]),
                "cli_telefono" => isset($_POST["editar_telefono"]) && $_POST["editar_telefono"] !== "" ? trim($_POST["editar_telefono"]) : null,
                "cli_email"    => isset($_POST["editar_email"]) && $_POST["editar_email"] !== "" ? trim($_POST["editar_email"]) : null,
                "cli_puntos"   => isset($_POST["editar_puntos"]) && $_POST["editar_puntos"] !== "" ? (int)$_POST["editar_puntos"] : 0,
                "cli_activo"   => (int)$_POST["editar_activo"]
            ];

            $resp = ModelClientes::mdlEditarClientes("clientes", $datos);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Cliente editado con éxito!"})
                  .then(()=>{window.location="clientes"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo editar"})
                  .then(()=>{window.location="clientes"});
                </script>';
            }
        }
    }

    /* Eliminar (GET) */
    static public function ctrEliminarCliente()
    {
        if (isset($_GET["idCliente"])) {
            $id = (int)$_GET["idCliente"];
            $resp = ModelClientes::mdlEliminarCliente("clientes", $id);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Cliente eliminado!"})
                  .then(()=>{window.location="clientes"});
                </script>';
            }
        }
    }

    /* Importar CSV */
    static public function ctrGuardarClientesCsv()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
            $archivo = $_FILES['archivo_csv']['tmp_name'];
            if (!is_uploaded_file($archivo)) return;

            if (($handle = fopen($archivo, "r")) !== FALSE) {
                $linea = 0;
                $ok = 0;
                $skip = 0;

                // Encabezado esperado:
                // cli_nombre,cli_telefono,cli_email,cli_puntos,cli_activo
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($linea === 0) {
                        $linea++;
                        continue;
                    }

                    $datos = [
                        "cli_nombre"   => isset($data[0]) ? trim($data[0]) : "",
                        "cli_telefono" => isset($data[1]) && $data[1] !== "" ? trim($data[1]) : null,
                        "cli_email"    => isset($data[2]) && $data[2] !== "" ? trim($data[2]) : null,
                        "cli_puntos"   => isset($data[3]) && $data[3] !== "" ? (int)$data[3] : 0,
                        "cli_activo"   => isset($data[4]) && $data[4] !== "" ? (int)$data[4] : 1,
                    ];

                    if ($datos["cli_nombre"] === "") {
                        $skip++;
                        continue;
                    }

                    $resp = ModelClientes::mdlGuardarClientes("clientes", $datos);
                    ($resp == "ok") ? $ok++ : $skip++;
                }
                fclose($handle);

                echo '<script>
                  Swal.fire({
                    icon:"info",
                    title:"CSV procesado",
                    html:"Insertados: ' . $ok . '<br>Saltados: ' . $skip . '"
                  }).then(()=>{window.location="clientes"});
                </script>';
            }
        }
    }
}