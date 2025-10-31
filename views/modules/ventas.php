<?php
/* =========================================================================
   VISTA: VENTAS
   - UI Bootstrap 5
   - Tabla + Modales “Agregar” y “Editar”
   - POST clásico (sin JSON)
   - Requiere: ControllerVentas / ModelVentas
               ControllerSucursales / ControllerClientes (para combos)
   ========================================================================= */

$sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
$clientes   = class_exists('ControllerClientes')   ? ControllerClientes::ctrMostrarClientes(null, null)     : [];
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-semibold">
                            <i class="ri-shopping-bag-line text-success me-2"></i>
                            Ventas
                        </h4>
                        <small class="text-muted">Registro de ventas por sucursal y fecha.</small>
                    </div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarVenta">
                        <i class="fas fa-plus me-1"></i> Nueva venta
                    </button>
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
                                    <th>Fecha</th>
                                    <th>Sucursal</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Tacos</th>
                                    <th class="text-end">Total</th>
                                    <th>Puntos</th>
                                    <th>Activa</th>
                                    <th style="width:150px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ventas = ControllerVentas::ctrMostrarVentas(null, null);
                                if (!empty($ventas)) {
                                    foreach ($ventas as $k => $v) {
                                        $badge = ((int)$v["ven_activa"] === 1)
                                            ? '<span class="badge bg-success">Sí</span>'
                                            : '<span class="badge bg-secondary">No</span>';

                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($v["ven_fecha"]) . '</td>
                        <td>' . htmlspecialchars($v["suc_nombre"] ?? ("#" . (int)$v["suc_id"])) . '</td>
                        <td>' . htmlspecialchars($v["cli_nombre"] ?? "") . '</td>
                        <td class="text-end">' . number_format((int)$v["ven_tacos_vendidos"]) . '</td>
                        <td class="text-end">$' . number_format((float)$v["ven_total"], 2) . '</td>
                        <td>' . (int)$v["ven_puntos_otorgados"] . '</td>
                        <td>' . $badge . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarVenta" idVenta="' . (int)$v["ven_id"] . '" title="Editar">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarVenta" idVenta="' . (int)$v["ven_id"] . '" title="Eliminar">
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

        </div><!-- container-fluid -->
    </div><!-- page-content -->
</div><!-- main-content -->


<!-- ============================ MODAL: Agregar Venta ============================ -->
<div class="modal fade" id="modalAgregarVenta" tabindex="-1" aria-labelledby="modalAgregarVentaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form" autocomplete="off">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalAgregarVentaLabel">
                        <i class="fas fa-plus-circle me-2"></i> Nueva venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" name="suc_id" required>
                                <?php foreach ($sucursales as $s): ?>
                                    <option value="<?php echo $s['suc_id']; ?>">
                                        <?php echo htmlspecialchars($s['suc_nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="ven_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Cliente (opcional)</label>
                            <select class="form-select" name="cli_id">
                                <option value="">— Sin cliente —</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?php echo $c['cli_id']; ?>">
                                        <?php echo htmlspecialchars($c['cli_nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tacos vendidos</label>
                            <input type="number" min="0" class="form-control" name="ven_tacos_vendidos" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="ven_total" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Puntos otorgados</label>
                            <input type="number" min="0" class="form-control" name="ven_puntos_otorgados" value="0"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Activa</label>
                            <select class="form-select" name="ven_activa" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>

                <?php
                $guardar = new ControllerVentas();
                $guardar->ctrGuardarVenta();
                ?>
            </form>
        </div>
    </div>
</div>


<!-- ============================== MODAL: Editar Venta ============================== -->
<div class="modal fade" id="modalEditarVenta" tabindex="-1" aria-labelledby="modalEditarVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form" autocomplete="off">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEditarVentaLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar venta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="ven_id" name="ven_id">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" id="editar_suc_id" name="editar_suc_id" required>
                                <?php foreach ($sucursales as $s): ?>
                                    <option value="<?php echo $s['suc_id']; ?>">
                                        <?php echo htmlspecialchars($s['suc_nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="editar_ven_fecha" name="editar_ven_fecha"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Cliente (opcional)</label>
                            <select class="form-select" id="editar_cli_id" name="editar_cli_id">
                                <option value="">— Sin cliente —</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?php echo $c['cli_id']; ?>">
                                        <?php echo htmlspecialchars($c['cli_nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tacos vendidos</label>
                            <input type="number" min="0" class="form-control" id="editar_ven_tacos_vendidos"
                                name="editar_ven_tacos_vendidos" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="editar_ven_total"
                                name="editar_ven_total" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Puntos otorgados</label>
                            <input type="number" min="0" class="form-control" id="editar_ven_puntos_otorgados"
                                name="editar_ven_puntos_otorgados" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Activa</label>
                            <select class="form-select" id="editar_ven_activa" name="editar_ven_activa" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar
                        cambios</button>
                </div>

                <?php
                $editar = new ControllerVentas();
                $editar->ctrEditarVenta();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar por GET (patrón que usas)
$del = new ControllerVentas();
$del->ctrEliminarVenta();
?>

<!-- SCRIPTS DEL MÓDULO -->
<script src="views/js/ventas.js"></script>