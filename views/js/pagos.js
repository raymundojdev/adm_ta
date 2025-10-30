/* =========================================================================
   PAGOS - JS
   ========================================================================= */

// EDITAR: carga por AJAX y abre modal
$(document).on("click", ".btnEditarPago", function () {
  var id = $(this).attr("idPago");
  if (!id) return;

  var datos = new FormData();
  datos.append("idPago", id);

  $.ajax({
    url: "ajax/pagos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.pag_id) {
        Swal.fire({ icon: "error", title: "No se encontró el pago" });
        return;
      }

      $("#pag_id").val(r.pag_id);
      $("#editar_ped_id").val(r.ped_id);
      $("#editar_pag_monto").val(r.pag_monto);
      $("#editar_pag_metodo").val(r.pag_metodo);
      $("#editar_pag_recibido").val(r.pag_recibido || "");
      $("#editar_pag_cambio").val(r.pag_cambio || "");
      $("#editar_pag_referencia").val(r.pag_referencia || "");
      $("#editar_pag_estado").val(r.pag_estado);

      var el = document.getElementById("modalEditarPago");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar pago" });
    },
  });
});

// ELIMINAR: confirmación
$(document).on("click", ".btnEliminarPago", function () {
  var id = $(this).attr("idPago");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar pago?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location = "index.php?url=pagos&idPago=" + encodeURIComponent(id);
    }
  });
});

// UX: calcular cambio (EFECTIVO)
function calcCambio(scopePrefix) {
  var monto = parseFloat($(scopePrefix + "_monto").val() || "0");
  var recibido = parseFloat($(scopePrefix + "_recibido").val() || "0");
  var metodo = $(scopePrefix + "_metodo").val();
  if (metodo === "EFECTIVO") {
    var cambio = Math.max(0, recibido - monto);
    $(scopePrefix + "_cambio").val(cambio.toFixed(2));
  } else {
    $(scopePrefix + "_cambio").val("");
  }
}

$(document).on(
  "input change",
  "#pag_monto, #pag_recibido, #pag_metodo",
  function () {
    calcCambio("#pag");
  }
);
$(document).on(
  "input change",
  "#editar_pag_monto, #editar_pag_recibido, #editar_pag_metodo",
  function () {
    calcCambio("#editar_pag");
  }
);
