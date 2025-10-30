// views/js/clientes.js
/* =========================================================================
   CLIENTES - JS
   Delegación global compatible con DataTables
   ========================================================================= */

// EDITAR: carga por AJAX y abre modal con API BS5
$(document).on("click", ".btnEditarCliente", function () {
  var id = $(this).attr("idCliente");
  if (!id) return;

  var datos = new FormData();
  datos.append("idCliente", id);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      if (!r || !r.cli_id) {
        Swal.fire({ icon: "error", title: "No se encontró el cliente" });
        return;
      }
      // Llenar inputs
      $("#cli_id").val(r.cli_id);
      $("#editar_nombre").val(r.cli_nombre);
      $("#editar_telefono").val(r.cli_telefono);
      $("#editar_email").val(r.cli_email);
      $("#editar_puntos").val(r.cli_puntos);
      $("#editar_activo").val(r.cli_activo);

      var el = document.getElementById("modalEditarCliente");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar cliente" });
    },
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarCliente", function () {
  var id = $(this).attr("idCliente");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar cliente?",
    text: "Esta acción no se puede deshacer",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=clientes&idCliente=" + encodeURIComponent(id);
    }
  });
});

// DROPZONE CSV (UX)
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
