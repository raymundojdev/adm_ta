/* =========================================================================
   METAS TACOS - JS
   ========================================================================= */

// EDITAR: puebla inputs por AJAX y luego abre el modal (previene aria-hidden issues)
$(document).on("click", ".btnEditarMeta", function () {
  var id = $(this).attr("idMeta");
  if (!id) return;

  var datos = new FormData();
  datos.append("idMeta", id);

  $.ajax({
    url: "ajax/metas_tacos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.met_id) {
        Swal.fire({ icon: "error", title: "No se encontró la meta" });
        return;
      }

      $("#met_id").val(r.met_id);
      $("#editar_suc_id").val(r.suc_id);
      $("#editar_cat_id").val(r.cat_id || "");
      $("#editar_pro_id").val(r.pro_id || "");
      $("#editar_met_fecha").val(r.met_fecha);
      $("#editar_met_cantidad").val(r.met_cantidad);
      $("#editar_met_nota").val(r.met_nota || "");
      $("#editar_met_activa").val(r.met_activa);

      var el = document.getElementById("modalEditarMeta");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show(); // (OPT) mostrar tras poblar inputs evita “aria-hidden focus” warnings
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar meta" });
    },
  });
});

// ELIMINAR por GET (mantiene tu patrón actual)
$(document).on("click", ".btnEliminarMeta", function () {
  var id = $(this).attr("idMeta");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar meta?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=metas_tacos&idMeta=" + encodeURIComponent(id);
    }
  });
});
