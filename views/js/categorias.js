// EDITAR
$(document).on("click", ".btnEditarCategoria", function () {
  var idCategoria = $(this).attr("idCategoria");
  var datos = new FormData();
  datos.append("idCategoria", idCategoria);

  $.ajax({
    url: "ajax/categorias.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (r) {
      $("#cat_id").val(r.cat_id);
      $("#editar_nombre").val(r.cat_nombre);
      $("#editar_activa").val(r.cat_activa);

      var modalEl = document.getElementById("modalEditarCategoria");
      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar categoría" });
    },
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarCategoria", function () {
  var idCategoria = $(this).attr("idCategoria");
  Swal.fire({
    icon: "warning",
    title: "¿Está seguro?",
    showCancelButton: true,
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=categorias&idCategoria=" +
        encodeURIComponent(idCategoria);
    }
  });
});
