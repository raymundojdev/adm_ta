<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="" style="font-size: 25px;"><i class="fas fa-user"></i> Modulo Usuarios</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body ">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-success d-md-block mb-3" data-bs-toggle="modal"
                                    data-bs-target="#modalAgregarUsuario"><i class="fas fa-user-plus"></i> Agregar
                                    Usuario</button>
                                <button type="button" class="btn btn-success d-md-block mb-3" data-bs-toggle="modal"
                                    data-bs-target="#modalCargarCSV"><i class="fas fa-user-plus"></i> CSV</button>
                            </div>




                            <table id="datatable-buttons "
                                class="table table-striped table-bordered dt-responsive nowrap example2 tablas"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Perfil</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $item = null;
                                    $valor = null;

                                    $usuarios = ControllerUsuarios::ctrMostrarUsuarios($item, $valor);

                                    foreach ($usuarios as $key => $value) {
                                        echo '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $value["nombre"] . '</td>
                                            <td>' . $value["usuario"] . '</td>
                                            <td>' . $value["perfil"] . '</td>
                                            <td>' . $value["fecha"] . '</td>
                                            <td>
                                                <div class="btn-group">                                                   
                                                        <button class="btn btn-primary btn-sm btnEditarUsuario" idUsuario="' . $value["id"] . '" data-bs-toggle="modal" data-bs-target="#modalEditarUsuarios"><i class="fas fa-pencil-alt"></i> Editar</button>
                                                        <button class="btn btn-danger btn-sm btnEliminarUsuario" idUsuario="' . $value["id"] . '"><i class="fas fa-trash-alt"></i> Eliminar</button>                                           
                                                </div>
                                            </td>                                    
                                            ';
                                    }
                                    ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>


<div class="modal fade " id="modalAgregarUsuario" tabindex="-1" aria-labelledby="myLargeModalLabel"
    style="display: none;" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-user-plus"></i> Agregar Usuario</h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label"><i class="fas fa-user"></i> Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword"><i
                                    class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="perfil" class="form-label"><i class="fas fa-user-tag"></i> Perfil</label>
                        <select class="form-control" id="perfil" name="perfil" required>
                            <option value="Administrador">Administrador</option>
                            <option value="Jefe de cuartel">Jefe de cuartel</option>
                            <option value="Jefe de manzana">Jefe de manzana</option>
                        </select>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    <?php
                    $crearUsuario = new ControllerUsuarios();
                    $crearUsuario->ctrGuardarUsuarios();
                    ?>

                </form>



            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<!-- MODAL: Subir CSV -->
<div class="modal fade" id="modalCargarCSV" tabindex="-1" aria-labelledby="modalCSVLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-csv"></i> Importar usuarios desde CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formCSV" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Arrastra tu archivo .csv aquí o haz clic</label>
                        <div id="dropzoneCSV" class="border border-primary border-2 rounded p-4 text-center bg-light"
                            style="cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-primary"></i>
                            <p id="fileLabel">Ningún archivo seleccionado</p>
                            <input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" style="display: none;"
                                required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Subir CSV</button>
                    </div>
                    <?php
                    $guardarCSV = new ControllerUsuarios();
                    $guardarCSV->ctrGuardarUsuariosCsv();
                    ?>
                </form>
            </div>

        </div>
    </div>
</div>



<div class="modal fade " id="modalEditarUsuarios" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-user-edit"></i> Editar Usuario </h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="mb-3">

                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="editar_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label"><i class="fas fa-user"></i> Usuario</label>
                        <input type="text" class="form-control" id="editar_usuario" name="editar_usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="editar_password" name="editar_password"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="perfil" class="form-label"><i class="fas fa-user-tag"></i> Perfil</label>
                        <select class="form-control" name="editar_perfil" required>
                            <option id="editar_perfil"></option>
                            <option value="Administrador">Administrador</option>
                            <option value="Jefe de cuartel">Jefe de cuartel</option>
                            <option value="Jefe de manzana">Jefe de manzana</option>
                        </select>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                    <?php
                    $editarUsuario = new ControllerUsuarios();
                    $editarUsuario->ctrEditarUsuarios();
                    ?>

                </form>



            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php

$borrarUsuario = new ControllerUsuarios();
$borrarUsuario->ctrEliminarUsuario();

?>