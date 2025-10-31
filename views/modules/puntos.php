<!-- views/modules/puntos.php -->
<?php
  $clientes   = class_exists('ControllerClientes')   ? ControllerClientes::ctrMostrarClientes(null, null)   : [];
  $sucursales = class_exists('ControllerSucursales') ? ControllerSucursales::ctrMostrarSucursales(null, null) : [];
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Header -->
      <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 position-relative">
          <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(1200px 400px at 0% -10%, rgba(25,135,84,.10), transparent 60%); pointer-events:none;"></div>
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <h4 class="mb-1 fw-semibold">
                <i class="fas fa-gift text-success me-2"></i>
                Programa de Puntos
              </h4>
              <small class="text-muted">Acumula, redime y ajusta puntos por cliente. El saldo se recalcula automáticamente.</small>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarMovimiento">
              <i class="fas fa-plus me-1"></i> Nuevo movimiento
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
                  <th>Cliente</th>
                  <th>Sucursal</th>
                  <th>Tipo</th>
                  <th class="text-end">Puntos</th>
                  <th class="text-end">Saldo</th>
                  <th>Pedido</th>
                  <th>Nota</th>
                  <th>Fecha</th>
                  <th style="width:160px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $movs = ControllerPuntos::ctrMostrarMovimientos(null, null);
                  if(!empty($movs)){
                    foreach($movs as $k=>$m){
                      $badgeTipo = [
                        "ACUM"  => '<span class="badge bg-success">ACUM</span>',
                        "REDIM" => '<span class="badge bg-warning text-dark">REDIM</span>',
                        "AJUSTE"=> '<span class="badge bg-info text-dark">AJUSTE</span>',
                      ][$m["pmv_tipo"]] ?? $m["pmv_tipo"];

                      echo '<tr>
                        <td>'.($k+1).'</td>
                        <td>'.htmlspecialchars($m["cli_nombre"]).'</td>
                        <td>'.htmlspecialchars($m["suc_nombre"]).'</td>
                        <td>'.$badgeTipo.'</td>
                        <td class="text-end">'.number_format((int)$m["pmv_puntos"]).'</td>
                        <td class="text-end">'.number_format((int)$m["pmv_saldo"]).'</td>
                        <td>'.($m["ped_id"] ? "#".$m["ped_id"] : "—").'</td>
                        <td>'.htmlspecialchars($m["pmv_nota"] ?? "").'</td>
                        <td>'.htmlspecialchars($m["pmv_creado_en"]).'</td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm btnEditarMovimiento" idMovimiento="'.(int)$m["pmv_id"].'">
                              <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btnEliminarMovimiento" idMovimiento="'.(int)$m["pmv_id"].'">
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

<!-- MODAL: Agregar Movimiento -->
<div class="modal fade" id="modalAgregarMovimiento" tabindex="-1" aria-labelledby="modalAgregarMovimientoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-lg border-0">
      <form method="post" role="form">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalAgregarMovimientoLabel">
            <i class="fas fa-plus-circle me-2"></i> Nuevo movimiento de puntos
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Cliente</label>
              <select class="form-select" id="cli_id" name="cli_id" required>
                <?php foreach($clientes as $c){
                  echo '<option value="'.$c["cli_id"].'">'.htmlspecialchars($c["cli_nombre"]).'</option>';
                } ?>
              </select>
              <small class="text-muted d-block mt-1">Saldo actual: <strong id="saldoCliente">0</strong> pts</small>
            </div>
            <div class="col-md-4">
              <label class="form-label">Sucursal</label>
              <select class="form-select" name="suc_id" required>
                <?php foreach($sucursales as $s){
                  echo '<option value="'.$s["suc_id"].'">'.htmlspecialchars($s["suc_nombre"]).'</option>';
                } ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Pedido (opcional)</label>
              <input type="number" class="form-control" name="ped_id" placeholder="ID pedido">
            </div>
            <div class="col-md-4">
              <label class="form-label">Tipo</label>
              <select class="form-select" name="pmv_tipo" required>
                <option value="ACUM">ACUM</option>
                <option value="REDIM">REDIM</option>
                <option value="AJUSTE">AJUSTE</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Puntos</label>
              <input type="number" step="1" class="form-control" name="pmv_puntos" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nota (opcional)</label>
              <input type="text" class="form-control" name="pmv_nota" placeholder="Comentario">
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Guardar
          </button>
        </div>

        <?php
          $guardar = new ControllerPuntos();
          $guardar->ctrGuardarMovimiento();
        ?>
      </form>
    </div>
  </div>
</div>

<!-- MODAL: Editar Movimiento -->
<div class="modal fade" id="modalEditarMovimiento" tabindex="-1" aria-labelledby="modalEditarMovimientoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-lg border-0">
      <form method="post" role="form">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalEditarMovimientoLabel">
            <i class="fas fa-pen-to-square me-2"></i> Editar movimiento
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="pmv_id" name="pmv_id">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Cliente</label>
              <select class="form-select" id="editar_cli_id" name="editar_cli_id" required>
                <?php foreach($clientes as $c){
                  echo '<option value="'.$c["cli_id"].'">'.htmlspecialchars($c["cli_nombre"]).'</option>';
                } ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Sucursal</label>
              <select class="form-select" id="editar_suc_id" name="editar_suc_id" required>
                <?php foreach($sucursales as $s){
                  echo '<option value="'.$s["suc_id"].'">'.htmlspecialchars($s["suc_nombre"]).'</option>';
                } ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Pedido (opcional)</label>
              <input type="number" class="form-control" id="editar_ped_id" name="editar_ped_id">
            </div>
            <div class="col-md-4">
              <label class="form-label">Tipo</label>
              <select class="form-select" id="editar_pmv_tipo" name="editar_pmv_tipo" required>
                <option value="ACUM">ACUM</option>
                <option value="REDIM">REDIM</option>
                <option value="AJUSTE">AJUSTE</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Puntos</label>
              <input type="number" step="1" class="form-control" id="editar_pmv_puntos" name="editar_pmv_puntos" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nota</label>
              <input type="text" class="form-control" id="editar_pmv_nota" name="editar_pmv_nota">
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Guardar cambios
          </button>
        </div>

        <?php
          $editar = new ControllerPuntos();
          $editar->ctrEditarMovimiento();
        ?>
      </form>
    </div>
  </div>
</div>

<?php
  $del = new ControllerPuntos();
  $del->ctrEliminarMovimiento();
?>
