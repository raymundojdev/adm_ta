<?php
/* ==========================================================
   controllers/productos.controller.php  (REEMPLAZA COMPLETO)
   ========================================================== */
class ControllerProductos
{

    static public function ctrMostrarProductos($item, $valor)
    {
        $tabla = "productos";
        return ModelProductos::mdlMostrarProductos($tabla, $item, $valor);
    }

    static public function ctrUnidades()
    {
        return ModelProductos::mdlUnidades();
    }

    static public function ctrProveedoresActivos()
    {
        return ModelProductos::mdlProveedoresActivos();
    }
}
