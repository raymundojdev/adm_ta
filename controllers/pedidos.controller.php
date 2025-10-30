<?php
class ControllerPedidos
{

    /* Listar / Mostrar uno */
    static public function ctrMostrarPedidos($item, $valor)
    {
        $tabla = "pedidos";
        return ModelPedidos::mdlMostrarPedidos($tabla, $item, $valor);
    }

    /* Guardar (encabezado + detalles) */
    static public function ctrGuardarPedidos()
    {
        if (!isset($_POST["suc_id"]) || !isset($_POST["pro_id"])) return;

        $encabezado = [
            "cli_id"     => isset($_POST["cli_id"]) && $_POST["cli_id"] !== "" ? (int)$_POST["cli_id"] : null,
            "suc_id"     => (int)$_POST["suc_id"],
            "ped_folio"  => trim($_POST["ped_folio"]),
            "ped_tipo"   => $_POST["ped_tipo"],     // 'MOSTRADOR' | 'ONLINE'
            "ped_estado" => $_POST["ped_estado"]    // 'PENDIENTE' | 'PAGADO' | 'CANCELADO'
        ];

        // Arreglos de detalle (no JSON)
        $pro_ids   = $_POST["pro_id"];         // []
        $cantidades = $_POST["pde_cantidad"];   // []
        $precios   = $_POST["pde_precio"];     // []

        // Calcular totales
        $total = 0;
        $puntos = 0;
        $detalles = [];
        for ($i = 0; $i < count($pro_ids); $i++) {
            $pid   = (int)$pro_ids[$i];
            $cant  = max(1, (int)$cantidades[$i]);
            $prec  = (float)$precios[$i];
            $sub   = $cant * $prec;
            $total += $sub;
            $puntos += $cant; // (REGRA PUNTOS) 1 punto por taco. Cambia aquí si lo requieres.

            $detalles[] = [
                "pro_id"       => $pid,
                "pde_cantidad" => $cant,
                "pde_precio"   => $prec,
                "pde_subtotal" => $sub
            ];
        }

        $encabezado["ped_total"] = $total;
        $encabezado["ped_puntos_generados"] = $puntos;

        $resp = ModelPedidos::mdlGuardarPedidoConDetalles("pedidos", "pedidos_detalles", $encabezado, $detalles);

        if ($resp["status"] === "ok") {
            // Sumar puntos al cliente (si hay cliente)
            if (!empty($encabezado["cli_id"])) {
                ModelPedidos::mdlSumarPuntosCliente("clientes", (int)$encabezado["cli_id"], (int)$encabezado["ped_puntos_generados"]);
            }

            echo '<script>
              Swal.fire({icon:"success", title:"¡Pedido guardado!"})
              .then(()=>{window.location="pedidos"});
            </script>';
        } else {
            echo '<script>
              Swal.fire({icon:"error", title:"No se pudo guardar: ' . htmlspecialchars($resp["error"]) . '"})
              .then(()=>{window.location="pedidos"});
            </script>';
        }
    }

    /* Editar (encabezado + reemplazo completo de detalles) */
    static public function ctrEditarPedidos()
    {
        if (!isset($_POST["ped_id"])) return;

        $ped_id = (int)$_POST["ped_id"];
        $encabezado = [
            "ped_id"     => $ped_id,
            "cli_id"     => isset($_POST["editar_cli_id"]) && $_POST["editar_cli_id"] !== "" ? (int)$_POST["editar_cli_id"] : null,
            "suc_id"     => (int)$_POST["editar_suc_id"],
            "ped_folio"  => trim($_POST["editar_folio"]),
            "ped_tipo"   => $_POST["editar_tipo"],
            "ped_estado" => $_POST["editar_estado"]
        ];

        $detalles = [];
        if (isset($_POST["editar_pro_id"])) {
            $pro_ids    = $_POST["editar_pro_id"];
            $cantidades = $_POST["editar_pde_cantidad"];
            $precios    = $_POST["editar_pde_precio"];

            $total = 0;
            $puntos = 0;
            for ($i = 0; $i < count($pro_ids); $i++) {
                $pid  = (int)$pro_ids[$i];
                $cant = max(1, (int)$cantidades[$i]);
                $prec = (float)$precios[$i];
                $sub  = $cant * $prec;

                $total  += $sub;
                $puntos += $cant;

                $detalles[] = [
                    "pro_id"       => $pid,
                    "pde_cantidad" => $cant,
                    "pde_precio"   => $prec,
                    "pde_subtotal" => $sub
                ];
            }
            $encabezado["ped_total"] = $total;
            $encabezado["ped_puntos_generados"] = $puntos;
        }

        $resp = ModelPedidos::mdlEditarPedidoConDetalles("pedidos", "pedidos_detalles", $encabezado, $detalles);

        if ($resp["status"] === "ok") {
            echo '<script>
              Swal.fire({icon:"success", title:"¡Pedido editado!"})
              .then(()=>{window.location="pedidos"});
            </script>';
        } else {
            echo '<script>
              Swal.fire({icon:"error", title:"No se pudo editar: ' . htmlspecialchars($resp["error"]) . '"})
              .then(()=>{window.location="pedidos"});
            </script>';
        }
    }

    /* Eliminar (GET) */
    static public function ctrEliminarPedido()
    {
        if (isset($_GET["idPedido"])) {
            $id = (int)$_GET["idPedido"];
            $resp = ModelPedidos::mdlEliminarPedido("pedidos", "pedidos_detalles", $id);

            if ($resp === "ok") {
                echo '<script>
                  Swal.fire({icon:"success", title:"¡Pedido eliminado!"})
                  .then(()=>{window.location="pedidos"});
                </script>';
            } else {
                echo '<script>
                  Swal.fire({icon:"error", title:"No se pudo eliminar"})
                  .then(()=>{window.location="pedidos"});
                </script>';
            }
        }
    }
}