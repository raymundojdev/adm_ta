// views/js/puntos.js
/* =========================================================================
   PUNTOS (LEALTAD) - JS
   ========================================================================= */

// Al cambiar cliente en "Agregar", consultar saldo por AJAX
$(document).on("change", "#cli_id", function(){
  var cli = $(this).val();
  if(!cli) return;
  var datos = new FormData();
  datos.append("idCliente", cli);

  $.ajax({
    url: "ajax/puntos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(r){
      $("#saldoCliente").text((r && r.saldo!==undefined) ? r.saldo : 0);
    }
  });
});

// EDITAR: carga movimiento y abre modal
$(document).on("click", ".btnEditarMovimiento", function(){
  var id = $(this).attr("idMovimiento");
  if(!id) return;

  var datos = new FormData();
  datos.append("idMovimiento", id);

  $.ajax({
    url: "ajax/puntos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(r){
      if(!r || !r.pmv_id){ Swal.fire({icon:"error", title:"No se encontró"}); return; }

      $("#pmv_id").val(r.pmv_id);
      $("#editar_cli_id").val(r.cli_id);
      $("#editar_suc_id").val(r.suc_id);
      $("#editar_ped_id").val(r.ped_id || "");
      $("#editar_pmv_tipo").val(r.pmv_tipo);
      $("#editar_pmv_puntos").val(r.pmv_puntos);
      $("#editar_pmv_nota").val(r.pmv_nota || "");

      var el = document.getElementById("modalEditarMovimiento");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show(); // (OPT) abrimos tras poblar inputs para evitar aria-hidden issues
    },
    error: function(xhr){
      console.error(xhr.responseText);
      Swal.fire({icon:"error", title:"Error al cargar"});
    }
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarMovimiento", function(){
  var id = $(this).attr("idMovimiento");
  if(!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar movimiento?",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r)=>{
    if(r.isConfirmed){
      window.location = "index.php?url=puntos&idMovimiento=" + encodeURIComponent(id);
    }
  });
});
