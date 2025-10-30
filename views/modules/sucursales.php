<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div style="font-size:25px;"><i class="fas fa-store"></i> Módulo Sucursales</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                                    data-bs-target="#modalAgregarSucursal">
                                    <i class="fas fa-plus"></i> Agregar Sucursal
                                </button>
                            </div>

                            <table class="table table-striped table-bordered dt-responsive nowrap example2 tablas">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Activa</th>
                                        <th>Creado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $item = null;
                                    $valor = null;
                                    $sucursales = ControllerSucursales::ctrMostrarSucursales($item, $valor);

                                    foreach ($sucursales as $key => $value) {
                                        echo '<tr>
                      <td>' . ($key + 1) . '</td>
                      <td>' . $value["suc_nombre"] . '</td>
                      <td>' . $value["suc_direccion"] . '</td>
                      <td>' . ($value["suc_activa"] ? "Sí" : "No") . '</td>
                      <td>' . $value["suc_creado_en"] . '</td>
                      <td>
                        <div class="btn-group">
                          <button class="btn btn-primary btn-sm btnEditarSucursal" idSucursal="' . $value["suc_id"] . '" data-bs-toggle="modal" data-bs-target="#modalEditarSucursal">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </button>
                          <button class="btn btn-danger btn-sm btnEliminarSucursal" idSucursal="' . $value["suc_id"] . '">
                            <i class="fas fa-trash-alt"></i> Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarSucursal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Agregar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="suc_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="suc_direccion">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activa</label>
                        <select class="form-control" name="suc_activa" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                $guardar = new ControllerSucursales();
                $guardar->ctrGuardarSucursales();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarSucursal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="suc_id" name="suc_id">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="editar_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editar_direccion" name="editar_direccion">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Activa</label>
                        <select class="form-control" id="editar_activa" name="editar_activa" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
                <?php
                $editar = new ControllerSucursales();
                $editar->ctrEditarSucursales();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$eliminar = new ControllerSucursales();
$eliminar->ctrEliminarSucursal();
?>