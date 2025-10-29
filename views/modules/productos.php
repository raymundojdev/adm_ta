<?php
/* ==========================================================
   views/modules/productos.php  (REEMPLAZA COMPLETO)
   ========================================================== */
// Cargar catálogos para selects
$unidades   = ControllerProductos::ctrUnidades();
$proveedAct = ControllerProductos::ctrProveedoresActivos();
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h4 class="mb-0"><i class="fas fa-box-open me-2"></i>Productos <span
              class="badge bg-light text-dark ms-2" id="totalProductosBadge">0</span></h4>
          <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalCSVProductos">
              <i class="fas fa-file-csv"></i> CSV
            </button>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal"
              data-bs-target="#modalAgregarProducto">
              <i class="fas fa-plus"></i> Agregar
            </button>
          </div>
        </div>
        <div class="card-body">

          <div class="row g-2 align-items-end mb-3">
            <div class="col-sm-4">
              <label class="form-label fw-semibold">Buscar</label>
              <input type="text" id="busquedaProducto" class="form-control"
                placeholder="Nombre o proveedor">
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
                <?php foreach ($proveedAct as $pr): ?>
                  <option value="<?php echo $pr["id"]; ?>"><?php echo htmlspecialchars($pr["nombre"]); ?>
                  </option>
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
                      <?php foreach ($unidades as $u): ?>
                        <option value="<?php echo $u["id"]; ?>">
                          <?php echo htmlspecialchars($u["nombre"]) . " (" . $u["abrev"] . ")"; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Costo de referencia</label>
                    <input type="number" step="0.01" class="form-control" name="prod_costo_ref"
                      value="0.00">
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-8">
                    <label class="form-label">Proveedor habitual (opcional)</label>
                    <select class="form-control" name="prod_proveedor_id">
                      <option value="">-- Ninguno --</option>
                      <?php foreach ($proveedAct as $pr): ?>
                        <option value="<?php echo $pr["id"]; ?>">
                          <?php echo htmlspecialchars($pr["nombre"]); ?></option>
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
                  <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cerrar</button>
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
                  <input type="text" class="form-control" id="editar_prod_nombre"
                    name="editar_prod_nombre" required>
                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Unidad</label>
                    <select class="form-control" id="editar_prod_unidad_id"
                      name="editar_prod_unidad_id" required>
                      <?php foreach ($unidades as $u): ?>
                        <option value="<?php echo $u["id"]; ?>">
                          <?php echo htmlspecialchars($u["nombre"]) . " (" . $u["abrev"] . ")"; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Costo de referencia</label>
                    <input type="number" step="0.01" class="form-control" id="editar_prod_costo_ref"
                      name="editar_prod_costo_ref" value="0.00">
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-8">
                    <label class="form-label">Proveedor habitual (opcional)</label>
                    <select class="form-control" id="editar_prod_proveedor_id"
                      name="editar_prod_proveedor_id">
                      <option value="">-- Ninguno --</option>
                      <?php foreach ($proveedAct as $pr): ?>
                        <option value="<?php echo $pr["id"]; ?>">
                          <?php echo htmlspecialchars($pr["nombre"]); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                      <input class="form-check-input" type="checkbox" id="editar_prod_activo"
                        name="editar_prod_activo">
                      <label class="form-check-label" for="editar_prod_activo">Activo</label>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL: CSV Productos -->
      <div class="modal fade" id="modalCSVProductos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title"><i class="fas fa-file-csv"></i> Importar productos desde CSV</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <form id="formCSVProductos" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label class="form-label">Arrastra tu archivo .csv aquí o haz clic</label>
                  <div id="dropzoneProdCSV"
                    class="border border-primary border-2 rounded p-4 text-center bg-light"
                    style="cursor: pointer;">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-primary"></i>
                    <p id="fileLabelProductos">Ningún archivo seleccionado</p>
                    <input type="file" name="archivo_csv" id="archivo_csv_productos" accept=".csv"
                      style="display: none;" required>
                  </div>
                  <small class="text-muted d-block mt-2">
                    Formato esperado (cabecera):
                    <code>nombre,unidad,costo_ref,proveedor,activo</code><br>
                    <strong>unidad</strong>: acepta nombre o abreviatura (ej. <code>Kilogramo</code>
                    o <code>kg</code>)<br>
                    <strong>proveedor</strong>: nombre exacto (opcional) • <strong>activo</strong>:
                    1/0 (opcional, por defecto 1)
                  </small>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Subir
                    CSV</button>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>

    </div> <!-- /.container-fluid -->
  </div>
</div>

<!-- JS del módulo -->
<script src="views/assets/js/productos.js"></script>