<?php
class ControllerVentas
{

    /* Listar / uno */
    static public function ctrMostrarVentas($item, $valor)
    {
        $tabla = "ventas";
        return ModelVentas::mdlMostrarVentas($tabla, $item, $valor);
    }

    /* Guardar */
    static public function ctrGuardarVenta()
    {
        if (!isset($_POST["suc_id"])) return;

        $datos = [
            "suc_id"              => (int)$_POST["suc_id"],
            "cli_id"              => (isset($_POST["cli_id"]) && $_POST["cli_id"] !== "") ? (int)$_POST["cli_id"] : null,
            "ven_fecha"           => $_POST["ven_fecha"],
            "ven_total"           => (float)$_POST["ven_total"],
            "ven_tacos_vendidos"  => (int)$_POST["ven_tacos_vendidos"],
            "ven_puntos_otorgados" => (int)$_POST["ven_puntos_otorgados"],
            "ven_activa"          => (int)$_POST["ven_activa"]
        ];

        $resp = ModelVentas::mdlGuardarVenta("ventas", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Venta registrada!"})
        .then(()=>{window.location="ventas"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo registrar la venta"})
        .then(()=>{window.location="ventas"});
      </script>';
        }
    }

    /* Editar */
    static public function ctrEditarVenta()
    {
        if (!isset($_POST["ven_id"])) return;

        $datos = [
            "ven_id"              => (int)$_POST["ven_id"],
            "suc_id"              => (int)$_POST["editar_suc_id"],
            "cli_id"              => (isset($_POST["editar_cli_id"]) && $_POST["editar_cli_id"] !== "") ? (int)$_POST["editar_cli_id"] : null,
            "ven_fecha"           => $_POST["editar_ven_fecha"],
            "ven_total"           => (float)$_POST["editar_ven_total"],
            "ven_tacos_vendidos"  => (int)$_POST["editar_ven_tacos_vendidos"],
            "ven_puntos_otorgados" => (int)$_POST["editar_ven_puntos_otorgados"],
            "ven_activa"          => (int)$_POST["editar_ven_activa"]
        ];

        $resp = ModelVentas::mdlEditarVenta("ventas", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Venta actualizada!"})
        .then(()=>{window.location="ventas"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo actualizar"})
        .then(()=>{window.location="ventas"});
      </script>';
        }
    }

    /* Eliminar por GET */
    static public function ctrEliminarVenta()
    {
        if (isset($_GET["idVenta"])) {
            $id  = (int)$_GET["idVenta"];
            $resp = ModelVentas::mdlEliminarVenta("ventas", $id);

            if ($resp == "ok") {
                echo '<script>
          Swal.fire({icon:"success", title:"¡Venta eliminada!"})
          .then(()=>{window.location="ventas"});
        </script>';
            } else {
                echo '<script>
          Swal.fire({icon:"error", title:"No se pudo eliminar"})
          .then(()=>{window.location="ventas"});
        </script>';
            }
        }
    }
}
