//EDITAR USUARIO
$(".tablas").on("click", ".btnEditarDireccion", function () {

    var id_direccion = $(this).attr("id_direccion");
  
    var datos = new FormData();
  
    datos.append("id_direccion", id_direccion);
  
  
    $.ajax({
      url: "ajax/direcciones.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $("#id_direccion").val(respuesta["id_direccion"]);
        $("#editar_direccion").val(respuesta["nombre_direccion"]);
      },
    });
  });
  
  //ELIMINAR USUARIO
  
  $(".tablas").on("click", ".btnEliminarDireccion", function () {
    var id_direccion = $(this).attr("id_direccion");
  
    Swal.fire({
      icon: "info",
      title: "¿Está seguro de borrar la direccion?",
      text: "¡Si no lo está puede cancelar la accion!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "¡Si, borrar Direccion!",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        window.location =
          "index.php?url=direcciones&id_direccion=" + id_direccion;
      }
    });
  });
  