// views/js/gastos.js
/* =========================================================================
   GASTOS - JS
   ========================================================================= */

// EDITAR: carga por AJAX y abre modal (abrir modal SOLO después de llenar datos)
$(document).on("click", ".btnEditarGasto", function () {
  var id = $(this).attr("idGasto");
  if (!id) return;

  var datos = new FormData();
  datos.append("idGasto", id);

  $.ajax({
    url: "ajax/gastos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.gas_id) {
        Swal.fire({ icon: "error", title: "No se encontró el gasto" });
        return;
      }

      $("#gas_id").val(r.gas_id);
      $("#editar_suc_id").val(r.suc_id);
      $("#editar_cor_id").val(r.cor_id || "");
      $("#editar_gas_concepto").val(r.gas_concepto);
      $("#editar_gas_monto").val(r.gas_monto);
      $("#editar_gas_metodo").val(r.gas_metodo);
      $("#editar_gas_fecha").val(
        r.gas_fecha ? r.gas_fecha.replace(" ", "T") : ""
      );
      $("#editar_gas_comprobante").val(r.gas_comprobante || "");
      $("#editar_gas_nota").val(r.gas_nota || "");
      $("#editar_gas_estado").val(r.gas_estado);

      var el = document.getElementById("modalEditarGasto");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show(); // (OPT) evita problemas de aria-hidden mostrando tras poblar inputs
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar gasto" });
    },
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarGasto", function () {
  var id = $(this).attr("idGasto");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar gasto?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=gastos&idGasto=" + encodeURIComponent(id);
    }
  });
});
