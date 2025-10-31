<?php
/* =========================================================================
   VISTA: METAS DE TACOS (por día / sucursal)
   - UI Bootstrap 5
   - Tabla + Modales “Agregar” y “Editar”
   - POST clásico (sin JSON)
   - Requiere: ControllerMetasTacos / ModelMetasTacos
               ControllerSucursales / ControllerCategorias / ControllerProductos (para combos)
   ========================================================================= */

$sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
$categorias = class_exists('ControllerCategorias') ? ControllerCategorias::ctrMostrarCategorias(null, null) : [];
$productos  = class_exists('ControllerProductos')  ? ControllerProductos::ctrMostrarProductos(null, null)  : [];
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Encabezado -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 start-0 w-100 h-100"
                        style="background: radial-gradient(1100px 380px at 0% -10%, rgba(25,135,84,.10), transparent 60%); pointer-events:none;">
                    </div>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-semibold">
                                <i class="fas fa-bullseye text-success me-2"></i>
                                Metas de tacos por día
                            </h4>
                            <small class="text-muted">Define objetivos por sucursal y, opcionalmente, por categoría o
                                producto.</small>
                        </div>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarMeta">
                            <i class="fas fa-plus me-1"></i> Nueva meta
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
                                    <th>Fecha</th>
                                    <th>Ámbito</th>
                                    <th class="text-end">Meta (tacos)</th>
                                    <th>Activa</th>
                                    <th style="width:160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $metas = ControllerMetasTacos::ctrMostrarMetas(null, null);
                                if (!empty($metas)) {
                                    foreach ($metas as $k => $m) {
                                        $ambito = "Global (tacos)";
                                        if (!empty($m["pro_id"]) && !empty($m["pro_nombre"])) {
                                            $ambito = "Producto: " . htmlspecialchars($m["pro_nombre"]);
                                        } elseif (!empty($m["cat_id"]) && !empty($m["cat_nombre"])) {
                                            $ambito = "Categoría: " . htmlspecialchars($m["cat_nombre"]);
                                        }

                                        $badge = ((int)$m["met_activa"] === 1)
                                            ? '<span class="badge bg-success">Sí</span>'
                                            : '<span class="badge bg-secondary">No</span>';

                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($m["suc_nombre"] ?? ("#" . (int)$m["suc_id"])) . '</td>
                        <td>' . htmlspecialchars($m["met_fecha"]) . '</td>
                        <td>' . htmlspecialchars($ambito) . '</td>
                        <td class="text-end">' . number_format((int)$m["met_cantidad"]) . '</td>
                        <td>' . $badge . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarMeta" idMeta="' . (int)$m["met_id"] . '" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEditarMeta">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarMeta" idMeta="' . (int)$m["met_id"] . '" title="Eliminar">
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


<!-- ============================ MODAL: Agregar Meta ============================ -->
<div class="modal fade" id="modalAgregarMeta" tabindex="-1" aria-labelledby="modalAgregarMetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form" autocomplete="off">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalAgregarMetaLabel">
                        <i class="fas fa-plus-circle me-2"></i> Nueva meta de tacos
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
                            <input type="date" class="form-control" name="met_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Activa</label>
                            <select class="form-select" name="met_activa" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ámbito (opcional)</label>
                            <div class="d-flex gap-2">
                                <select class="form-select" name="cat_id" title="Categoría (opcional)">
                                    <option value="">— Categoría —</option>
                                    <?php foreach ($categorias as $c): ?>
                                    <option value="<?php echo $c['cat_id']; ?>">
                                        <?php echo htmlspecialchars($c['cat_nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-select" name="pro_id" title="Si eliges producto, ignora categoría">
                                    <option value="">— Producto —</option>
                                    <?php foreach ($productos as $p): ?>
                                    <option value="<?php echo $p['pro_id']; ?>">
                                        <?php echo htmlspecialchars($p['pro_nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <small class="text-muted">Si eliges un producto, se ignorará la categoría.</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Meta (tacos)</label>
                            <input type="number" min="1" class="form-control" name="met_cantidad" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nota (opcional)</label>
                            <input type="text" class="form-control" name="met_nota"
                                placeholder="Ej. día de baja afluencia">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>

                <?php
                $guardar = new ControllerMetasTacos();
                $guardar->ctrGuardarMeta();
                ?>
            </form>
        </div>
    </div>
</div>


<!-- ============================== MODAL: Editar Meta ============================== -->
<div class="modal fade" id="modalEditarMeta" tabindex="-1" aria-labelledby="modalEditarMetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form" autocomplete="off">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEditarMetaLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar meta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="met_id" name="met_id">

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
                            <input type="date" class="form-control" id="editar_met_fecha" name="editar_met_fecha"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Activa</label>
                            <select class="form-select" id="editar_met_activa" name="editar_met_activa" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ámbito (opcional)</label>
                            <div class="d-flex gap-2">
                                <select class="form-select" id="editar_cat_id" name="editar_cat_id">
                                    <option value="">— Categoría —</option>
                                    <?php foreach ($categorias as $c): ?>
                                    <option value="<?php echo $c['cat_id']; ?>">
                                        <?php echo htmlspecialchars($c['cat_nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-select" id="editar_pro_id" name="editar_pro_id">
                                    <option value="">— Producto —</option>
                                    <?php foreach ($productos as $p): ?>
                                    <option value="<?php echo $p['pro_id']; ?>">
                                        <?php echo htmlspecialchars($p['pro_nombre']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <small class="text-muted">Si eliges un producto, se ignorará la categoría.</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Meta (tacos)</label>
                            <input type="number" min="1" class="form-control" id="editar_met_cantidad"
                                name="editar_met_cantidad" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Nota (opcional)</label>
                            <input type="text" class="form-control" id="editar_met_nota" name="editar_met_nota">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar
                        cambios</button>
                </div>

                <?php
                $editar = new ControllerMetasTacos();
                $editar->ctrEditarMeta();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar por GET (tu patrón actual)
$del = new ControllerMetasTacos();
$del->ctrEliminarMeta();
?>

<!-- SCRIPTS DEL MÓDULO -->
<script src="views/js/metas_tacos.js"></script>