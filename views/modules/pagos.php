<!-- views/modules/pagos.php -->
<?php
$pedidos = class_exists('ControllerPedidos') ? ControllerPedidos::ctrMostrarPedidos(null, null) : [];
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100"
                        style="background: radial-gradient(1200px 400px at 0% -10%, rgba(13,110,253,.12), transparent 60%); pointer-events:none;">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <i class="fas fa-cash-register text-primary me-2"></i>
                                Pagos / Cobros
                            </h4>
                            <small class="text-muted">Registra cobros por pedido y calcula cambio en efectivo.</small>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarPago">
                            <i class="fas fa-plus me-1"></i> Nuevo pago
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-hover example2 tablas" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Pedido</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Recibido</th>
                                    <th>Cambio</th>
                                    <th>Referencia</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th style="width:160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pagos = ControllerPagos::ctrMostrarPagos(null, null);
                                if (!empty($pagos)) {
                                    foreach ($pagos as $k => $p) {
                                        $badge = ($p["pag_estado"] === "APLICADO")
                                            ? '<span class="badge bg-success">Aplicado</span>'
                                            : '<span class="badge bg-secondary">Anulado</span>';

                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($p["ped_folio"] ?? ("#" . $p["ped_id"])) . '</td>
                        <td>$' . number_format((float)$p["pag_monto"], 2) . '</td>
                        <td>' . htmlspecialchars($p["pag_metodo"]) . '</td>
                        <td>' . ($p["pag_recibido"] !== null ? "$" . number_format((float)$p["pag_recibido"], 2) : "—") . '</td>
                        <td>' . ($p["pag_cambio"]   !== null ? "$" . number_format((float)$p["pag_cambio"], 2)   : "—") . '</td>
                        <td>' . htmlspecialchars($p["pag_referencia"] ?? "—") . '</td>
                        <td>' . $badge . '</td>
                        <td>' . htmlspecialchars($p["pag_creado_en"]) . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarPago" idPago="' . (int)$p["pag_id"] . '">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarPago" idPago="' . (int)$p["pag_id"] . '">
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

<!-- MODAL: Agregar Pago -->
<div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarPagoLabel">
                        <i class="fas fa-plus-circle me-2"></i> Registrar pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pedido</label>
                            <select class="form-select" name="ped_id" required>
                                <?php
                                foreach ($pedidos as $pd) {
                                    echo '<option value="' . $pd["ped_id"] . '">' . htmlspecialchars(($pd["ped_folio"] ?: "#" . $pd["ped_id"])) . ' — $' . number_format((float)$pd["ped_total"], 2) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Método</label>
                            <select class="form-select" id="pag_metodo" name="pag_metodo" required>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="MIXTO">MIXTO</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="pag_estado" required>
                                <option value="APLICADO">APLICADO</option>
                                <option value="ANULADO">ANULADO</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="pag_monto" name="pag_monto" step="0.01"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Recibido (efectivo)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="pag_recibido" name="pag_recibido"
                                    step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cambio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="pag_cambio" name="pag_cambio" step="0.01"
                                    min="0" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Referencia (opcional)</label>
                            <input type="text" class="form-control" name="pag_referencia"
                                placeholder="Folio/últimos 4/ID transferencia">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>

                <?php
                $guardar = new ControllerPagos();
                $guardar->ctrGuardarPagos();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Editar Pago -->
<div class="modal fade" id="modalEditarPago" tabindex="-1" aria-labelledby="modalEditarPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarPagoLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar pago
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="pag_id" name="pag_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pedido</label>
                            <select class="form-select" id="editar_ped_id" name="editar_ped_id" required>
                                <?php
                                foreach ($pedidos as $pd) {
                                    echo '<option value="' . $pd["ped_id"] . '">' . htmlspecialchars(($pd["ped_folio"] ?: "#" . $pd["ped_id"])) . ' — $' . number_format((float)$pd["ped_total"], 2) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Método</label>
                            <select class="form-select" id="editar_pag_metodo" name="editar_pag_metodo" required>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="MIXTO">MIXTO</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="editar_pag_estado" name="editar_pag_estado" required>
                                <option value="APLICADO">APLICADO</option>
                                <option value="ANULADO">ANULADO</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="editar_pag_monto" name="editar_pag_monto"
                                    step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Recibido (efectivo)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="editar_pag_recibido"
                                    name="editar_pag_recibido" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cambio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="editar_pag_cambio"
                                    name="editar_pag_cambio" step="0.01" min="0" readonly>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Referencia (opcional)</label>
                            <input type="text" class="form-control" id="editar_pag_referencia"
                                name="editar_pag_referencia">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar
                        cambios</button>
                </div>

                <?php
                $editar = new ControllerPagos();
                $editar->ctrEditarPagos();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar por GET (patrón existente)
$eliminar = new ControllerPagos();
$eliminar->ctrEliminarPago();
?>