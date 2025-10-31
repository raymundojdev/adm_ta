// views/js/dashboard.js
/* =========================================================================
   DASHBOARD - JS (usa pedidos pagados)
   ========================================================================= */

let chartHoras, chartTop, chartSuc, chartMetas;

function renderVentasHoras(data){
  const ctx = document.getElementById('chartVentasHoras');
  if(chartHoras) chartHoras.destroy();
  chartHoras = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [...Array(24).keys()].map(h=>String(h).padStart(2,'0')+":00"),
      datasets: [{ label: 'Ventas $ por hora (hoy)', data }]
    },
    options: { responsive:true, plugins:{legend:{display:false}} }
  });
}

function renderTopProductos(data){
  const ctx = document.getElementById('chartTopProductos');
  if(chartTop) chartTop.destroy();
  chartTop = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.map(r=>r.pro_nombre),
      datasets: [{ label: 'Unidades vendidas (hoy)', data: data.map(r=>r.qty) }]
    },
    options: { responsive:true, plugins:{legend:{display:false}} }
  });
}

function renderVentasSucursal(data){
  const ctx = document.getElementById('chartVentasSucursal');
  if(chartSuc) chartSuc.destroy();
  chartSuc = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.map(r=>r.suc_nombre),
      datasets: [{ label: 'Ventas $ (hoy)', data: data.map(r=>r.total) }]
    },
    options: { responsive:true, plugins:{legend:{display:false}} }
  });
}

function renderProgresoMetas(data){
  const ctx = document.getElementById('chartMetas');
  if(chartMetas) chartMetas.destroy();
  chartMetas = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.map(r=>r.suc_nombre),
      datasets: [{ label: '% Cumplimiento', data: data.map(r=>r.pct) }]
    },
    options: {
      responsive:true, plugins:{legend:{display:false}},
      scales:{ y:{ min:0, max:100 } }
    }
  });
}

/* Cargar KPIs + gráficas */
function cargarDashboard(){
  fetch('ajax/dashboard.ajax.php?action=kpis')
    .then(r=>r.json())
    .then(k=>{
      document.getElementById('kpiVentas').innerText = '$ ' + Number(k.ventas_hoy||0).toFixed(2);
      document.getElementById('kpiTacos').innerText  = Number(k.tacos_hoy||0);
      document.getElementById('kpiGastos').innerText = '$ ' + Number(k.gastos_hoy||0).toFixed(2);
      document.getElementById('kpiMeta').innerText   = (k.meta_hoy||0) + ' / ' + (k.cumpl_meta||0) + '%';
      const bar = document.getElementById('barCumplMeta');
      if(bar){
        const v = (k.cumpl_meta||0);
        bar.style.width = v + '%';
        bar.setAttribute('aria-valuenow', v);
      }
    });

  fetch('ajax/dashboard.ajax.php?action=ventas_horas')
    .then(r=>r.json()).then(renderVentasHoras);

  fetch('ajax/dashboard.ajax.php?action=top_productos&limit=5')
    .then(r=>r.json()).then(renderTopProductos);

  fetch('ajax/dashboard.ajax.php?action=ventas_sucursal')
    .then(r=>r.json()).then(renderVentasSucursal);

  fetch('ajax/dashboard.ajax.php?action=progreso_metas')
    .then(r=>r.json()).then(renderProgresoMetas);
}

document.addEventListener('DOMContentLoaded', cargarDashboard);
