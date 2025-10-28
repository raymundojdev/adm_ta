<?php
/* ============================================
   views/modules/productos.php
   ============================================ */
// Cargar catálogos para selects
$unidades   = ControllerProductos::ctrUnidades();
$proveedAct = ControllerProductos::ctrProveedoresActivos();
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-white d-flex justify-content-between align-items-center">
          <h4 class="mb-0"><i class="fas fa-box-open me-2"></i>Productos <span class="badge bg-light text-dark ms-2" id="totalProductosBadge">0</span></h4>
          <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
            <i class="fas fa-plus"></i> Agregar
          </button>
        </div>
        <div class="card-body">

          <div class="row g-2 align-items-end mb-3">
            <div class="col-sm-4">
              <label class="form-label fw-semibold">Buscar</label>
              <input type="text" id="busquedaProducto" class="form-control" placeholder="Nombre o proveedor">
            </div>
            <div class="col-sm-2">
              <label class="form-label fw-semibold">Estatus</label>
              <select id="filtroActivo" class="form-select">
                <option value="">Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label class="form-label fw-semibold">Proveedor</label>
              <select id="filtroProveedor" class="form-select">
                <option value="">Todos</option>
                <?php foreach($proveedAct as $pr): ?>
                  <option value="<?php echo $pr["id"]; ?>"><?php echo htmlspecialchars($pr["nombre"]); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label class="form-label fw-semibold">Por página</label>
              <select id="perPage" class="form-select">
                <option value="10">10</option>
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-striped align-middle example2 tablas mb-0" id="tablaProductos">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Producto</th>
                  <th>Unidad</th>
                  <th>Costo ref.</th>
                  <th>Proveedor</th>
                  <th>Activo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Se llena por AJAX con paginado -->
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-end pt-2" id="productosPagination"></div>

        </div>
      </div>

      <!-- MODAL: Agregar -->
      <div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fas fa-plus"></i> Agregar Producto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form method="post" role="form" id="formAgregarProducto">
                <div class="mb-3">
                  <label class="form-label">Nombre</label>
                  <input type="text" class="form-control" name="prod_nombre" required>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Unidad</label>
                    <select class="form-control" name="prod_unidad_id" required>
                      <?php foreach($unidades as $u): ?>
                        <option value="<?php echo $u["id"]; ?>"><?php echo htmlspecialchars($u["nombre"])." (".$u["abrev"].")"; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Costo de referencia</label>
                    <input type="number" step="0.01" class="form-control" name="prod_costo_ref" value="0.00">
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-8">
                    <label class="form-label">Proveedor habitual (opcional)</label>
                    <select class="form-control" name="prod_proveedor_id">
                      <option value="">-- Ninguno --</option>
                      <?php foreach($proveedAct as $pr): ?>
                        <option value="<?php echo $pr["id"]; ?>"><?php echo htmlspecialchars($pr["nombre"]); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                      <input class="form-check-input" type="checkbox" name="prod_activo" checked>
                      <label class="form-check-label">Activo</label>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL: Editar -->
      <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Producto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form method="post" role="form" id="formEditarProducto">
                <input type="hidden" id="idProducto" name="idProducto">

                <div class="mb-3">
                  <label class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="editar_prod_nombre" name="editar_prod_nombre" required>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Unidad</label>
                    <select class="form-control" id="editar_prod_unidad_id" name="editar_prod_unidad_id" required>
                      <?php foreach($unidades as $u): ?>
                        <option value="<?php echo $u["id"]; ?>"><?php echo htmlspecialchars($u["nombre"])." (".$u["abrev"].")"; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Costo de referencia</label>
                    <input type="number" step="0.01" class="form-control" id="editar_prod_costo_ref" name="editar_prod_costo_ref" value="0.00">
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-8">
                    <label class="form-label">Proveedor habitual (opcional)</label>
                    <select class="form-control" id="editar_prod_proveedor_id" name="editar_prod_proveedor_id">
                      <option value="">-- Ninguno --</option>
                      <?php foreach($proveedAct as $pr): ?>
                        <option value="<?php echo $pr["id"]; ?>"><?php echo htmlspecialchars($pr["nombre"]); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                      <input class="form-check-input" type="checkbox" id="editar_prod_activo" name="editar_prod_activo">
                      <label class="form-check-label" for="editar_prod_activo">Activo</label>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- /.container-fluid -->
  </div>
</div>

