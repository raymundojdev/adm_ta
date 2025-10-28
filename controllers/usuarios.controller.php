<?php

class ControllerUsuarios
{

    static public function ctrIngresoUsuario()
    {

        if (isset($_POST["usuario_login"])) {

            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuario_login"]) && preg_match('/^[a-zA-Z0-9]+$/', $_POST["password_login"])) {

                $tabla = "usuarios";

                $encryptar = crypt($_POST["password_login"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $item = "usuario";
                $valor = $_POST["usuario_login"];

                $respuesta = ModelUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

                if ($respuesta["usuario"] == $_POST["usuario_login"] && $respuesta["password"]) {

                    $_SESSION["iniciarSesion"] = "ok";
                    $_SESSION["id"] = $respuesta["id"];
                    $_SESSION["nombre"] = $respuesta["nombre"];
                    $_SESSION["usuario"] = $respuesta["usuario"];
                    $_SESSION["perfil"] = $respuesta["perfil"];

                    echo '<script>
                    window.location = "dashboard";
                    </script>';
                } else {
                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "¡El usuario no existe!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "login";
                            }
                        });
                    </script>';
                }
            }
        }
    }

    static public function ctrMostrarUsuarios($item, $valor)
    {

        $tabla = "usuarios";

        $respuesta = ModelUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

        return $respuesta;
    }

    static public function ctrGuardarUsuarios()
    {
        if (isset($_POST["usuario"])) {

            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nombre"]) && 
               preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuario"]) && 
               preg_match('/^[a-zA-Z0-9]+$/', $_POST["password"])) {

                            $tabla = "usuarios";
    
                            $encryptar = crypt($_POST["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
    
                            $datos = array(
                                "nombre" => $_POST["nombre"],
                                "usuario" => $_POST["usuario"],
                                "password" => $encryptar,
                                "perfil" => $_POST["perfil"]
                            );
    
                            $respuesta = ModelUsuarios::mdlGuardarUsuarios($tabla, $datos);
    
                            if ($respuesta == "ok") {
    
                                echo '<script>
                               Swal.fire({
                                title:"¡Usuario agregado con exito!",                
                                icon: "success",                                
                                confirmButtonColor: "#3085d6",               
                                confirmButtonText: "Aceptar"
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "usuarios";
                                }
                                });
    
                                </script>';
                            } else {
                                echo '<script>
                                Swal.fire({
                                          icon: "error",
                                          title: "El usuario no pudo ser guardado",
                                          showConfirmButton: true,
                                          confirmButtonText: "Cerrar"
                                          }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location = "usuarios";
                                                    }
                                                });  
                                </script>';
                            }

            }
        }
                    
        }

        static public function ctrGuardarUsuariosCsv(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
                $archivo = $_FILES['archivo_csv']['tmp_name'];

                $tabla = "usuarios";
            
                if (($handle = fopen($archivo, "r")) !== FALSE) {
                    $linea = 0;
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($linea === 0) { $linea++; continue; } // saltar encabezado
            
                        $nombre = trim($data[0]);
                        $usuario = trim($data[1]);
                        $password = password_hash(trim($data[2]), PASSWORD_DEFAULT);
                        $perfil = trim($data[3]);
            
                        $datos = [
                            "nombre" => $nombre,
                            "usuario" => $usuario,
                            "password" => $password,
                            "perfil" => $perfil
                        ];
            
                        $respuesta = ModelUsuarios::mdlGuardarUsuarios( $tabla, $datos);


                       
                    }

                    if ($respuesta == "ok") {
    
                        echo '<script>
                       Swal.fire({
                        title:"¡Usuario agregado con exito!",                
                        icon: "success",                                
                        confirmButtonColor: "#3085d6",               
                        confirmButtonText: "Aceptar"
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "usuarios";
                        }
                        });
        
                        </script>';
                    } else {
                        echo '<script>
                        Swal.fire({
                                  icon: "error",
                                  title: "El usuario no pudo ser guardado",
                                  showConfirmButton: true,
                                  confirmButtonText: "Cerrar"
                                  }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location = "usuarios";
                                            }
                                        });  
                        </script>';
                    }
            
                    fclose($handle);
                  
                } 
            } 
        }
    
        
    static public function ctrEditarUsuarios(){
        if(isset($_POST["editar_usuario"])){
            if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editar_nombre"]) && 
               preg_match('/^[a-zA-Z0-9]+$/', $_POST["editar_usuario"])
             ) {

                $tabla = "usuarios";
              

                $datos = array(
                    "id" => $_POST["id"],
                    "nombre" => $_POST["editar_nombre"],
                    "usuario" => $_POST["editar_usuario"],
                    "password" => $_POST["editar_password"],
                    "perfil" => $_POST["editar_perfil"]
                );

                $respuesta = ModelUsuarios::mdlEditarUsuarios($tabla, $datos);

                if ($respuesta == "ok") {

                    echo '<script>
                   Swal.fire({
                    title:"¡Usuario editado con exito!",                
                    icon: "success",                                
                    confirmButtonColor: "#3085d6",               
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "usuarios";
                    }
                    });

                    </script>';
                } else {
                    echo '<script>
                    Swal.fire({
                              icon: "error",
                              title: "El usuario no pudo ser editado",
                              showConfirmButton: true,
                              confirmButtonText: "Cerrar"
                              }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = "usuarios";
                                        }
                                    });  
                    </script>';
                }

            }
        }   
    }

    static public function ctrEliminarUsuario()
    {
        if (isset($_GET["idUsuario"])) {

            $tabla = "usuarios";

            $datos = $_GET["idUsuario"];

            $respuesta = ModelUsuarios::mdlEliminarUsuario($tabla, $datos);

            if ($respuesta == "ok") {

                echo '<script>
                Swal.fire({
                    title:"¡Usuario eliminado con exito!",                
                    icon: "success",                                
                    confirmButtonColor: "#3085d6",               
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "usuarios";
                    }
                    });

                    </script>';
            }
        }
  
    }


}