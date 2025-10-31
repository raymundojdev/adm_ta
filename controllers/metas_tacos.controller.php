<?php
class ControllerMetasTacos
{

    /* Listar / uno */
    static public function ctrMostrarMetas($item, $valor)
    {
        $tabla = "metas_tacos";
        return ModelMetasTacos::mdlMostrarMetas($tabla, $item, $valor);
    }

    /* Guardar */
    static public function ctrGuardarMeta()
    {
        if (!isset($_POST["suc_id"])) return;

        // (OPT) Saneamos y seteamos nulls opcionales
        $datos = [
            "suc_id"       => (int)$_POST["suc_id"],
            "cat_id"       => (isset($_POST["cat_id"]) && $_POST["cat_id"] !== "") ? (int)$_POST["cat_id"] : null,
            "pro_id"       => (isset($_POST["pro_id"]) && $_POST["pro_id"] !== "") ? (int)$_POST["pro_id"] : null,
            "met_fecha"    => $_POST["met_fecha"],
            "met_cantidad" => (int)$_POST["met_cantidad"],
            "met_nota"     => (isset($_POST["met_nota"]) && $_POST["met_nota"] !== "") ? trim($_POST["met_nota"]) : null,
            "met_activa"   => (int)$_POST["met_activa"]
        ];

        $resp = ModelMetasTacos::mdlGuardarMeta("metas_tacos", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Meta creada!"})
        .then(()=>{window.location="metas_tacos"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo crear la meta"})
        .then(()=>{window.location="metas_tacos"});
      </script>';
        }
    }

    /* Editar */
    static public function ctrEditarMeta()
    {
        if (!isset($_POST["met_id"])) return;

        $datos = [
            "met_id"       => (int)$_POST["met_id"],
            "suc_id"       => (int)$_POST["editar_suc_id"],
            "cat_id"       => (isset($_POST["editar_cat_id"]) && $_POST["editar_cat_id"] !== "") ? (int)$_POST["editar_cat_id"] : null,
            "pro_id"       => (isset($_POST["editar_pro_id"]) && $_POST["editar_pro_id"] !== "") ? (int)$_POST["editar_pro_id"] : null,
            "met_fecha"    => $_POST["editar_met_fecha"],
            "met_cantidad" => (int)$_POST["editar_met_cantidad"],
            "met_nota"     => (isset($_POST["editar_met_nota"]) && $_POST["editar_met_nota"] !== "") ? trim($_POST["editar_met_nota"]) : null,
            "met_activa"   => (int)$_POST["editar_met_activa"]
        ];

        $resp = ModelMetasTacos::mdlEditarMeta("metas_tacos", $datos);

        if ($resp == "ok") {
            echo '<script>
        Swal.fire({icon:"success", title:"¡Meta actualizada!"})
        .then(()=>{window.location="metas_tacos"});
      </script>';
        } else {
            echo '<script>
        Swal.fire({icon:"error", title:"No se pudo actualizar"})
        .then(()=>{window.location="metas_tacos"});
      </script>';
        }
    }

    /* Eliminar por GET (patrón que usas) */
    static public function ctrEliminarMeta()
    {
        if (isset($_GET["idMeta"])) {
            $id = (int)$_GET["idMeta"];
            $resp = ModelMetasTacos::mdlEliminarMeta("metas_tacos", $id);

            if ($resp == "ok") {
                echo '<script>
          Swal.fire({icon:"success", title:"¡Meta eliminada!"})
          .then(()=>{window.location="metas_tacos"});
        </script>';
            } else {
                echo '<script>
          Swal.fire({icon:"error", title:"No se pudo eliminar"})
          .then(()=>{window.location="metas_tacos"});
        </script>';
            }
        }
    }

    /* (OPCIONAL) Progreso vendido del día: suma pde_cantidad con filtros */
    static public function ctrProgresoDia($suc_id, $fecha, $cat_id = null, $pro_id = null)
    {
        return ModelMetasTacos::mdlProgresoDia(
            "pedidos",
            "pedidos_detalles",
            "productos",
            (int)$suc_id,
            $fecha,
            $cat_id,
            $pro_id
        );
    }
}