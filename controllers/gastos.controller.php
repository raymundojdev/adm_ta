<?php
// controllers/gastos.controller.php

class ControllerGastos
{

    static public function ctrMostrarGastos($item, $valor)
    {
        $tabla = "gastos";
        return ModelGastos::mdlMostrarGastos($tabla, $item, $valor);
    }

    static public function ctrGuardarGastos()
    {
        if (!isset($_POST["suc_id"])) return;

        $datos = [
            "suc_id"         => (int)$_POST["suc_id"],
            "cor_id"         => (isset($_POST["cor_id"]) && $_POST["cor_id"] !== "") ? (int)$_POST["cor_id"] : null,
            "gas_concepto"   => trim($_POST["gas_concepto"]),
            "gas_monto"      => (float)$_POST["gas_monto"],
            "gas_metodo"     => $_POST["gas_metodo"],
            "gas_fecha"      => $_POST["gas_fecha"],
            "gas_comprobante" => (isset($_POST["gas_comprobante"]) && $_POST["gas_comprobante"] !== "") ? trim($_POST["gas_comprobante"]) : null,
            "gas_nota"       => (isset($_POST["gas_nota"]) && $_POST["gas_nota"] !== "") ? trim($_POST["gas_nota"]) : null,
            "gas_estado"     => $_POST["gas_estado"]
        ];

        $resp = ModelGastos::mdlGuardarGastos("gastos", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Gasto registrado!"})
        .then(()=>{window.location="gastos"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo registrar el gasto"})
        .then(()=>{window.location="gastos"});
      </script>';
        }
    }

    static public function ctrEditarGastos()
    {
        if (!isset($_POST["gas_id"])) return;

        $datos = [
            "gas_id"         => (int)$_POST["gas_id"],
            "suc_id"         => (int)$_POST["editar_suc_id"],
            "cor_id"         => (isset($_POST["editar_cor_id"]) && $_POST["editar_cor_id"] !== "") ? (int)$_POST["editar_cor_id"] : null,
            "gas_concepto"   => trim($_POST["editar_gas_concepto"]),
            "gas_monto"      => (float)$_POST["editar_gas_monto"],
            "gas_metodo"     => $_POST["editar_gas_metodo"],
            "gas_fecha"      => $_POST["editar_gas_fecha"],
            "gas_comprobante" => (isset($_POST["editar_gas_comprobante"]) && $_POST["editar_gas_comprobante"] !== "") ? trim($_POST["editar_gas_comprobante"]) : null,
            "gas_nota"       => (isset($_POST["editar_gas_nota"]) && $_POST["editar_gas_nota"] !== "") ? trim($_POST["editar_gas_nota"]) : null,
            "gas_estado"     => $_POST["editar_gas_estado"]
        ];

        $resp = ModelGastos::mdlEditarGastos("gastos", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Gasto editado!"})
        .then(()=>{window.location="gastos"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo editar el gasto"})
        .then(()=>{window.location="gastos"});
      </script>';
        }
    }

    static public function ctrEliminarGasto()
    {
        if (isset($_GET["idGasto"])) {
            $id = (int)$_GET["idGasto"];
            $resp = ModelGastos::mdlEliminarGasto("gastos", $id);

            if ($resp == "ok") {
                echo '<script>
          Swal.fire({icon:"success", title:"¡Gasto eliminado!"})
          .then(()=>{window.location="gastos"});
        </script>';
            } else {
                echo '<script>
          Swal.fire({icon:"error", title:"No se pudo eliminar"})
          .then(()=>{window.location="gastos"});
        </script>';
            }
        }
    }
}