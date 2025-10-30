<!-- views/modules/gastos.php -->
<?php
$sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
$cortes     = class_exists('ControllerCortes') ? ControllerCortes::ctrMostrarCortes(null, null) : [];
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100"
                        style="background: radial-gradient(1200px 400px at 0% -10%, rgba(13,110,253,.10), transparent 60%); pointer-events:none;">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                                Gastos
                            </h4>
                            <small class="text-muted">Registra egresos por sucursal y relaciónalos con el corte
                                (opcional).</small>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarGasto">
                            <i class="fas fa-plus me-1"></i> Nuevo gasto
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
                                    <th>Sucursal</th>
                                    <th>Concepto</th>
                                    <th>Método</th>
                                    <th class="text-end">Monto</th>
                                    <th>Fecha</th>
                                    <th>Corte</th>
                                    <th>Estado</th>
                                    <th style="width:160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $gastos = ControllerGastos::ctrMostrarGastos(null, null);
                                if (!empty($gastos)) {
                                    foreach ($gastos as $k => $g) {
                                        $badge = ($g["gas_estado"] === "APLICADO")
                                            ? '<span class="badge bg-success">Aplicado</span>'
                                            : '<span class="badge bg-secondary">Anulado</span>';

                                        $corteTxt = $g["cor_id"] ? ('#' . $g["cor_id"] . ' (' . $g["cor_turno"] . ')') : '—';

                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($g["suc_nombre"] ?? "") . '</td>
                        <td>' . htmlspecialchars($g["gas_concepto"]) . '</td>
                        <td>' . htmlspecialchars($g["gas_metodo"]) . '</td>
                        <td class="text-end">$' . number_format((float)$g["gas_monto"], 2) . '</td>
                        <td>' . htmlspecialchars($g["gas_fecha"]) . '</td>
                        <td>' . htmlspecialchars($corteTxt) . '</td>
                        <td>' . $badge . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarGasto" idGasto="' . (int)$g["gas_id"] . '">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarGasto" idGasto="' . (int)$g["gas_id"] . '">
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

<!-- MODAL: Agregar Gasto -->
<div class="modal fade" id="modalAgregarGasto" tabindex="-1" aria-labelledby="modalAgregarGastoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarGastoLabel">
                        <i class="fas fa-plus-circle me-2"></i> Registrar gasto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" name="suc_id" required>
                                <?php foreach ($sucursales as $s) {
                                    echo '<option value="' . $s["suc_id"] . '">' . htmlspecialchars($s["suc_nombre"]) . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Corte (opcional)</label>
                            <select class="form-select" name="cor_id">
                                <option value="">— (sin asignar)</option>
                                <?php foreach ($cortes as $c) {
                                    echo '<option value="' . $c["cor_id"] . '">#' . $c["cor_id"] . ' — ' . $c["suc_nombre"] . ' — ' . $c["cor_turno"] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Método</label>
                            <select class="form-select" name="gas_metodo" required>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Concepto</label>
                            <input type="text" class="form-control" name="gas_concepto"
                                placeholder="Ej. Compra de tortillas" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" name="gas_monto" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha del gasto</label>
                            <input type="datetime-local" class="form-control" name="gas_fecha" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Comprobante (URL, opcional)</label>
                            <input type="text" class="form-control" name="gas_comprobante"
                                placeholder="https://... o ruta del archivo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nota (opcional)</label>
                            <input type="text" class="form-control" name="gas_nota" placeholder="Observaciones">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="gas_estado" required>
                                <option value="APLICADO">APLICADO</option>
                                <option value="ANULADO">ANULADO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>

                <?php
                $guardar = new ControllerGastos();
                $guardar->ctrGuardarGastos();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Editar Gasto -->
<div class="modal fade" id="modalEditarGasto" tabindex="-1" aria-labelledby="modalEditarGastoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarGastoLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar gasto
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="gas_id" name="gas_id">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" id="editar_suc_id" name="editar_suc_id" required>
                                <?php foreach ($sucursales as $s) {
                                    echo '<option value="' . $s["suc_id"] . '">' . htmlspecialchars($s["suc_nombre"]) . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Corte (opcional)</label>
                            <select class="form-select" id="editar_cor_id" name="editar_cor_id">
                                <option value="">— (sin asignar)</option>
                                <?php foreach ($cortes as $c) {
                                    echo '<option value="' . $c["cor_id"] . '">#' . $c["cor_id"] . ' — ' . $c["suc_nombre"] . ' — ' . $c["cor_turno"] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Método</label>
                            <select class="form-select" id="editar_gas_metodo" name="editar_gas_metodo" required>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TARJETA">TARJETA</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="OTRO">OTRO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Concepto</label>
                            <input type="text" class="form-control" id="editar_gas_concepto" name="editar_gas_concepto"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="editar_gas_monto"
                                    name="editar_gas_monto" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha del gasto</label>
                            <input type="datetime-local" class="form-control" id="editar_gas_fecha"
                                name="editar_gas_fecha" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Comprobante (URL, opcional)</label>
                            <input type="text" class="form-control" id="editar_gas_comprobante"
                                name="editar_gas_comprobante">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nota (opcional)</label>
                            <input type="text" class="form-control" id="editar_gas_nota" name="editar_gas_nota">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="editar_gas_estado" name="editar_gas_estado" required>
                                <option value="APLICADO">APLICADO</option>
                                <option value="ANULADO">ANULADO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar
                        cambios</button>
                </div>

                <?php
                $editar = new ControllerGastos();
                $editar->ctrEditarGastos();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar por GET (patrón existente)
$del = new ControllerGastos();
$del->ctrEliminarGasto();
?>