<?php
// controllers/cortes.controller.php

class ControllerCortes
{

    /* Listar / uno */
    static public function ctrMostrarCortes($item, $valor)
    {
        $tabla = "cortes_caja";
        return ModelCortes::mdlMostrarCortes($tabla, $item, $valor);
    }

    /* Abrir corte (apertura) */
    static public function ctrAbrirCorte()
    {
        if (!isset($_POST["suc_id"])) return;

        $datos = [
            "suc_id"            => (int)$_POST["suc_id"],
            "usr_id"            => isset($_POST["usr_id"]) && $_POST["usr_id"] !== "" ? (int)$_POST["usr_id"] : null,
            "cor_turno"         => $_POST["cor_turno"],
            "cor_inicio"        => $_POST["cor_inicio"],
            "cor_fondo_inicial" => (float)$_POST["cor_fondo_inicial"],
            "cor_observaciones" => isset($_POST["cor_observaciones"]) ? trim($_POST["cor_observaciones"]) : null
        ];

        $resp = ModelCortes::mdlAbrirCorte("cortes_caja", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Corte abierto!"})
        .then(()=>{window.location="cortes_caja"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo abrir el corte"})
        .then(()=>{window.location="cortes_caja"});
      </script>';
        }
    }

    /* Calcular totales del sistema (pagos vinculados a pedidos por sucursal y rango) */
    static public function ctrCalcularTotales($suc_id, $inicio, $fin)
    {
        return ModelCortes::mdlSumasPagosPorRango("pagos", "pedidos", (int)$suc_id, $inicio, $fin);
    }

    /* Cerrar corte (actualiza totales sistema + declarados, diferencia y estado) */
    static public function ctrCerrarCorte()
    {
        if (!isset($_POST["cor_id"])) return;

        $cor_id = (int)$_POST["cor_id"];

        $suc_id = (int)$_POST["editar_suc_id"];
        $ini    = $_POST["editar_cor_inicio"];
        $fin    = $_POST["editar_cor_fin"];

        // Totales del sistema (recalcular en server para confiabilidad)
        $tot = self::ctrCalcularTotales($suc_id, $ini, $fin);

        $sistema_total = (float)($tot["total_sistema"] ?? 0);

        // Declarado por caja (inputs manuales)
        $decl_efectivo = (float)($_POST["editar_decl_efectivo"] ?? 0);
        $decl_tarjeta  = (float)($_POST["editar_decl_tarjeta"] ?? 0);
        $decl_transf   = (float)($_POST["editar_decl_transfer"] ?? 0);
        $decl_mixto    = (float)($_POST["editar_decl_mixto"] ?? 0);

        $gastos        = (float)($_POST["editar_gastos"] ?? 0);
        $ing_extra     = (float)($_POST["editar_ingresos_extra"] ?? 0);
        $fondo_inicial = (float)$_POST["editar_cor_fondo_inicial"];

        $total_declarado = $decl_efectivo + $decl_tarjeta + $decl_transf + $decl_mixto;

        // Fórmula de diferencia:
        // (declarado + gastos - ingresos_extra) - (sistema_total + fondo_inicial)
        // Ajusta a tu operación si lo prefieres distinto.
        $diferencia = ($total_declarado + $gastos - $ing_extra) - ($sistema_total + $fondo_inicial);

        $datos = [
            "cor_id"              => $cor_id,
            "suc_id"              => $suc_id,
            "cor_turno"           => $_POST["editar_cor_turno"],
            "cor_inicio"          => $ini,
            "cor_fin"             => $fin,
            "cor_fondo_inicial"   => $fondo_inicial,
            "cor_total_efectivo"  => (float)($tot["efectivo"] ?? 0),
            "cor_total_tarjeta"   => (float)($tot["tarjeta"] ?? 0),
            "cor_total_transfer"  => (float)($tot["transferencia"] ?? 0),
            "cor_total_mixto"     => (float)($tot["mixto"] ?? 0),
            "cor_total_sistema"   => $sistema_total,
            "cor_gastos"          => $gastos,
            "cor_ingresos_extra"  => $ing_extra,
            "cor_total_declarado" => $total_declarado,
            "cor_diferencia"      => $diferencia,
            "cor_observaciones"   => isset($_POST["editar_cor_observaciones"]) ? trim($_POST["editar_cor_observaciones"]) : null,
            "cor_estado"          => "CERRADO"
        ];

        $resp = ModelCortes::mdlCerrarCorte("cortes_caja", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Corte cerrado!"})
        .then(()=>{window.location="cortes_caja"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo cerrar el corte"})
        .then(()=>{window.location="cortes_caja"});
      </script>';
        }
    }

    static public function ctrEliminarCorte()
    {
        if (isset($_GET["idCorte"])) {
            $id = (int)$_GET["idCorte"];
            $resp = ModelCortes::mdlEliminarCorte("cortes_caja", $id);

            if ($resp == "ok") {
                echo '<script>
          Swal.fire({icon:"success", title:"¡Corte eliminado!"})
          .then(()=>{window.location="cortes_caja"});
        </script>';
            } else {
                echo '<script>
          Swal.fire({icon:"error", title:"No se pudo eliminar"})
          .then(()=>{window.location="cortes_caja"});
        </script>';
            }
        }
    }
}