/* =========================================================================
   PROMOCIONES - JS
   Delegación global compatible con DataTables
   ========================================================================= */

// EDITAR (carga por AJAX y abre modal con API BS5)
$(document).on("click", ".btnEditarPromocion", function () {
  var id = $(this).attr("idPromocion");
  if (!id) return;

  var datos = new FormData();
  datos.append("idPromocion", id);

  $.ajax({
    url: "ajax/promociones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.prm_id) {
        Swal.fire({ icon: "error", title: "No se encontró la promoción" });
        return;
      }
      // Llenar inputs
      $("#prm_id").val(r.prm_id);
      $("#editar_nombre").val(r.prm_nombre);
      $("#editar_tipo").val(r.prm_tipo);
      $("#editar_valor").val(r.prm_valor);
      $("#editar_activa").val(r.prm_activa);
      $("#editar_inicio").val(r.prm_inicio);
      $("#editar_fin").val(r.prm_fin);
      $("#editar_codigo").val(r.prm_codigo);
      $("#editar_descripcion").val(r.prm_descripcion);

      // Mostrar modal con API Bootstrap 5
      var el = document.getElementById("modalEditarPromocion");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar promoción" });
    },
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarPromocion", function () {
  var id = $(this).attr("idPromocion");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar promoción?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=promociones&idPromocion=" + encodeURIComponent(id);
    }
  });
});

// DROPZONE CSV (UX mejorada)
(function initDropzoneCSV() {
  var dz = document.getElementById("dropzoneCSV");
  var input = document.getElementById("archivo_csv");
  var label = document.getElementById("fileLabel");
  if (!dz || !input || !label) return;

  dz.addEventListener("click", function () {
    input.click();
  });
  dz.addEventListener("dragover", function (e) {
    e.preventDefault();
    dz.classList.add("bg-primary", "text-white", "shadow");
  });
  dz.addEventListener("dragleave", function () {
    dz.classList.remove("bg-primary", "text-white", "shadow");
  });
  dz.addEventListener("drop", function (e) {
    e.preventDefault();
    dz.classList.remove("bg-primary", "text-white", "shadow");
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      input.files = e.dataTransfer.files;
      label.innerText = e.dataTransfer.files[0].name;
    }
  });
  input.addEventListener("change", function () {
    label.innerText = input.files.length
      ? input.files[0].name
      : "Ningún archivo";
  });
})();
