// controllers/dashboard.controller.php
<?php
class ControllerDashboard
{
    // NOTA: solo ejemplo de paso de datos. Conéctalo a tus consultas reales.
    static public function ctrDatosDashboard()
    {
        return [
            "kpiVentasHoy"       => $kpiVentasHoy       ?? 0.00,
            "kpiTacosHoy"        => $kpiTacosHoy        ?? 0,
            "kpiTicketPromedio"  => $kpiTicketPromedio  ?? 0.00,
            "kpiAvanceMeta"      => $kpiAvanceMeta      ?? 0,
            "kpiPedidosAbiertos" => $kpiPedidosAbiertos ?? 0,
            "kpiGastosHoy"       => $kpiGastosHoy       ?? 0.00,
            "kpiMargenEstimado"  => $kpiMargenEstimado  ?? 0.00,
            "kpiPromosActivas"   => $kpiPromosActivas   ?? 0,
            "serieHoras"         => ["09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20"],
            "serieVentasHora"    => [120, 180, 260, 300, 420, 380, 410, 450, 500, 480, 520, 560],
            "serieTacosHora"     => [12, 18, 26, 31, 41, 37, 39, 45, 49, 47, 50, 54],
            "mixPagos"           => [
                ["label" => "Efectivo", "valor" => 62],
                ["label" => "Tarjeta", "valor" => 28],
                ["label" => "Mixto", "valor" => 10],
            ],
            "topProductos"       => [
                ["pro_nombre" => "Taco de res", "cantidad" => 180, "monto" => 3600],
                ["pro_nombre" => "Quesadilla", "cantidad" => 95, "monto" => 2375],
                ["pro_nombre" => "Refresco", "cantidad" => 210, "monto" => 3150],
                ["pro_nombre" => "Taco campechano", "cantidad" => 70, "monto" => 1750],
                ["pro_nombre" => "Agua fresca", "cantidad" => 130, "monto" => 1950],
            ],
            "rankingSucursales"  => [
                ["sucursal" => "Centro", "ventas" => 7800, "tacos" => 390, "avance" => 82],
                ["sucursal" => "Norte", "ventas" => 6200, "tacos" => 315, "avance" => 74],
                ["sucursal" => "Sur", "ventas" => 4100, "tacos" => 205, "avance" => 58],
            ],
            "ultimasVentas"      => [
                ["fecha" => "2025-10-31 18:20", "sucursal" => "Centro", "cliente" => "Público", "tacos" => 8, "total" => 192],
                ["fecha" => "2025-10-31 18:05", "sucursal" => "Norte", "cliente" => "Público", "tacos" => 5, "total" => 120],
                ["fecha" => "2025-10-31 17:58", "sucursal" => "Centro", "cliente" => "Juan P.", "tacos" => 10, "total" => 240],
                ["fecha" => "2025-10-31 17:47", "sucursal" => "Sur", "cliente" => "Público", "tacos" => 3, "total" => 72],
                ["fecha" => "2025-10-31 17:32", "sucursal" => "Norte", "cliente" => "María L.", "tacos" => 6, "total" => 144],
            ],
        ];
    }
}
