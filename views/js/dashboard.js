// views/js/dashboard.js
/* ============================================================
   DASHBOARD - Gráficos (solo UI)
   ============================================================ */
(function () {
  // Línea: Ventas ($) y Tacos por hora
  const el1 = document.getElementById("chartVentasHora");
  if (el1) {
    const labels = JSON.parse(el1.dataset.labels || "[]");
    const ventas = JSON.parse(el1.dataset.ventas || "[]");
    const tacos = JSON.parse(el1.dataset.tacos || "[]");
    new Chart(el1, {
      type: "line",
      data: {
        labels,
        datasets: [
          { label: "Ventas ($)", data: ventas, borderWidth: 2, tension: 0.35 },
          { label: "Tacos", data: tacos, borderWidth: 2, tension: 0.35 },
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: "bottom" } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  // Pie: Mix de pagos
  const el2 = document.getElementById("chartPagos");
  if (el2) {
    const mix = JSON.parse(el2.dataset.mix || "[]");
    new Chart(el2, {
      type: "pie",
      data: {
        labels: mix.map((x) => x.label),
        datasets: [{ data: mix.map((x) => x.valor) }],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: "bottom" } },
      },
    });
  }

  // Barras: Top productos
  const el3 = document.getElementById("chartTopProductos");
  if (el3) {
    const labels = JSON.parse(el3.dataset.labels || "[]");
    const cantidades = JSON.parse(el3.dataset.cantidades || "[]");
    new Chart(el3, {
      type: "bar",
      data: { labels, datasets: [{ label: "Unidades", data: cantidades }] },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }
})();
