<!-- views/modules/dashboard.php -->
<?php
  require_once "controllers/dashboard.controller.php";
  require_once "models/dashboard.model.php";

  $kpis = ControllerDashboard::ctrKpisHoy();
  $pedidosRec = ControllerDashboard::ctrPedidosRecientes(8);
  $promos = ControllerDashboard::ctrPromocionesActivas(5);
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Header -->
      <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 position-relative">
          <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(1100px 380px at 0% -10%, rgba(13,110,253,.10), transparent 60%); pointer-events:none;"></div>
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <h4 class="mb-1 fw-semibold">
                <i class="fas fa-chart-line text-primary me-2"></i> Dashboard
              </h4>
              <small class="text-muted">Indicadores basados en <strong>pedidos PAGADOS</strong> (hoy).</small>
            </div>
          </div>
        </div>
      </div>

      <!-- KPIs -->
      <div class="row g-3">
        <div class="col-md-3">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <small class="text-muted">Ventas (hoy)</small>
                  <h4 id="kpiVentas" class="mb-0 fw-bold">$ <?= number_format($kpis["ventas_hoy"]??0,2) ?></h4>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                  <i class="fas fa-dollar-sign text-primary"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <small class="text-muted">Tacos vendidos (hoy)</small>
                  <h4 id="kpiTacos" class="mb-0 fw-bold"><?= (int)($kpis["tacos_hoy"]??0) ?></h4>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                  <i class="fas fa-hamburger text-success"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <small class="text-muted">Gastos (hoy)</small>
                  <h4 id="kpiGastos" class="mb-0 fw-bold">$ <?= number_format($kpis["gastos_hoy"]??0,2) ?></h4>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                  <i class="fas fa-receipt text-danger"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <small class="text-muted d-block">Meta del día</small>
              <div class="d-flex align-items-end justify-content-between">
                <h6 id="kpiMeta" class="mb-2 fw-semibold">
                  <?= (int)($kpis["meta_hoy"]??0) ?> / <?= (int)($kpis["cumpl_meta"]??0) ?>%
                </h6>
              </div>
              <div class="progress" style="height:10px;">
                <div id="barCumplMeta" class="progress-bar bg-success"
                     role="progressbar"
                     style="width: <?= (int)($kpis["cumpl_meta"]??0) ?>%;"
                     aria-valuenow="<?= (int)($kpis["cumpl_meta"]??0) ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficas -->
      <div class="row g-3 mt-1">
        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-clock me-1 text-primary"></i> Ventas por hora (hoy)</strong>
            </div>
            <div class="card-body">
              <canvas id="chartVentasHoras" height="150"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-star me-1 text-warning"></i> Top productos (hoy)</strong>
            </div>
            <div class="card-body">
              <canvas id="chartTopProductos" height="150"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-store me-1 text-success"></i> Ventas por sucursal (hoy)</strong>
            </div>
            <div class="card-body">
              <canvas id="chartVentasSucursal" height="150"></canvas>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-bullseye me-1 text-success"></i> Cumplimiento de metas (hoy)</strong>
            </div>
            <div class="card-body">
              <canvas id="chartMetas" height="150"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Listas -->
      <div class="row g-3 mt-1">
        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-list-ul me-1 text-secondary"></i> Pedidos recientes (PAGADOS)</strong>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>#ID</th>
                      <th>Sucursal</th>
                      <th>Tipo</th>
                      <th class="text-end">Total</th>
                      <th>Fecha</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($pedidosRec)): foreach($pedidosRec as $p): ?>
                      <tr>
                        <td>#<?= (int)$p["ped_id"] ?></td>
                        <td><?= htmlspecialchars($p["suc_nombre"]) ?></td>
                        <td><?= htmlspecialchars($p["ped_tipo"]) ?></td>
                        <td class="text-end">$ <?= number_format((float)$p["ped_total"],2) ?></td>
                        <td><?= htmlspecialchars($p["ped_creado_en"]) ?></td>
                      </tr>
                    <?php endforeach; else: ?>
                      <tr><td colspan="5" class="text-center text-muted">Sin pedidos pagados aún.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent">
              <strong><i class="fas fa-tags me-1 text-danger"></i> Promociones activas (hoy)</strong>
            </div>
            <div class="card-body">
              <?php if(!empty($promos)): ?>
                <ul class="list-group list-group-flush">
                  <?php foreach($promos as $pr): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                        <strong><?= htmlspecialchars($pr["prm_titulo"]) ?></strong><br>
                        <small class="text-muted">
                          Tipo: <?= htmlspecialchars($pr["prm_tipo"]) ?> · Desc: <?= number_format((float)$pr["prm_descuento"],2) ?><br>
                          Vigencia: <?= $pr["prm_inicio"] ? htmlspecialchars($pr["prm_inicio"]) : '—' ?> → <?= $pr["prm_fin"] ? htmlspecialchars($pr["prm_fin"]) : '—' ?>
                        </small>
                      </div>
                      <span class="badge rounded-pill bg-danger-subtle text-danger">Activa</span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <div class="text-muted">No hay promociones activas hoy.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Chart.js (OPT: para visualización clara) -->

<script src="views/js/dashboard.js"></script>
