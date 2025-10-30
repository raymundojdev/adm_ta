<?php
// views/modules/clientes.php
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado moderno -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100"
                        style="background: radial-gradient(1200px 400px at 0% -10%, rgba(13,110,253,.12), transparent 60%); pointer-events:none;">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <i class="fas fa-user-stars text-primary me-2"></i>
                                Clientes & Puntos
                            </h4>
                            <small class="text-muted">Administra tus clientes y su saldo de puntos por compras.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span
                                class="badge rounded-pill bg-success-subtle text-success border border-success">Activos</span>
                            <span
                                class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary">Inactivos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones + Tabla -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="input-group" style="max-width: 420px;">
                            <!-- <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control"
                                placeholder="Buscar cliente (usa el filtro de tu DataTable)"> -->
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalAgregarCliente">
                                <i class="fas fa-user-plus me-1"></i> Nuevo cliente
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalCargarCSV">
                                <i class="fas fa-file-csv me-1"></i> Importar CSV
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-hover example2 tablas" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th class="text-end">Puntos</th>
                                    <th>Estado</th>
                                    <th>Creado</th>
                                    <th style="width:180px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $clientes = ControllerClientes::ctrMostrarClientes(null, null);
                                if (!empty($clientes)) {
                                    foreach ($clientes as $k => $c) {
                                        $badge = ((int)$c["cli_activo"] === 1)
                                            ? '<span class="badge bg-success">Activo</span>'
                                            : '<span class="badge bg-secondary">Inactivo</span>';
                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($c["cli_nombre"] ?? "") . '</td>
                        <td>' . htmlspecialchars($c["cli_telefono"] ?? "") . '</td>
                        <td>' . htmlspecialchars($c["cli_email"] ?? "") . '</td>
                        <td class="text-end">' . number_format((int)($c["cli_puntos"] ?? 0)) . '</td>
                        <td>' . $badge . '</td>
                        <td>' . htmlspecialchars($c["cli_creado_en"] ?? "") . '</td>
                        <td>
                          <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary btn-sm btnEditarCliente" idCliente="' . ($c["cli_id"] ?? 0) . '" data-bs-toggle="tooltip" title="Editar">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarCliente" idCliente="' . ($c["cli_id"] ?? 0) . '" data-bs-toggle="tooltip" title="Eliminar">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>';
                                    }
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

<!-- MODAL: Agregar cliente -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarClienteLabel">
                        <i class="fas fa-user-plus me-2"></i> Agregar cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="cli_nombre" placeholder="Ej. Juan Pérez"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="cli_telefono" placeholder="10 dígitos">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="cli_email" placeholder="correo@dominio.com">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Puntos iniciales</label>
                            <input type="number" class="form-control" name="cli_puntos" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="cli_activo" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>
                </div>

                <?php
                $guardar = new ControllerClientes();
                $guardar->ctrGuardarClientes();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Importar CSV -->
<div class="modal fade" id="modalCargarCSV" tabindex="-1" aria-labelledby="modalCargarCSVLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCargarCSVLabel">
                    <i class="fas fa-file-csv me-2"></i> Importar clientes desde CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    <strong>Formato:</strong>
                    <code>cli_nombre,cli_telefono,cli_email,cli_puntos,cli_activo</code>
                </p>
                <form method="post" enctype="multipart/form-data" id="formCSV">
                    <div class="mb-3">
                        <div id="dropzoneCSV" class="border border-primary border-2 rounded p-4 text-center bg-light"
                            style="cursor:pointer;">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-primary"></i>
                            <p class="mb-0" id="fileLabel">Arrastra tu archivo .csv aquí o haz clic</p>
                            <input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" style="display:none;"
                                required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-upload me-1"></i> Subir CSV
                        </button>
                    </div>
                    <?php
                    $csv = new ControllerClientes();
                    $csv->ctrGuardarClientesCsv();
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Editar cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarClienteLabel">
                        <i class="fas fa-user-edit me-2"></i> Editar cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="cli_id" name="cli_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editar_nombre" name="editar_nombre" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editar_telefono" name="editar_telefono">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editar_email" name="editar_email">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Puntos</label>
                            <input type="number" class="form-control" id="editar_puntos" name="editar_puntos" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="editar_activo" name="editar_activo" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar cambios
                    </button>
                </div>

                <?php
                $editar = new ControllerClientes();
                $editar->ctrEditarClientes();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$eliminar = new ControllerClientes();
$eliminar->ctrEliminarCliente();
?>