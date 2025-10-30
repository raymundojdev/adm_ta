<!-- views/modules/cortes_caja.php -->
<?php
$sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100"
                        style="background: radial-gradient(1200px 400px at 0% -10%, rgba(13,110,253,.10), transparent 60%); pointer-events:none;">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <i class="fas fa-cash-register text-primary me-2"></i>
                                Cortes de caja
                            </h4>
                            <small class="text-muted">Abre y cierra cortes por sucursal/turno; compara declarado vs
                                sistema.</small>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbrirCorte">
                            <i class="fas fa-plus me-1"></i> Abrir corte
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-striped table-hover tablas" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Sucursal</th>
                                    <th>Turno</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Estado</th>
                                    <th class="text-end">Sistema</th>
                                    <th class="text-end">Declarado</th>
                                    <th class="text-end">Diferencia</th>
                                    <th style="width:160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cortes = ControllerCortes::ctrMostrarCortes(null, null);
                                if (!empty($cortes)) {
                                    foreach ($cortes as $k => $c) {
                                        $badge = ($c["cor_estado"] === "ABIERTO")
                                            ? '<span class="badge bg-warning text-dark">ABIERTO</span>'
                                            : '<span class="badge bg-success">CERRADO</span>';
                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($c["suc_nombre"] ?? "") . '</td>
                        <td>' . htmlspecialchars($c["cor_turno"]) . '</td>
                        <td>' . htmlspecialchars($c["cor_inicio"]) . '</td>
                        <td>' . htmlspecialchars($c["cor_fin"] ?: "—") . '</td>
                        <td>' . $badge . '</td>
                        <td class="text-end">' . (isset($c["cor_total_sistema"]) ? "$" . number_format((float)$c["cor_total_sistema"], 2) : "—") . '</td>
                        <td class="text-end">' . (isset($c["cor_total_declarado"]) ? "$" . number_format((float)$c["cor_total_declarado"], 2) : "—") . '</td>
                        <td class="text-end">' . (isset($c["cor_diferencia"]) ? "$" . number_format((float)$c["cor_diferencia"], 2) : "—") . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarCorte" idCorte="' . (int)$c["cor_id"] . '">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarCorte" idCorte="' . (int)$c["cor_id"] . '">
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

<!-- MODAL: Abrir corte -->
<div class="modal fade" id="modalAbrirCorte" tabindex="-1" aria-labelledby="modalAbrirCorteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAbrirCorteLabel">
                        <i class="fas fa-door-open me-2"></i> Abrir corte
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
                            <label class="form-label">Turno</label>
                            <select class="form-select" name="cor_turno" required>
                                <option value="MAÑANA">MAÑANA</option>
                                <option value="TARDE">TARDE</option>
                                <option value="NOCHE">NOCHE</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Inicio</label>
                            <input type="datetime-local" class="form-control" name="cor_inicio" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fondo inicial</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" name="cor_fondo_inicial"
                                    value="0" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones (opcional)</label>
                            <input type="text" class="form-control" name="cor_observaciones"
                                placeholder="Comentarios del turno">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Abrir</button>
                </div>

                <?php
                $abrir = new ControllerCortes();
                $abrir->ctrAbrirCorte();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Cerrar/Editar corte -->
<div class="modal fade" id="modalEditarCorte" tabindex="-1" aria-labelledby="modalEditarCorteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarCorteLabel">
                        <i class="fas fa-door-closed me-2"></i> Cerrar corte
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="cor_id" name="cor_id">

                    <!-- Encabezado -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" id="editar_suc_id" name="editar_suc_id" required>
                                <?php foreach ($sucursales as $s) {
                                    echo '<option value="' . $s["suc_id"] . '">' . htmlspecialchars($s["suc_nombre"]) . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Turno</label>
                            <select class="form-select" id="editar_cor_turno" name="editar_cor_turno" required>
                                <option value="MAÑANA">MAÑANA</option>
                                <option value="TARDE">TARDE</option>
                                <option value="NOCHE">NOCHE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Inicio</label>
                            <input type="datetime-local" class="form-control" id="editar_cor_inicio"
                                name="editar_cor_inicio" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fin</label>
                            <input type="datetime-local" class="form-control" id="editar_cor_fin" name="editar_cor_fin"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fondo inicial</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    id="editar_cor_fondo_inicial" name="editar_cor_fondo_inicial" required>
                            </div>
                        </div>
                    </div>

                    <!-- Totales del sistema -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Totales del sistema (por pagos aplicados en el rango)</h6>
                        </div>
                        <div class="col-md-2">
                            <div class="p-2 border rounded bg-light">Efectivo: <strong id="sys_efectivo">$0.00</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-2 border rounded bg-light">Tarjeta: <strong id="sys_tarjeta">$0.00</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">Transferencia: <strong
                                    id="sys_transfer">$0.00</strong></div>
                        </div>
                        <div class="col-md-2">
                            <div class="p-2 border rounded bg-light">Mixto: <strong id="sys_mixto">$0.00</strong></div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">Total sistema: <strong
                                    id="sys_total">$0.00</strong></div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Declarados y ajustes -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Declarados por caja</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Efectivo</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_decl_efectivo"
                                name="editar_decl_efectivo">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tarjeta</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_decl_tarjeta"
                                name="editar_decl_tarjeta">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Transferencia</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_decl_transfer"
                                name="editar_decl_transfer">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Mixto</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_decl_mixto"
                                name="editar_decl_mixto">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Gastos</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_gastos"
                                name="editar_gastos">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ingresos extra</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_ingresos_extra"
                                name="editar_ingresos_extra">
                        </div>

                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">Total declarado: <strong
                                    id="calc_total_decl">$0.00</strong></div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 border rounded bg-light">Diferencia: <strong
                                    id="calc_diferencia">$0.00</strong></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <input type="text" class="form-control" id="editar_cor_observaciones"
                                name="editar_cor_observaciones">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-lock me-1"></i> Cerrar corte</button>
                </div>

                <?php
                $cerrar = new ControllerCortes();
                $cerrar->ctrCerrarCorte();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar (GET)
$del = new ControllerCortes();
$del->ctrEliminarCorte();
?>