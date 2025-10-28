<?php
/* ============================================
   controllers/productos.controller.php
   ============================================ */
class ControllerProductos {

    /* Mostrar 1 o todos (para modal edición y utilidades) */
    static public function ctrMostrarProductos($item, $valor){
        $tabla = "productos";
        return ModelProductos::mdlMostrarProductos($tabla, $item, $valor);
    }

    /* Catálogos para selects de la vista */
    static public function ctrUnidades(){
        return ModelProductos::mdlUnidades();
    }

    static public function ctrProveedoresActivos(){
        return ModelProductos::mdlProveedoresActivos();
    }
}
