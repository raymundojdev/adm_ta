// views/js/cortes_caja.js
/* =========================================================================
   CORTES DE CAJA - JS
   ========================================================================= */

// EDITAR/CERRAR: carga corte + totales por AJAX y abre modal
$(document).on("click", ".btnEditarCorte", function () {
  var id = $(this).attr("idCorte");
  if (!id) return;

  var datos = new FormData();
  datos.append("idCorte", id);

  $.ajax({
    url: "ajax/cortes_caja.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      var c = r.corte || {};
      var t = r.totales || {};

      // Encabezado
      $("#cor_id").val(c.cor_id);
      $("#editar_suc_id").val(c.suc_id);
      $("#editar_cor_turno").val(c.cor_turno);
      $("#editar_cor_inicio").val(c.cor_inicio.replace(" ", "T"));
      $("#editar_cor_fin").val(
        c.cor_fin ? c.cor_fin : new Date().toISOString().slice(0, 16)
      );
      $("#editar_cor_fondo_inicial").val(c.cor_fondo_inicial);
      $("#editar_cor_observaciones").val(c.cor_observaciones || "");

      // Totales sistema (solo lectura)
      $("#sys_efectivo").text("$" + parseFloat(t.efectivo || 0).toFixed(2));
      $("#sys_tarjeta").text("$" + parseFloat(t.tarjeta || 0).toFixed(2));
      $("#sys_transfer").text(
        "$" + parseFloat(t.transferencia || 0).toFixed(2)
      );
      $("#sys_mixto").text("$" + parseFloat(t.mixto || 0).toFixed(2));
      $("#sys_total").text("$" + parseFloat(t.total_sistema || 0).toFixed(2));

      // Reset declarados
      [
        "efectivo",
        "tarjeta",
        "transfer",
        "mixto",
        "gastos",
        "ingresos_extra",
      ].forEach(function (n) {
        $("#editar_decl_" + n).val("");
      });
      $("#calc_total_decl").text("$0.00");
      $("#calc_diferencia").text("$0.00");

      var el = document.getElementById("modalEditarCorte");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar corte" });
    },
  });
});

// CALCULADORA: total declarado y diferencia
function recalc() {
  var fondo = parseFloat($("#editar_cor_fondo_inicial").val() || "0");
  var declEf = parseFloat($("#editar_decl_efectivo").val() || "0");
  var declTa = parseFloat($("#editar_decl_tarjeta").val() || "0");
  var declTr = parseFloat($("#editar_decl_transfer").val() || "0");
  var declMx = parseFloat($("#editar_decl_mixto").val() || "0");
  var gastos = parseFloat($("#editar_gastos").val() || "0");
  var extra = parseFloat($("#editar_ingresos_extra").val() || "0");

  var sysTotal = parseFloat($("#sys_total").text().replace("$", "") || "0");

  var totalDecl = declEf + declTa + declTr + declMx;
  $("#calc_total_decl").text("$" + totalDecl.toFixed(2));

  var diferencia = totalDecl + gastos - extra - (sysTotal + fondo);
  $("#calc_diferencia").text("$" + diferencia.toFixed(2));
}

$(document).on(
  "input",
  "#editar_cor_fondo_inicial, #editar_decl_efectivo, #editar_decl_tarjeta, #editar_decl_transfer, #editar_decl_mixto, #editar_gastos, #editar_ingresos_extra",
  recalc
);

// ELIMINAR
$(document).on("click", ".btnEliminarCorte", function () {
  var id = $(this).attr("idCorte");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar corte?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=cortes_caja&idCorte=" + encodeURIComponent(id);
    }
  });
});
