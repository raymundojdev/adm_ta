// views/modules/dashboard.php
<?php
/* =========================================================================
   VISTA: DASHBOARD PRINCIPAL (Sólo UI)
   - Usa variables entregadas por ControllerDashboard::ctrDatosDashboard()
   ========================================================================= */
$D = class_exists('ControllerDashboard') ? ControllerDashboard::ctrDatosDashboard() : [];

$kpiVentasHoy        = $D['kpiVentasHoy']        ?? 0.00;
$kpiTacosHoy         = $D['kpiTacosHoy']         ?? 0;
$kpiTicketPromedio   = $D['kpiTicketPromedio']   ?? 0.00;
$kpiAvanceMeta       = $D['kpiAvanceMeta']       ?? 0;
$kpiPedidosAbiertos  = $D['kpiPedidosAbiertos']  ?? 0;
$kpiGastosHoy        = $D['kpiGastosHoy']        ?? 0.00;
$kpiMargenEstimado   = $D['kpiMargenEstimado']   ?? 0.00;
$kpiPromosActivas    = $D['kpiPromosActivas']    ?? 0;

$serieHoras          = $D['serieHoras']          ?? [];
$serieVentasHora     = $D['serieVentasHora']     ?? [];
$serieTacosHora      = $D['serieTacosHora']      ?? [];
$mixPagos            = $D['mixPagos']            ?? [];
$topProductos        = $D['topProductos']        ?? [];
$rankingSucursales   = $D['rankingSucursales']   ?? [];
$ultimasVentas       = $D['ultimasVentas']       ?? [];
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- KPIs fila 1 -->
            <div class="row g-3">
                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Ventas de hoy</div>
                                <div class="fs-4 fw-semibold">$<?= number_format($kpiVentasHoy, 2) ?></div>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-money-dollar-circle-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-primary" style="width: <?= min(100, max(0, $kpiAvanceMeta)); ?>%">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Tacos vendidos</div>
                                <div class="fs-4 fw-semibold"><?= number_format($kpiTacosHoy) ?></div>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-restaurant-2-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-success" style="width: <?= min(100, max(0, $kpiAvanceMeta)); ?>%">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Ticket promedio</div>
                                <div class="fs-4 fw-semibold">$<?= number_format($kpiTicketPromedio, 2) ?></div>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-bill-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Pedidos abiertos</div>
                                <div class="fs-4 fw-semibold"><?= number_format($kpiPedidosAbiertos) ?></div>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-shopping-bag-3-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-warning"
                                style="width: <?= min(100, $kpiPedidosAbiertos * 10); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPIs fila 2 -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Gastos de hoy</div>
                                <div class="fs-4 fw-semibold text-danger">$<?= number_format($kpiGastosHoy, 2) ?></div>
                            </div>
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-file-reduce-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-danger" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Margen estimado</div>
                                <div class="fs-4 fw-semibold text-success">$<?= number_format($kpiMargenEstimado, 2) ?>
                                </div>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-line-chart-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Promos activas</div>
                                <div class="fs-4 fw-semibold"><?= number_format($kpiPromosActivas) ?></div>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width:48px;height:48px;">
                                <i class="ri-price-tag-3-line fs-4"></i>
                            </div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row g-3 mt-1">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="ri-time-line me-2"></i>Ventas y Tacos por hora (hoy)</h6>
                            <small class="text-muted">Actualiza desde tu Controller</small>
                        </div>
                        <div class="card-body">
                            <canvas id="chartVentasHora" height="120" data-labels='<?= json_encode($serieHoras) ?>'
                                data-ventas='<?= json_encode($serieVentasHora) ?>'
                                data-tacos='<?= json_encode($serieTacosHora) ?>'></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ri-pie-chart-2-line me-2"></i> Mix de pagos</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartPagos" data-mix='<?= json_encode($mixPagos) ?>' height="230"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking / Top productos / Últimas ventas -->
            <div class="row g-3 mt-1">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ri-goblet-2-line me-2"></i> Ranking de Sucursales (hoy)</h6>
                        </div>
                        <div class="card-body">
                            <?php foreach ($rankingSucursales as $row): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($row['sucursal']) ?></div>
                                        <small class="text-muted">Tacos: <?= number_format($row['tacos']) ?> •
                                            $<?= number_format($row['ventas'], 0) ?></small>
                                    </div>
                                    <div class="text-end" style="min-width:120px;">
                                        <div class="small text-muted"><?= (int)$row['avance'] ?>% meta</div>
                                        <div class="progress" style="height:6px;">
                                            <div class="progress-bar"
                                                style="width: <?= min(100, max(0, (int)$row['avance'])) ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="ri-star-line me-2"></i> Top productos (hoy)</h6>
                            <small class="text-muted">por cantidad</small>
                        </div>
                        <div class="card-body">
                            <canvas id="chartTopProductos"
                                data-labels='<?= json_encode(array_column($topProductos, "pro_nombre")) ?>'
                                data-cantidades='<?= json_encode(array_column($topProductos, "cantidad")) ?>'
                                height="180"></canvas>
                            <div class="mt-3">
                                <?php foreach ($topProductos as $p): ?>
                                    <div class="d-flex justify-content-between small border-bottom py-1">
                                        <span><?= htmlspecialchars($p['pro_nombre']) ?></span>
                                        <span><?= number_format($p['cantidad']) ?> und •
                                            $<?= number_format($p['monto'], 0) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ri-file-list-3-line me-2"></i> Últimas ventas</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Suc.</th>
                                            <th class="text-end">Tacos</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ultimasVentas as $v): ?>
                                            <tr>
                                                <td><small><?= htmlspecialchars($v['fecha']) ?></small></td>
                                                <td><small><?= htmlspecialchars($v['sucursal']) ?></small></td>
                                                <td class="text-end"><?= number_format($v['tacos']) ?></td>
                                                <td class="text-end">$<?= number_format($v['total'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="ventas" class="btn btn-outline-primary btn-sm w-100">Ver todas</a>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /container-fluid -->
    </div><!-- /page-content -->
</div><!-- /main-content -->