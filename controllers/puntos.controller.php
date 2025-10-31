<?php
// controllers/puntos.controller.php

class ControllerPuntos {

  /* =========================
     LISTAR / UNO
     ========================= */
  static public function ctrMostrarMovimientos($item, $valor) {
    $tabla = "puntos_movimientos";
    return ModelPuntos::mdlMostrarMovimientos($tabla, $item, $valor);
  }

  /* =========================
     SALDO CLIENTE
     ========================= */
  static public function ctrSaldoCliente($cli_id) {
    return ModelPuntos::mdlSaldoCliente("puntos_movimientos", (int)$cli_id);
  }

  /* =========================
     GUARDAR (acum/redim/ajuste)
     ========================= */
  static public function ctrGuardarMovimiento() {
    if (!isset($_POST["cli_id"])) return;

    $cli_id     = (int)$_POST["cli_id"];
    $suc_id     = (int)$_POST["suc_id"];
    $ped_id     = (isset($_POST["ped_id"]) && $_POST["ped_id"]!=="") ? (int)$_POST["ped_id"] : null;
    $pmv_tipo   = $_POST["pmv_tipo"]; // ACUM | REDIM | AJUSTE
    $pmv_puntos = (int)$_POST["pmv_puntos"]; // positivo o negativo (validación abajo)
    $pmv_nota   = isset($_POST["pmv_nota"]) && $_POST["pmv_nota"]!=="" ? trim($_POST["pmv_nota"]) : null;

    // Normalizamos puntos para REDIM a negativo (si enviaron positivo)
    if ($pmv_tipo === "REDIM" && $pmv_puntos > 0) $pmv_puntos = -$pmv_puntos; // (OPT) coherencia
    if ($pmv_tipo === "ACUM" && $pmv_puntos < 0) $pmv_puntos = -$pmv_puntos;   // (OPT)

    // Saldo anterior
    $saldoAnt = self::ctrSaldoCliente($cli_id)["saldo"];

    // Validación de no sobreredimir
    if (($saldoAnt + $pmv_puntos) < 0) {
      echo '<script>
        Swal.fire({icon:"warning", title:"Saldo insuficiente para redimir"})
        .then(()=>{window.location="puntos";});
      </script>';
      return;
    }

    $datos = [
      "cli_id"     => $cli_id,
      "suc_id"     => $suc_id,
      "ped_id"     => $ped_id,
      "pmv_tipo"   => $pmv_tipo,
      "pmv_puntos" => $pmv_puntos,
      "pmv_saldo"  => $saldoAnt + $pmv_puntos, // (OPT) guardar saldo resultante
      "pmv_nota"   => $pmv_nota
    ];

    $resp = ModelPuntos::mdlGuardarMovimiento("puntos_movimientos", $datos);

    if ($resp == "ok") {
      echo '<script>
        Swal.fire({icon:"success", title:"¡Movimiento registrado!"})
        .then(()=>{window.location="puntos";});
      </script>';
    } else {
      echo '<script>
        Swal.fire({icon:"error", title:"No se pudo registrar"})
        .then(()=>{window.location="puntos";});
      </script>';
    }
  }

  /* =========================
     EDITAR (nota, sucursal, ped_id, tipo y puntos)
     ========================= */
  static public function ctrEditarMovimiento() {
    if (!isset($_POST["pmv_id"])) return;

    $pmv_id     = (int)$_POST["pmv_id"];
    $cli_id     = (int)$_POST["editar_cli_id"];
    $suc_id     = (int)$_POST["editar_suc_id"];
    $ped_id     = (isset($_POST["editar_ped_id"]) && $_POST["editar_ped_id"]!=="") ? (int)$_POST["editar_ped_id"] : null;
    $pmv_tipo   = $_POST["editar_pmv_tipo"];
    $pmv_puntos = (int)$_POST["editar_pmv_puntos"];
    $pmv_nota   = isset($_POST["editar_pmv_nota"]) && $_POST["editar_pmv_nota"]!=="" ? trim($_POST["editar_pmv_nota"]) : null;

    // Para edit, recalculamos el saldo con base al histórico completo
    $resp = ModelPuntos::mdlEditarMovimiento("puntos_movimientos", [
      "pmv_id"     => $pmv_id,
      "cli_id"     => $cli_id,
      "suc_id"     => $suc_id,
      "ped_id"     => $ped_id,
      "pmv_tipo"   => $pmv_tipo,
      "pmv_puntos" => $pmv_puntos,
      "pmv_nota"   => $pmv_nota
    ]);

    if ($resp == "ok") {
      // (OPT) normalizamos todos los saldos del cliente tras el cambio
      ModelPuntos::mdlRecalcularSaldosCliente("puntos_movimientos", $cli_id);
      echo '<script>
        Swal.fire({icon:"success", title:"¡Movimiento editado!"})
        .then(()=>{window.location="puntos";});
      </script>';
    } else {
      echo '<script>
        Swal.fire({icon:"error", title:"No se pudo editar"})
        .then(()=>{window.location="puntos";});
      </script>';
    }
  }

  /* =========================
     ELIMINAR (GET)
     ========================= */
  static public function ctrEliminarMovimiento() {
    if (isset($_GET["idMovimiento"])) {
      $id  = (int)$_GET["idMovimiento"];
      // obtenemos cliente para recalcular saldos después
      $mov = self::ctrMostrarMovimientos("pmv_id", $id);
      $cli = $mov ? (int)$mov["cli_id"] : null;

      $resp = ModelPuntos::mdlEliminarMovimiento("puntos_movimientos", $id);

      if ($resp == "ok") {
        if ($cli) ModelPuntos::mdlRecalcularSaldosCliente("puntos_movimientos", $cli); // (OPT)
        echo '<script>
          Swal.fire({icon:"success", title:"¡Movimiento eliminado!"})
          .then(()=>{window.location="puntos";});
        </script>';
      } else {
        echo '<script>
          Swal.fire({icon:"error", title:"No se pudo eliminar"})
          .then(()=>{window.location="puntos";});
        </script>';
      }
    }
  }
}
