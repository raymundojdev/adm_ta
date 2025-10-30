<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body text-center">
                    <h4><i class="fas fa-layer-group"></i> Módulo Categorías</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCSV">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>

                    <table class="table table-bordered table-striped  example2 tablas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Activa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $categorias = ControllerCategorias::ctrMostrarCategorias(null, null);
                            foreach ($categorias as $k => $c) {
                                echo "<tr>
                    <td>" . ($k + 1) . "</td>
                    <td>{$c["cat_nombre"]}</td>
                    <td>" . ($c["cat_activa"] ? "Sí" : "No") . "</td>
                    <td>
                      <button class='btn btn-primary btnEditarCategoria' idCategoria='{$c["cat_id"]}'>
                        Editar
                      </button>
                      <button class='btn btn-danger btnEliminarCategoria' idCategoria='{$c["cat_id"]}'>
                        Eliminar
                      </button>
                    </td>
                  </tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-user-plus"></i> Agregar categoria</h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="cat_nombre" class="form-control mb-3" placeholder="Nombre" required>
                    <select name="cat_activa" class="form-control">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
                <?php $guardar = new ControllerCategorias();
                $guardar->ctrGuardarCategorias(); ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-user-plus"></i> Editar categoria</h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" id="cat_id" name="cat_id">
                    <input type="text" id="editar_nombre" name="editar_nombre" class="form-control mb-3" required>
                    <select id="editar_activa" name="editar_activa" class="form-control">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                </div>
                <?php $editar = new ControllerCategorias();
                $editar->ctrEditarCategorias(); ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CSV -->
<div class="modal fade" id="modalCSV" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="archivo_csv" accept=".csv" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-success" type="submit">Importar</button>
                </div>
                <?php $csv = new ControllerCategorias();
                $csv->ctrGuardarCategoriasCsv(); ?>
            </form>
        </div>
    </div>
</div>

<?php $eliminar = new ControllerCategorias();
$eliminar->ctrEliminarCategoria(); ?>