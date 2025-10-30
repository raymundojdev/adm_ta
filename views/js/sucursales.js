// EDITAR SUCURSAL
$(".tablas").on("click", ".btnEditarSucursal", function () {
  var idSucursal = $(this).attr("idSucursal");
  var datos = new FormData();
  datos.append("idSucursal", idSucursal);

  $.ajax({
    url: "ajax/sucursales.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#suc_id").val(respuesta["suc_id"]);
      $("#editar_nombre").val(respuesta["suc_nombre"]);
      $("#editar_direccion").val(respuesta["suc_direccion"]);
      $("#editar_activa").val(respuesta["suc_activa"]);
    },
  });
});

// ELIMINAR SUCURSAL
$(".tablas").on("click", ".btnEliminarSucursal", function () {
  var idSucursal = $(this).attr("idSucursal");

  Swal.fire({
    icon: "info",
    title: "¿Está seguro de borrar la sucursal?",
    text: "¡Si no lo está puede cancelar la acción!",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, borrar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?url=sucursales&idSucursal=" + encodeURIComponent(idSucursal);
    }
  });
});
