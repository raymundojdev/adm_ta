//EDITAR USUARIO
$(".tablas").on("click", ".btnEditarSeccion", function () {

  var id_seccion = $(this).attr("id_seccion");

  var datos = new FormData();

  datos.append("id_seccion", id_seccion);


  $.ajax({
    url: "ajax/secciones.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#id_seccion").val(respuesta["id_seccion"]);
      $("#editar_seccion").val(respuesta["seccion"]);
    },
  });
});

//ELIMINAR USUARIO

$(".tablas").on("click", ".btnEliminarSeccion", function () {
  var id_seccion = $(this).attr("id_seccion");

  Swal.fire({
    icon: "info",
    title: "¿Está seguro de borrar la seccion?",
    text: "¡Si no lo está puede cancelar la accion!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, borrar Seccion!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      window.location =
        "index.php?url=secciones&id_seccion=" + id_seccion;
    }
  });
});
