<?php

class ControllerSucursales
{

    /* Mostrar Sucursales */
    static public function ctrMostrarSucursales($item, $valor)
    {
        $tabla = "sucursales";
        return ModelSucursales::mdlMostrarSucursales($tabla, $item, $valor);
    }

    /* Guardar Sucursal */
    static public function ctrGuardarSucursales()
    {
        if (isset($_POST["suc_nombre"])) {
            $tabla = "sucursales";
            $datos = array(
                "suc_nombre"    => trim($_POST["suc_nombre"]),
                "suc_direccion" => trim($_POST["suc_direccion"]),
                "suc_activa"    => (int)$_POST["suc_activa"]
            );

            $respuesta = ModelSucursales::mdlGuardarSucursales($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    Swal.fire({
                        title:"¡Sucursal agregada con éxito!",
                        icon:"success",
                        confirmButtonColor:"#3085d6",
                        confirmButtonText:"Aceptar"
                    }).then((r)=>{ if(r.isConfirmed){ window.location="sucursales"; }});
                </script>';
            }
        }
    }

    /* Editar Sucursal */
    static public function ctrEditarSucursales()
    {
        if (isset($_POST["editar_nombre"])) {
            $tabla = "sucursales";
            $datos = array(
                "suc_id"        => (int)$_POST["suc_id"],
                "suc_nombre"    => trim($_POST["editar_nombre"]),
                "suc_direccion" => trim($_POST["editar_direccion"]),
                "suc_activa"    => (int)$_POST["editar_activa"]
            );

            $respuesta = ModelSucursales::mdlEditarSucursales($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    Swal.fire({
                        title:"¡Sucursal editada con éxito!",
                        icon:"success",
                        confirmButtonColor:"#3085d6",
                        confirmButtonText:"Aceptar"
                    }).then((r)=>{ if(r.isConfirmed){ window.location="sucursales"; }});
                </script>';
            }
        }
    }

    /* Eliminar Sucursal */
    static public function ctrEliminarSucursal()
    {
        if (isset($_GET["idSucursal"])) {
            $tabla = "sucursales";
            $datos = (int)$_GET["idSucursal"];

            $respuesta = ModelSucursales::mdlEliminarSucursal($tabla, $datos);

            if ($respuesta == "ok") {
                echo '<script>
                    Swal.fire({
                        title:"¡Sucursal eliminada con éxito!",
                        icon:"success",
                        confirmButtonColor:"#3085d6",
                        confirmButtonText:"Aceptar"
                    }).then((r)=>{ if(r.isConfirmed){ window.location="sucursales"; }});
                </script>';
            }
        }
    }
}