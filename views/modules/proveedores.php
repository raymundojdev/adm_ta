<?php
// views/modules/proveedores.php
// Asegúrate de tener cargados Bootstrap 5, jQuery y SweetAlert en tu plantilla general.
// Ruta base para AJAX según tu estructura:
$ajaxUrl = "ajax/proveedores.ajax.php";

?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-white d-flex justify-content-between align-items-center">
          <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Proveedores</h4>
          <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalProveedor">
            <i class="fas fa-plus"></i> Agregar proveedor
          </button>
        </div>

        <div class="card-body">

          <div class="row g-2 mb-3 align-items-end">
            <div class="col-12 col-md-4">
              <label class="form-label fw-semibold">Buscar</label>
              <input type="text" id="busquedaProveedor" class="form-control" placeholder="Nombre, RFC, email, teléfono">
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label fw-semibold">Estatus</label>
              <select id="filtroEstatus" class="form-select">
                <option value="">Todos</option>
                <option value="ACTIVO">Activos</option>
                <option value="INACTIVO">Inactivos</option>
              </select>
            </div>
            <div class="col-12 col-md-2">
              <button id="btnFiltrarProveedor" class="btn btn-secondary w-100">
                <i class="fas fa-search"></i> Filtrar
              </button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-striped align-middle example2 tablas mb-0" id="tablaProveedores">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>RFC</th>
                  <th>Dirección</th>
                  <th>Estatus</th>
                  <th>Fecha alta</th>
                  <th style="width:220px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- filas renderizadas por JS -->
              </tbody>
            </table>
          </div>

        </div> <!-- /.card-body -->
      </div> <!-- /.card -->

    </div> <!-- /.container-fluid -->
  </div> <!-- /.page-content -->
</div> <!-- /.main-content -->

<!-- Modal Agregar/Editar -->
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="formProveedor" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalProveedorLabel">Agregar proveedor</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" name="id" id="prov_id">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="prov_nombre" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="prov_telefono" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="prov_email" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">RFC</label>
            <input type="text" name="rfc" id="prov_rfc" class="form-control">
          </div>
          <div class="col-md-8">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" id="prov_direccion" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Estatus</label>
            <select name="estatus" id="prov_estatus" class="form-select">
              <option value="ACTIVO">ACTIVO</option>
              <option value="INACTIVO">INACTIVO</option>
            </select>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- JS del módulo -->
<script>
  const PROV_AJAX_URL = "<?php echo $ajaxUrl; ?>";
</script>
