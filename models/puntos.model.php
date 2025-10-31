<?php
// models/puntos.model.php
require_once "conexion.php";

class ModelPuntos {

  static public function mdlMostrarMovimientos($tabla, $item, $valor) {
    $pdo = conexiondb::conectar();
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // (OPT)

    $sqlBase = "SELECT pm.*, c.cli_nombre, s.suc_nombre
                FROM $tabla pm
                JOIN clientes c   ON c.cli_id = pm.cli_id
                JOIN sucursales s ON s.suc_id = pm.suc_id";

    if ($item != null) {
      $permitidos = ["pmv_id"]; // (OPT) whitelist de columnas filtrables
      if (!in_array($item, $permitidos, true)) return [];

      $sql = $sqlBase." WHERE pm.$item = :val LIMIT 1";
      $st  = $pdo->prepare($sql);
      $st->bindParam(":val", $valor, PDO::PARAM_STR);
      $st->execute();
      return $st->fetch() ?: [];
    } else {
      $sql = $sqlBase." ORDER BY pm.pmv_creado_en DESC, pm.pmv_id DESC";
      $st  = $pdo->prepare($sql);
      $st->execute();
      return $st->fetchAll() ?: [];
    }
  }

  static public function mdlSaldoCliente($tabla, $cli_id) {
    $pdo = conexiondb::conectar();
    $st = $pdo->prepare("SELECT COALESCE(SUM(pmv_puntos),0) AS saldo FROM $tabla WHERE cli_id=:cli");
    $st->bindParam(":cli", $cli_id, PDO::PARAM_INT);
    $st->execute();
    $r = $st->fetch(PDO::FETCH_ASSOC);
    return ["saldo" => (int)($r["saldo"] ?? 0)];
  }

  static public function mdlGuardarMovimiento($tabla, $d) {
    $sql = "INSERT INTO $tabla (cli_id, suc_id, ped_id, pmv_tipo, pmv_puntos, pmv_saldo, pmv_nota)
            VALUES (:cli_id, :suc_id, :ped_id, :pmv_tipo, :pmv_puntos, :pmv_saldo, :pmv_nota)";
    $st = conexiondb::conectar()->prepare($sql);

    $st->bindParam(":cli_id", $d["cli_id"], PDO::PARAM_INT);
    $st->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
    if ($d["ped_id"]===null) $st->bindValue(":ped_id", null, PDO::PARAM_NULL);
    else $st->bindParam(":ped_id", $d["ped_id"], PDO::PARAM_INT);
    $st->bindParam(":pmv_tipo", $d["pmv_tipo"], PDO::PARAM_STR);
    $st->bindParam(":pmv_puntos", $d["pmv_puntos"], PDO::PARAM_INT);
    $st->bindParam(":pmv_saldo", $d["pmv_saldo"], PDO::PARAM_INT);
    if ($d["pmv_nota"]===null) $st->bindValue(":pmv_nota", null, PDO::PARAM_NULL);
    else $st->bindParam(":pmv_nota", $d["pmv_nota"], PDO::PARAM_STR);

    return $st->execute() ? "ok" : "error";
  }

  static public function mdlEditarMovimiento($tabla, $d) {
    $sql = "UPDATE $tabla SET
              cli_id=:cli_id, suc_id=:suc_id, ped_id=:ped_id,
              pmv_tipo=:pmv_tipo, pmv_puntos=:pmv_puntos, pmv_nota=:pmv_nota
            WHERE pmv_id=:pmv_id";
    $st = conexiondb::conectar()->prepare($sql);

    $st->bindParam(":pmv_id", $d["pmv_id"], PDO::PARAM_INT);
    $st->bindParam(":cli_id", $d["cli_id"], PDO::PARAM_INT);
    $st->bindParam(":suc_id", $d["suc_id"], PDO::PARAM_INT);
    if ($d["ped_id"]===null) $st->bindValue(":ped_id", null, PDO::PARAM_NULL);
    else $st->bindParam(":ped_id", $d["ped_id"], PDO::PARAM_INT);
    $st->bindParam(":pmv_tipo", $d["pmv_tipo"], PDO::PARAM_STR);
    $st->bindParam(":pmv_puntos", $d["pmv_puntos"], PDO::PARAM_INT);
    if ($d["pmv_nota"]===null) $st->bindValue(":pmv_nota", null, PDO::PARAM_NULL);
    else $st->bindParam(":pmv_nota", $d["pmv_nota"], PDO::PARAM_STR);

    return $st->execute() ? "ok" : "error";
  }

  static public function mdlEliminarMovimiento($tabla, $id) {
    $st = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE pmv_id=:id");
    $st->bindParam(":id", $id, PDO::PARAM_INT);
    return $st->execute() ? "ok" : "error";
  }

  /* (OPT) Recalcula saldos denormalizados por orden cronológico para un cliente */
  static public function mdlRecalcularSaldosCliente($tabla, $cli_id) {
    $pdo = conexiondb::conectar();
    $pdo->beginTransaction();
    try {
      $st = $pdo->prepare("SELECT pmv_id, pmv_puntos FROM $tabla WHERE cli_id=:cli ORDER BY pmv_creado_en ASC, pmv_id ASC");
      $st->bindParam(":cli", $cli_id, PDO::PARAM_INT);
      $st->execute();
      $rows = $st->fetchAll(PDO::FETCH_ASSOC);

      $saldo = 0;
      $up = $pdo->prepare("UPDATE $tabla SET pmv_saldo=:saldo WHERE pmv_id=:id");
      foreach ($rows as $r) {
        $saldo += (int)$r["pmv_puntos"];
        $up->bindParam(":saldo", $saldo, PDO::PARAM_INT);
        $up->bindParam(":id", $r["pmv_id"], PDO::PARAM_INT);
        $up->execute();
      }
      $pdo->commit();
      return "ok";
    } catch (Exception $e) {
      $pdo->rollBack();
      return "error";
    }
  }
}
