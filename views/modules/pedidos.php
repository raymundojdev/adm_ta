<!-- views/modules/pedidos.php -->
<?php
// Opciones de selects precargadas
$clientes   = class_exists('ControllerClientes')   ? ControllerClientes::ctrMostrarClientes(null, null) : [];
$sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
$productos  = class_exists('ControllerProductos')  ? ControllerProductos::ctrMostrarProductos(null, null) : [];
// Render options para JS
$optProductos = '';
foreach ($productos as $pr) {
    $optProductos .= '<option value="' . $pr["pro_id"] . '">' . htmlspecialchars($pr["pro_nombre"]) . '</option>';
}
?>
<script>
// Opciones de productos disponibles para las filas dinámicas
window.__productosOptions = <?php echo json_encode($optProductos); ?>;
</script>

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
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Pedidos / Ventas
                            </h4>
                            <small class="text-muted">Registra ventas por mostrador u online, aplica puntos y controla
                                estados.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarPedido">
                                <i class="fas fa-plus me-1"></i> Nuevo pedido
                            </button>
                        </div>
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
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Sucursal</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th class="text-end">Total</th>
                                    <th>Fecha</th>
                                    <th style="width:160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pedidos = ControllerPedidos::ctrMostrarPedidos(null, null);
                                if (!empty($pedidos)) {
                                    foreach ($pedidos as $k => $p) {
                                        $badgeEstado = [
                                            "PENDIENTE" => '<span class="badge bg-warning text-dark">Pendiente</span>',
                                            "PAGADO"    => '<span class="badge bg-success">Pagado</span>',
                                            "CANCELADO" => '<span class="badge bg-secondary">Cancelado</span>',
                                        ][$p["ped_estado"]] ?? '<span class="badge bg-light text-dark">—</span>';

                                        echo '<tr>
                        <td>' . ($k + 1) . '</td>
                        <td>' . htmlspecialchars($p["ped_folio"]) . '</td>
                        <td>' . htmlspecialchars($p["cli_nombre"] ?? 'Mostrador') . '</td>
                        <td>' . htmlspecialchars($p["suc_nombre"] ?? '') . '</td>
                        <td>' . htmlspecialchars($p["ped_tipo"]) . '</td>
                        <td>' . $badgeEstado . '</td>
                        <td class="text-end">$' . number_format((float)$p["ped_total"], 2) . '</td>
                        <td>' . htmlspecialchars($p["ped_creado_en"]) . '</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarPedido" idPedido="' . (int)$p["ped_id"] . '">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarPedido" idPedido="' . (int)$p["ped_id"] . '">
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

<!-- MODAL: Agregar Pedido -->
<div class="modal fade" id="modalAgregarPedido" tabindex="-1" aria-labelledby="modalAgregarPedidoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalAgregarPedidoLabel">
                        <i class="fas fa-plus-circle me-2"></i> Nuevo pedido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Encabezado -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Folio</label>
                            <input type="text" class="form-control" name="ped_folio" placeholder="Ej. VTA-0001"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="ped_tipo" required>
                                <option value="MOSTRADOR">MOSTRADOR</option>
                                <option value="ONLINE">ONLINE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="ped_estado" required>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="PAGADO">PAGADO</option>
                                <option value="CANCELADO">CANCELADO</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" name="suc_id" required>
                                <?php
                                foreach ($sucursales as $s) {
                                    echo '<option value="' . $s["suc_id"] . '">' . htmlspecialchars($s["suc_nombre"]) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cliente (opcional)</label>
                            <select class="form-select" name="cli_id">
                                <option value="">Mostrador / Anónimo</option>
                                <?php
                                foreach ($clientes as $c) {
                                    echo '<option value="' . $c["cli_id"] . '">' . htmlspecialchars($c["cli_nombre"]) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Detalle -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Detalle del pedido</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm btnAddFilaNuevo">
                            <i class="fas fa-plus"></i> Agregar fila
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:260px;">Producto</th>
                                    <th style="width:120px;">Cantidad</th>
                                    <th style="width:140px;">Precio</th>
                                    <th style="width:140px;">Subtotal</th>
                                    <th style="width:60px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="detalles-body">
                                <!-- filas dinámicas -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th colspan="2" class="pedido_total">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar pedido
                    </button>
                </div>

                <?php
                $guardar = new ControllerPedidos();
                $guardar->ctrGuardarPedidos();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Editar Pedido -->
<div class="modal fade" id="modalEditarPedido" tabindex="-1" aria-labelledby="modalEditarPedidoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <form method="post" role="form">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarPedidoLabel">
                        <i class="fas fa-pen-to-square me-2"></i> Editar pedido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Encabezado -->
                    <input type="hidden" id="ped_id" name="ped_id">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Folio</label>
                            <input type="text" class="form-control" id="editar_folio" name="editar_folio" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" id="editar_tipo" name="editar_tipo" required>
                                <option value="MOSTRADOR">MOSTRADOR</option>
                                <option value="ONLINE">ONLINE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="editar_estado" name="editar_estado" required>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="PAGADO">PAGADO</option>
                                <option value="CANCELADO">CANCELADO</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sucursal</label>
                            <select class="form-select" id="editar_suc_id" name="editar_suc_id" required>
                                <?php
                                foreach ($sucursales as $s) {
                                    echo '<option value="' . $s["suc_id"] . '">' . htmlspecialchars($s["suc_nombre"]) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cliente (opcional)</label>
                            <select class="form-select" id="editar_cli_id" name="editar_cli_id">
                                <option value="">Mostrador / Anónimo</option>
                                <?php
                                foreach ($clientes as $c) {
                                    echo '<option value="' . $c["cli_id"] . '">' . htmlspecialchars($c["cli_nombre"]) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Detalle -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Detalle del pedido</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm btnAddFilaEditar">
                            <i class="fas fa-plus"></i> Agregar fila
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:260px;">Producto</th>
                                    <th style="width:120px;">Cantidad</th>
                                    <th style="width:140px;">Precio</th>
                                    <th style="width:140px;">Subtotal</th>
                                    <th style="width:60px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="detalles-body">
                                <!-- filas dinámicas via AJAX -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th colspan="2" class="pedido_total">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar cambios
                    </button>
                </div>

                <?php
                $editar = new ControllerPedidos();
                $editar->ctrEditarPedidos();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
// Eliminar por GET (patrón existente)
$eliminar = new ControllerPedidos();
$eliminar->ctrEliminarPedido();
?>