<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-badge-percent me-2 text-primary"></i>
                            Promociones
                        </h4>
                        <small class="text-muted">Crea descuentos, combos y cupones para impulsar ventas.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success-subtle text-success border border-success">Activas</span>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary">Históricas</span>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalAgregarPromocion">
                            <i class="fas fa-plus me-1"></i> Nueva promoción
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#modalCargarCSV">
                            <i class="fas fa-file-csv me-1"></i> Importar CSV
                        </button>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-hover example2 tablas">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th class="text-end">Valor</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th>Código</th>
                                    <th>Creado</th>
                                    <th style="width:180px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $promos = ControllerPromociones::ctrMostrarPromociones(null, null);
                                foreach ($promos as $k => $p) {
                                    $vig = ($p["prm_inicio"] ?? "") . " — " . ($p["prm_fin"] ?? "");
                                    $estado = ((int)$p["prm_activa"] === 1)
                                        ? '<span class="badge bg-success">Activa</span>'
                                        : '<span class="badge bg-secondary">Inactiva</span>';
                                    $valorFmt = number_format((float)$p["prm_valor"], 2);
                                    echo '<tr>
                      <td>' . ($k + 1) . '</td>
                      <td>' . htmlspecialchars($p["prm_nombre"]) . '</td>
                      <td>' . htmlspecialchars($p["prm_tipo"]) . '</td>
                      <td class="text-end">' . $valorFmt . '</td>
                      <td>' . htmlspecialchars($vig) . '</td>
                      <td>' . $estado . '</td>
                      <td>' . htmlspecialchars($p["prm_codigo"]) . '</td>
                      <td>' . (isset($p["prm_creado_en"]) ? $p["prm_creado_en"] : "") . '</td>
                      <td>
                        <div class="btn-group" role="group">
                          <button class="btn btn-outline-primary btn-sm btnEditarPromocion"
                                  idPromocion="' . $p["prm_id"] . '"
                                  data-bs-toggle="tooltip" title="Editar">
                            <i class="fas fa-pen"></i>
                          </button>
                          <button class="btn btn-outline-danger btn-sm btnEliminarPromocion"
                                  idPromocion="' . $p["prm_id"] . '"
                                  data-bs-toggle="tooltip" title="Eliminar">
                            <i class="fas fa-trash"></i>
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

<!-- MODAL: Agregar -->
<div class="modal fade" id="modalAgregarPromocion" tabindex="-1" aria-labelledby="modalAgregarPromocionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <form method="post">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarPromocionLabel">
                        <i class="fas fa-plus-circle me-2"></i> Nueva promoción
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="prm_nombre" placeholder="Ej. 2x1 Martes"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="prm_tipo" required>
                                <option value="porcentaje">Porcentaje</option>
                                <option value="fijo">Descuento fijo</option>
                                <option value="combo">Combo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                <input type="number" step="0.01" class="form-control" name="prm_valor"
                                    placeholder="Ej. 15.00" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vigencia: inicio</label>
                            <input type="date" class="form-control" name="prm_inicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vigencia: fin</label>
                            <input type="date" class="form-control" name="prm_fin">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Código (opcional)</label>
                            <input type="text" class="form-control" name="prm_codigo" placeholder="Ej. TACOS15">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="prm_activa" required>
                                <option value="1">Activa</option>
                                <option value="0">Inactiva</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="prm_descripcion" rows="2"
                                placeholder="Detalles de la promo"></textarea>
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
                $guardar = new ControllerPromociones();
                $guardar->ctrGuardarPromociones();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: CSV -->
<div class="modal fade" id="modalCargarCSV" tabindex="-1" aria-labelledby="modalCargarCSVLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header bg-outline-primary">
                <h5 class="modal-title" id="modalCargarCSVLabel">
                    <i class="fas fa-file-csv me-2"></i> Importar promociones desde CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    <strong>Formato:</strong>
                    <code>prm_nombre,prm_tipo,prm_valor,prm_activa,prm_inicio,prm_fin,prm_codigo,prm_descripcion</code>
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
                    $csv = new ControllerPromociones();
                    $csv->ctrGuardarPromocionesCsv();
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Editar -->
<div class="modal fade" id="modalEditarPromocion" tabindex="-1" aria-labelledby="modalEditarPromocionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg">
            <form method="post">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarPromocionLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar promoción
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="prm_id" name="prm_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editar_nombre" name="editar_nombre" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" id="editar_tipo" name="editar_tipo" required>
                                <option value="porcentaje">Porcentaje</option>
                                <option value="fijo">Descuento fijo</option>
                                <option value="combo">Combo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                <input type="number" step="0.01" class="form-control" id="editar_valor"
                                    name="editar_valor" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Vigencia: inicio</label>
                            <input type="date" class="form-control" id="editar_inicio" name="editar_inicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vigencia: fin</label>
                            <input type="date" class="form-control" id="editar_fin" name="editar_fin">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" id="editar_codigo" name="editar_codigo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="editar_activa" name="editar_activa" required>
                                <option value="1">Activa</option>
                                <option value="0">Inactiva</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="editar_descripcion" name="editar_descripcion"
                                rows="2"></textarea>
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
                $editar = new ControllerPromociones();
                $editar->ctrEditarPromociones();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
$eliminar = new ControllerPromociones();
$eliminar->ctrEliminarPromocion();
?>