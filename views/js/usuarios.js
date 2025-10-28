//EDITAR USUARIO
$(".tablas").on("click", ".btnEditarUsuario", function () {
  var idUsuario = $(this).attr("idUsuario");

  var datos = new FormData();
  datos.append("idUsuario", idUsuario);

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      $("#id").val(respuesta["id"]);
      $("#editar_nombre").val(respuesta["nombre"]);
      $("#editar_usuario").val(respuesta["usuario"]);
      $("#editar_perfil").html(respuesta["perfil"]);
      $("#editar_perfil").val(respuesta["perfil"]);
    },
  });
});

//ELIMINAR USUARIO

$(".tablas").on("click", ".btnEliminarUsuario", function () {
  var idUsuario = $(this).attr("idUsuario");

  Swal.fire({
    icon: "info",
    title: "¿Está seguro de borrar el usuario?",
    text: "¡Si no lo está puede cancelar la accion!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, borrar usuario!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?url=usuarios&idUsuario=" + idUsuario;
    }
  });
});

var togglePassword = document.getElementById("togglePassword");
if (togglePassword) {
  togglePassword.addEventListener("click", function () {
    var passwordField = document.getElementById("password");
    var passwordFieldType = passwordField.getAttribute("type");
    if (passwordFieldType == "password") {
      passwordField.setAttribute("type", "text");
      this.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
      passwordField.setAttribute("type", "password");
      this.innerHTML = '<i class="fas fa-eye"></i>';
    }
  });
}

//VALIDAR SI EXISTE USUARIO CURP

$("#curp_valido").change(function () {
  var curp = $(this).val();
  var datos = new FormData();
  datos.append("validarCURP", curp);

  $.ajax({
    url: "ajax/usuarios.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta) {
        Swal.fire({
          title: "¡Aviso!",
          text: "Este curp ya existe en la base de datos",
          icon: "warning",
       
        }).then((result) => {
          if (result.value) {
            window.location = "captura";
            $("#curp_valido").val("");
          }
        });
   
        
      } else {

        $(".nueva-captura").append(
          '<form id="formulario-registro">' +
              '<div class="row">' +
                  '<!-- Columna 1 -->' +
                  '<hr>'+
                  '<div class="col">' +
                 
                      '<div class="mb-3">' +
                          '<label for="apellido_paterno" class="form-label">Apellido Paterno</label>' +
                          '<input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>' +
                      '</div>' +
                      '<div class="mb-3">' +
                          '<label for="zona" class="form-label">Zona</label>' +
                          '<input type="text" class="form-control" id="zona" name="zona" required>' +
                      '</div>' +
                  '</div>' +
      
                  '<!-- Columna 2 -->' +
                  '<div class="col">' +
                      '<div class="mb-3">' +
                          '<label for="apellido_materno" class="form-label">Apellido Materno</label>' +
                          '<input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>' +
                      '</div>' +
                      '<div class="mb-3">' +
                          '<label for="colonia" class="form-label">Colonia</label>' +
                          '<input type="text" class="form-control" id="colonia" name="colonia" required>' +
                      '</div>' +
                  '</div>' +
      
                  '<!-- Columna 3 -->' +
                  '<div class="col">' +
                      '<div class="mb-3">' +
                          '<label for="nombre" class="form-label">Nombre</label>' +
                          '<input type="text" class="form-control" id="nombre" name="nombre" required>' +
                      '</div>' +
                      '<div class="mb-3">' +
                          '<label for="seccion" class="form-label">Sección</label>' +
                          '<input type="text" class="form-control" id="seccion" name="seccion" required>' +
                      '</div>' +
                  '</div>' +
      
                  '<!-- Columna 4 -->' +
                  '<div class="col">' +
                      '<div class="mb-3">' +
                          '<label for="curp" class="form-label">CURP</label>' +
                          '<input type="text" class="form-control" id="curp" name="curp" required>' +
                      '</div>' +
                      '<div class="mb-3">' +
                          '<label for="vigencia_credencial" class="form-label">Vigencia de Credencial</label>' +
                          '<input type="date" class="form-control" id="vigencia_credencial" name="vigencia_credencial" required>' +
                      '</div>' +
                  '</div>' +
      
                  '<!-- Columna 5 -->' +
                  '<div class="col">' +
                      '<div class="mb-3">' +
                          '<label for="telefono" class="form-label">Teléfono</label>' +
                          '<input type="tel" class="form-control" id="telefono" name="telefono" required>' +
                      '</div>' +
                      '<div class="mb-3">' +
                          '<label for="numero_casa" class="form-label">Número de Casa</label>' +
                          '<input type="text" class="form-control" id="numero_casa" name="numero_casa" required>' +
                      '</div>' +
                  '</div>' +
              '</div>' +
      
              '<!-- Botón de envío -->' +
              '<div class="d-grid mt-4">' +
                  '<button type="submit" class="btn btn-primary">Registrar</button>' +
              '</div>' +
          '</form>'
      );
      }
    },
  });
});


//CODIGO DROPZONE CSV
// Dropzone para cargar CSV
  const dropzone = document.getElementById('dropzoneCSV');
  const inputFile = document.getElementById('archivo_csv');
  const label = document.getElementById('fileLabel');

  dropzone.addEventListener('click', () => inputFile.click());

  dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('bg-primary', 'text-white');
  });

  dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('bg-primary', 'text-white');
  });

  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('bg-primary', 'text-white');
    inputFile.files = e.dataTransfer.files;
    label.innerText = inputFile.files[0].name;
  });

  inputFile.addEventListener('change', () => {
    if (inputFile.files.length > 0) {
      label.innerText = inputFile.files[0].name;
    }
  });

