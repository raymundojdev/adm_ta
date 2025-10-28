//EDITAR USUARIO
$(".tablas").on("click", ".btnEditarReporte", function () {

    var reporte_id = $(this).attr("reporte_id");
  
    var datos = new FormData();
  
    datos.append("reporte_id", reporte_id);
  
  
    $.ajax({
      url: "ajax/reportes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        $("#reporte_id").val(respuesta["reporte_id"]);
        $("#editar_responsable").val(respuesta["responsable"]);
        $("#editar_telefono").val(respuesta["telefono"]);
        $("#editar_descripcion_reporte").val(respuesta["descripcion_reporte"]);
        $("#editar_domicilio_reporte").val(respuesta["domicilio_reporte"]);

        $("#editar_id_direccion").val(respuesta["id_direccion"]);
        $("#editar_id_direccion").html(respuesta["nombre_direccion"]);       
        $("#editar_id_seccion").val(respuesta["id_seccion"]);
        $("#editar_id_seccion").html(respuesta["seccion"]);
        $("#editar_fecha_reporte").val(respuesta["fecha_reporte"]);


        

      },
    });
  });


  
  //ELIMINAR USUARIO
  
  
  


    //CONSULTAR USUARIO
$(".tablas").on("click", ".btnConsultarReporte", function () {

    var reporte_id = $(this).attr("reporte_id");
  
    var datos = new FormData();
  
    datos.append("reporte_id", reporte_id);
  
  
    $.ajax({
      url: "ajax/reportes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {

        console.log(respuesta);

        $("#reporte_id").val(respuesta["reporte_id"]);
        $("#consultar_responsable").val(respuesta["responsable"]);
        $("#consultar_telefono").val(respuesta["telefono"]);
        $("#consultar_descripcion_reporte").val(respuesta["descripcion_reporte"]);
        $("#consultar_domicilio_reporte").val(respuesta["domicilio_reporte"]);

        $("#consultar_id_direccion").val(respuesta["id_direccion"]);
        $("#consultar_id_direccion").html(respuesta["nombre_direccion"]);       
        $("#consultar_id_seccion").val(respuesta["id_seccion"]);
        $("#consultar_id_seccion").html(respuesta["seccion"]);
        $("#consultar_fecha_reporte").val(respuesta["fecha_reporte"]);
     

      },
    });
  });