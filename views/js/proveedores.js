// views/assets/js/proveedores.js
// Requiere jQuery y SweetAlert2. No usa JSON en formularios: envía FormData tradicional.

// Helpers
function renderFilaProveedor(p) {
  const badge = p.estatus === "ACTIVO"
    ? '<span class="badge bg-success">ACTIVO</span>'
    : '<span class="badge bg-secondary">INACTIVO</span>';

  // Botones de acción con el estilo solicitado (atributos data-* y $(this).data("id"))
  const btnEditar = `
    <button class="btn btn-primary btn-sm btnEditarProveedor" data-id="${p.id}" data-bs-toggle="modal" data-bs-target="#modalProveedor">
      <i class="fas fa-pencil-alt"></i> Editar
    </button>`;

  const btnToggle = (p.estatus === "ACTIVO")
    ? `<button class="btn btn-warning btn-sm btnDesactivarProveedor" data-id="${p.id}">
         <i class="fas fa-ban"></i> Desactivar
       </button>`
    : `<button class="btn btn-success btn-sm btnActivarProveedor" data-id="${p.id}">
         <i class="fas fa-check"></i> Activar
       </button>`;

  // Si deseas permitir eliminar físico:
  const btnEliminar = `
    <button class="btn btn-danger btn-sm btnEliminarProveedor" data-id="${p.id}">
      <i class="fas fa-trash-alt"></i> Eliminar
    </button>`;

  return `
    <tr>
      <td>${p.nombre ?? ""}</td>
      <td>${p.telefono ?? ""}</td>
      <td>${p.email ?? ""}</td>
      <td>${p.rfc ?? ""}</td>
      <td>${p.direccion ?? ""}</td>
      <td>${badge}</td>
      <td>${p.fecha_alta ?? ""}</td>
      <td class="d-flex gap-1 flex-wrap">
        ${btnEditar}
        ${btnToggle}
        ${btnEliminar}
      </td>
    </tr>`;
}

function cargarTabla() {
  const q = $("#busquedaProveedor").val() || "";
  const estatus = $("#filtroEstatus").val() || "";

  $.ajax({
    url: PROV_AJAX_URL + "?accion=listar&q=" + encodeURIComponent(q) + (estatus ? "&estatus=" + encodeURIComponent(estatus) : ""),
    method: "GET",
    dataType: "json",
    success: function(resp){
      if (!resp.ok) { Swal.fire("Error", resp.msg || "No se pudo obtener el listado", "error"); return; }
      const tbody = $("#tablaProveedores tbody");
      tbody.empty();
      (resp.data || []).forEach(p => tbody.append(renderFilaProveedor(p)));
    },
    error: function(){
      Swal.fire("Error", "No se pudo conectar con el servidor", "error");
    }
  });
}

function limpiarFormulario() {
  $("#prov_id").val("");
  $("#prov_nombre").val("");
  $("#prov_telefono").val("");
  $("#prov_email").val("");
  $("#prov_rfc").val("");
  $("#prov_direccion").val("");
  $("#prov_estatus").val("ACTIVO");
  $("#modalProveedorLabel").text("Agregar proveedor");
}

// Listeners
$(document).ready(function(){
  cargarTabla();

  $("#btnFiltrarProveedor").on("click", function(){ cargarTabla(); });

  $("#busquedaProveedor").on("keyup", function(e){
    if (e.key === "Enter") cargarTabla();
  });

  $("#modalProveedor").on("show.bs.modal", function(e){
    const btn = $(e.relatedTarget);
    if (btn && btn.hasClass("btnEditarProveedor")) {
      $("#modalProveedorLabel").text("Editar proveedor");
      const id = btn.data("id");
      $.ajax({
        url: PROV_AJAX_URL + "?accion=obtener&id=" + encodeURIComponent(id),
        method: "GET",
        dataType: "json",
        success: function(resp){
          if (!resp.ok) { Swal.fire("Error", resp.msg || "No encontrado", "error"); return; }
          const p = resp.data;
          $("#prov_id").val(p.id);
          $("#prov_nombre").val(p.nombre);
          $("#prov_telefono").val(p.telefono);
          $("#prov_email").val(p.email);
          $("#prov_rfc").val(p.rfc);
          $("#prov_direccion").val(p.direccion);
          $("#prov_estatus").val(p.estatus);
        },
        error: function(){ Swal.fire("Error", "No se pudo obtener el proveedor", "error"); }
      });
    } else {
      limpiarFormulario();
    }
  });

  $("#formProveedor").on("submit", function(e){
    e.preventDefault();
    const form = this;
    const fd = new FormData(form);
    const id = $("#prov_id").val();
    const accion = id ? "actualizar" : "crear";

    $.ajax({
      url: PROV_AJAX_URL + "?accion=" + encodeURIComponent(accion),
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(resp){
        if (resp.ok) {
          Swal.fire("Éxito", resp.msg || "Guardado correctamente", "success");
          $("#modalProveedor").modal("hide");
          cargarTabla();
          form.reset();
        } else {
          Swal.fire("Atención", resp.msg || "Revisa los datos", "warning");
        }
      },
      error: function(){
        Swal.fire("Error", "No se pudo guardar", "error");
      }
    });
  });

  $(document).on("click", ".btnDesactivarProveedor", function(){
    const id = $(this).data("id");
    Swal.fire({
      title: "Desactivar proveedor",
      text: "¿Deseas desactivarlo? Podrás activarlo después.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, desactivar",
      cancelButtonText: "Cancelar"
    }).then((r)=>{
      if (r.isConfirmed) {
        const fd = new FormData();
        fd.append("id", id);
        $.ajax({
          url: PROV_AJAX_URL + "?accion=desactivar",
          method: "POST",
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(resp){
            if (resp.ok) { Swal.fire("Listo", resp.msg, "success"); cargarTabla(); }
            else { Swal.fire("Atención", resp.msg, "warning"); }
          },
          error: function(){ Swal.fire("Error", "Operación fallida", "error"); }
        });
      }
    });
  });

  $(document).on("click", ".btnActivarProveedor", function(){
    const id = $(this).data("id");
    const fd = new FormData(); fd.append("id", id);
    $.ajax({
      url: PROV_AJAX_URL + "?accion=activar",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(resp){
        if (resp.ok) { Swal.fire("Listo", resp.msg, "success"); cargarTabla(); }
        else { Swal.fire("Atención", resp.msg, "warning"); }
      },
      error: function(){ Swal.fire("Error", "Operación fallida", "error"); }
    });
  });

  $(document).on("click", ".btnEliminarProveedor", function(){
    const id = $(this).data("id");
    Swal.fire({
      title: "Eliminar proveedor",
      text: "Esta acción es irreversible. ¿Deseas continuar?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then((r)=>{
      if (r.isConfirmed) {
        // Nota: usa encodeURIComponent en URLs si agregas parámetros adicionales
        const fd = new FormData(); fd.append("id", id);
        $.ajax({
          url: PROV_AJAX_URL + "?accion=eliminar",
          method: "POST",
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json",
          success: function(resp){
            if (resp.ok) { Swal.fire("Eliminado", resp.msg, "success"); cargarTabla(); }
            else { Swal.fire("Atención", resp.msg, "warning"); }
          },
          error: function(){ Swal.fire("Error", "Operación fallida", "error"); }
        });
      }
    });
  });

});
