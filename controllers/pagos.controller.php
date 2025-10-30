<?php
class ControllerPagos
{

    static public function ctrMostrarPagos($item, $valor)
    {
        $tabla = "pagos";
        return ModelPagos::mdlMostrarPagos($tabla, $item, $valor);
    }

    static public function ctrGuardarPagos()
    {
        if (!isset($_POST["ped_id"])) return;

        $datos = [
            "ped_id"        => (int)$_POST["ped_id"],
            "pag_monto"     => (float)$_POST["pag_monto"],
            "pag_metodo"    => $_POST["pag_metodo"],
            "pag_recibido"  => isset($_POST["pag_recibido"]) && $_POST["pag_recibido"] !== "" ? (float)$_POST["pag_recibido"] : null,
            "pag_cambio"    => isset($_POST["pag_cambio"])   && $_POST["pag_cambio"]   !== "" ? (float)$_POST["pag_cambio"]   : null,
            "pag_referencia" => isset($_POST["pag_referencia"]) ? trim($_POST["pag_referencia"]) : null,
            "pag_estado"    => $_POST["pag_estado"]
        ];

        $resp = ModelPagos::mdlGuardarPagos("pagos", $datos);

        if ($resp == "ok") {
            echo '<script>
              Swal.fire({icon:"success", title:"¡Pago registrado!"})
              .then(()=>{window.location="pagos"});
            </script>';
        } else {
            echo '<script>
              Swal.fire({icon:"error", title:"No se pudo registrar"})
              .then(()=>{window.location="pagos"});
            </script>';
        }
    }

    static public function ctrEditarPagos()
    {
        if (!isset($_POST["pag_id"])) return;

        $datos = [
            "pag_id"        => (int)$_POST["pag_id"],
            "ped_id"        => (int)$_POST["editar_ped_id"],
            "pag_monto"     => (float)$_POST["editar_pag_monto"],
            "pag_metodo"    => $_POST["editar_pag_metodo"],
            "pag_recibido"  => isset($_POST["editar_pag_recibido"]) && $_POST["editar_pag_recibido"] !== "" ? (float)$_POST["editar_pag_recibido"] : null,
            "pag_cambio"    => isset($_POST["editar_pag_cambio"])   && $_POST["editar_pag_cambio"]   !== "" ? (float)$_POST["editar_pag_cambio"]   : null,
            "pag_referencia" => isset($_POST["editar_pag_referencia"]) ? trim($_POST["editar_pag_referencia"]) : null,
            "pag_estado"    => $_POST["editar_pag_estado"]
        ];

        $resp = ModelPagos::mdlEditarPagos("pagos", $datos);

        if ($resp == "ok") {
            echo '<script>
              Swal.fire({icon:"success", title:"¡Pago editado!"})
              .then(()=>{window.location="pagos"});
            </script>';
        } else {
            echo '<script>
              Swal.fire({icon:"error", title:"No se pudo editar"})
              .then(()=>{window.location="pagos"});
            </script>';
        }
    }

    static public function ctrEliminarPago()
    {
        if (isset($_GET["idPago"])) {
            $id = (int)$_GET["idPago"];
            $resp = ModelPagos::mdlEliminarPago("pagos", $id);

            if ($resp == "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Pago eliminado!"})
                  .then(()=>{window.location="pagos"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo eliminar"})
                  .then(()=>{window.location="pagos"});
                </script>';
            }
        }
    }
}