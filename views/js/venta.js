/* ============================================================
   VENTAS - JS
   ============================================================ */

// EDITAR: primero trae la venta por AJAX, luego abre modal (evita aria-hidden issues)
$(document).on("click", ".btnEditarVenta", function () {
  var id = $(this).attr("idVenta");
  if (!id) return;

  var datos = new FormData();
  datos.append("idVenta", id);

  $.ajax({
    url: "ajax/ventas.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.ven_id) {
        Swal.fire({ icon: "error", title: "No se encontró la venta" });
        return;
      }

      $("#ven_id").val(r.ven_id);
      $("#editar_suc_id").val(r.suc_id);
      $("#editar_cli_id").val(r.cli_id || "");
      $("#editar_ven_fecha").val(r.ven_fecha);
      $("#editar_ven_total").val(r.ven_total);
      $("#editar_ven_tacos_vendidos").val(r.ven_tacos_vendidos);
      $("#editar_ven_puntos_otorgados").val(r.ven_puntos_otorgados);
      $("#editar_ven_activa").val(r.ven_activa);

      var el = document.getElementById("modalEditarVenta");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show(); // (OPT) mostrar tras poblar inputs
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar venta" });
    },
  });
});

// ELIMINAR por GET
$(document).on("click", ".btnEliminarVenta", function () {
  var id = $(this).attr("idVenta");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar venta?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=ventas&idVenta=" + encodeURIComponent(id);
    }
  });
});
