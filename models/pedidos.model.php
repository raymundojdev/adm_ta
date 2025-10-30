<?php
require_once "conexion.php";

class ModelPedidos
{

    /* Lista / uno con joins */
    static public function mdlMostrarPedidos($tabla, $item, $valor)
    {
        $pdo = conexiondb::conectar();
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $sqlBase = "SELECT p.*,
                           c.cli_nombre,
                           s.suc_nombre
                    FROM $tabla p
                    LEFT JOIN clientes   c ON c.cli_id = p.cli_id
                    JOIN  sucursales s ON s.suc_id = p.suc_id";

        if ($item != null) {
            $permitidos = ["ped_id", "ped_folio"]; // whitelist
            if (!in_array($item, $permitidos, true)) return [];

            $sql = $sqlBase . " WHERE p.$item = :val LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":val", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch() ?: [];
        } else {
            $sql = $sqlBase . " ORDER BY p.ped_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll() ?: [];
        }
    }

    /* Mostrar detalles por ped_id */
    static public function mdlMostrarDetalles($tablaDetalles, $ped_id)
    {
        $sql = "SELECT d.*, pr.pro_nombre
                FROM $tablaDetalles d
                JOIN productos pr ON pr.pro_id = d.pro_id
                WHERE d.ped_id = :ped_id
                ORDER BY d.pde_id ASC";
        $stmt = conexiondb::conectar()->prepare($sql);
        $stmt->bindParam(":ped_id", $ped_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /* Guardar encabezado + detalles (transacción) */
    static public function mdlGuardarPedidoConDetalles($tabla, $tablaDetalles, $encabezado, $detalles)
    {
        $pdo = conexiondb::conectar();
        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO $tabla
                    (cli_id, suc_id, ped_folio, ped_tipo, ped_estado, ped_total, ped_puntos_generados)
                    VALUES
                    (:cli_id, :suc_id, :ped_folio, :ped_tipo, :ped_estado, :ped_total, :ped_puntos_generados)";
            $stmt = $pdo->prepare($sql);

            if ($encabezado["cli_id"] === null) $stmt->bindValue(":cli_id", null, PDO::PARAM_NULL);
            else $stmt->bindParam(":cli_id", $encabezado["cli_id"], PDO::PARAM_INT);

            $stmt->bindParam(":suc_id", $encabezado["suc_id"], PDO::PARAM_INT);
            $stmt->bindParam(":ped_folio", $encabezado["ped_folio"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_tipo", $encabezado["ped_tipo"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_estado", $encabezado["ped_estado"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_total", $encabezado["ped_total"]);
            $stmt->bindParam(":ped_puntos_generados", $encabezado["ped_puntos_generados"], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $pdo->rollBack();
                return ["status" => "error", "error" => "insert encabezado"];
            }

            $ped_id = (int)$pdo->lastInsertId();

            $sqlD = "INSERT INTO $tablaDetalles
                     (ped_id, pro_id, pde_cantidad, pde_precio, pde_subtotal)
                     VALUES
                     (:ped_id, :pro_id, :pde_cantidad, :pde_precio, :pde_subtotal)";
            $stmtD = $pdo->prepare($sqlD);

            foreach ($detalles as $d) {
                $stmtD->bindParam(":ped_id", $ped_id, PDO::PARAM_INT);
                $stmtD->bindParam(":pro_id", $d["pro_id"], PDO::PARAM_INT);
                $stmtD->bindParam(":pde_cantidad", $d["pde_cantidad"], PDO::PARAM_INT);
                $stmtD->bindParam(":pde_precio", $d["pde_precio"]);
                $stmtD->bindParam(":pde_subtotal", $d["pde_subtotal"]);
                if (!$stmtD->execute()) {
                    $pdo->rollBack();
                    return ["status" => "error", "error" => "insert detalle"];
                }
            }

            $pdo->commit();
            return ["status" => "ok", "ped_id" => $ped_id];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }

    /* Editar encabezado + reemplazar detalles (transacción) */
    static public function mdlEditarPedidoConDetalles($tabla, $tablaDetalles, $encabezado, $detalles)
    {
        $pdo = conexiondb::conectar();
        try {
            $pdo->beginTransaction();

            $sql = "UPDATE $tabla SET
                      cli_id = :cli_id,
                      suc_id = :suc_id,
                      ped_folio = :ped_folio,
                      ped_tipo = :ped_tipo,
                      ped_estado = :ped_estado,
                      ped_total = :ped_total,
                      ped_puntos_generados = :ped_puntos_generados
                    WHERE ped_id = :ped_id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":ped_id", $encabezado["ped_id"], PDO::PARAM_INT);

            if (!array_key_exists("ped_total", $encabezado)) {
                // Si no enviaste detalles nuevos, mantener total/puntos actuales
                $current = self::mdlMostrarPedidos($tabla, "ped_id", $encabezado["ped_id"]);
                $encabezado["ped_total"] = $current ? (float)$current["ped_total"] : 0;
                $encabezado["ped_puntos_generados"] = $current ? (int)$current["ped_puntos_generados"] : 0;
            }

            if ($encabezado["cli_id"] === null) $stmt->bindValue(":cli_id", null, PDO::PARAM_NULL);
            else $stmt->bindParam(":cli_id", $encabezado["cli_id"], PDO::PARAM_INT);

            $stmt->bindParam(":suc_id", $encabezado["suc_id"], PDO::PARAM_INT);
            $stmt->bindParam(":ped_folio", $encabezado["ped_folio"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_tipo", $encabezado["ped_tipo"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_estado", $encabezado["ped_estado"], PDO::PARAM_STR);
            $stmt->bindParam(":ped_total", $encabezado["ped_total"]);
            $stmt->bindParam(":ped_puntos_generados", $encabezado["ped_puntos_generados"], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $pdo->rollBack();
                return ["status" => "error", "error" => "update encabezado"];
            }

            if (!empty($detalles)) {
                // Borrar existentes
                $del = $pdo->prepare("DELETE FROM $tablaDetalles WHERE ped_id=:ped_id");
                $del->bindParam(":ped_id", $encabezado["ped_id"], PDO::PARAM_INT);
                if (!$del->execute()) {
                    $pdo->rollBack();
                    return ["status" => "error", "error" => "delete detalles"];
                }

                // Insertar nuevos
                $ins = $pdo->prepare("INSERT INTO $tablaDetalles (ped_id, pro_id, pde_cantidad, pde_precio, pde_subtotal)
                                      VALUES (:ped_id,:pro_id,:pde_cantidad,:pde_precio,:pde_subtotal)");
                foreach ($detalles as $d) {
                    $ins->bindParam(":ped_id", $encabezado["ped_id"], PDO::PARAM_INT);
                    $ins->bindParam(":pro_id", $d["pro_id"], PDO::PARAM_INT);
                    $ins->bindParam(":pde_cantidad", $d["pde_cantidad"], PDO::PARAM_INT);
                    $ins->bindParam(":pde_precio", $d["pde_precio"]);
                    $ins->bindParam(":pde_subtotal", $d["pde_subtotal"]);
                    if (!$ins->execute()) {
                        $pdo->rollBack();
                        return ["status" => "error", "error" => "insert detalle nuevo"];
                    }
                }
            }

            $pdo->commit();
            return ["status" => "ok"];
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }

    /* Eliminar pedido (y cascada detalles) */
    static public function mdlEliminarPedido($tabla, $tablaDetalles, $id)
    {
        // Por FK CASCADE en detalles, basta borrar encabezado
        $stmt = conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE ped_id=:ped_id");
        $stmt->bindParam(":ped_id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "ok" : "error";
    }

    /* Sumar puntos a cliente */
    static public function mdlSumarPuntosCliente($tablaClientes, $cli_id, $puntos)
    {
        $stmt = conexiondb::conectar()->prepare(
            "UPDATE $tablaClientes SET cli_puntos = cli_puntos + :p WHERE cli_id=:cli"
        );
        $stmt->bindParam(":p", $puntos, PDO::PARAM_INT);
        $stmt->bindParam(":cli", $cli_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}