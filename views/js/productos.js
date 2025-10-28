/* ============================================
   views/assets/js/productos.js
   ============================================ */

// Utils
function escapeHtml(s){return String(s??"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;");}
function money(v){const n=Number(v||0); return n.toFixed(2);}
function badgeActivo(v){return parseInt(v)===1?'<span class="badge bg-success">Sí</span>':'<span class="badge bg-secondary">No</span>';}
function spinner(){return '<span class="spinner-border spinner-border-sm"></span>';}

// Render
function filaProducto(p, idx){
  return `
    <tr data-row-id="${p.id}">
      <td>${idx}</td>
      <td>${escapeHtml(p.nombre)}</td>
      <td>${escapeHtml(p.unidad_abrev ?? "")}</td>
      <td>${money(p.costo_ref)}</td>
      <td>${escapeHtml(p.proveedor_nombre ?? "")}</td>
      <td>${badgeActivo(p.activo)}</td>
      <td>
        <div class="btn-group">
          <button class="btn btn-primary btn-sm btnEditarProducto" idProducto="${p.id}" data-bs-toggle="modal" data-bs-target="#modalEditarProducto">
            <i class="fas fa-pencil-alt"></i> Editar
          </button>
          <button class="btn btn-danger btn-sm btnEliminarProducto" idProducto="${p.id}">
            <i class="fas fa-trash-alt"></i> Eliminar
          </button>
        </div>
      </td>
    </tr>`;
}

function renderPaginacion(page, pages){
  let html = `<nav><ul class="pagination pagination-sm mb-0">`;
  const prevDis = page<=1 ? "disabled" : "";
  const nextDis = page>=pages ? "disabled" : "";
  html += `<li class="page-item ${prevDis}"><a class="page-link" href="#" data-page="${page-1}">«</a></li>`;
  const start = Math.max(1, page-2);
  const end   = Math.min(pages, page+2);
  for(let i=start;i<=end;i++){
    html += `<li class="page-item ${i===page?'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  html += `<li class="page-item ${nextDis}"><a class="page-link" href="#" data-page="${page+1}">»</a></li>`;
  html += `</ul></nav>`;
  $("#productosPagination").html(html);
}

// Estado
const state = { page:1, per_page:20, busqueda:"", activo:"", proveedor_id:"" };
let debounceTimer = null;

// Cargar lista
function cargarLista(){
  const tbody = $("#tablaProductos tbody");
  tbody.html(`<tr><td colspan="7" class="text-center py-3">${spinner()} Cargando...</td></tr>`);

  const fd = new FormData();
  fd.append("__action","listar");
  fd.append("page", state.page);
  fd.append("per_page", state.per_page);
  fd.append("busqueda", state.busqueda);
  fd.append("activo", state.activo);
  fd.append("proveedor_id", state.proveedor_id);

  $.ajax({
    url: "ajax/productos.ajax.php",
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(resp){
      if(!resp.ok){ tbody.html(`<tr><td colspan="7" class="text-center text-danger">No se pudo cargar</td></tr>`); return; }
      tbody.empty();
      const startIndex = (resp.page-1)*resp.per_page;
      (resp.data||[]).forEach((p, i)=> tbody.append(filaProducto(p, startIndex+i+1)));
      renderPaginacion(resp.page, resp.pages);
      $("#totalProductosBadge").text(resp.total);
    },
    error: function(){
      tbody.html(`<tr><td colspan="7" class="text-center text-danger">Error de conexión</td></tr>`);
    }
  });
}

$(document).ready(function(){

  // Filtros
  $("#busquedaProducto").on("input", function(){
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(()=>{
      state.busqueda = this.value.trim();
      state.page = 1;
      cargarLista();
    }, 250);
  });

  $("#filtroActivo, #filtroProveedor, #perPage").on("change", function(){
    if(this.id==="perPage") state.per_page = parseInt(this.value,10)||20;
    if(this.id==="filtroActivo") state.activo = this.value;
    if(this.id==="filtroProveedor") state.proveedor_id = this.value;
    state.page = 1;
    cargarLista();
  });

  $("#productosPagination").on("click", ".page-link", function(e){
    e.preventDefault();
    const p = parseInt($(this).data("page"),10);
    if(!isNaN(p) && p>=1){ state.page = p; cargarLista(); }
  });

  // Obtener para editar
  $(".tablas").on("click", ".btnEditarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    const fd = new FormData();
    fd.append("__action","obtener");
    fd.append("idProducto", idProducto);

    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (r) {
        if(!r){ return; }
        $("#idProducto").val(r["id"]);
        $("#editar_prod_nombre").val(r["nombre"]);
        $("#editar_prod_unidad_id").val(r["unidad_id"]);
        $("#editar_prod_costo_ref").val(r["costo_ref"]);
        $("#editar_prod_proveedor_id").val(r["proveedor_id"] ?? "");
        $("#editar_prod_activo").prop("checked", parseInt(r["activo"])===1);
      }
    });
  });

  // Crear
  $("#formAgregarProducto").on("submit", function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append("__action","crear");

    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(resp){
        if(resp.ok){
          $("#modalAgregarProducto").modal("hide");
          $("#formAgregarProducto")[0].reset();
          Swal.fire("Éxito", resp.msg || "Producto creado","success");
          state.page = 1;
          cargarLista();
        }else{
          Swal.fire("Atención", resp.msg || "No se pudo crear","warning");
        }
      },
      error: function(){ Swal.fire("Error","Conexión fallida","error"); }
    });
  });

  // Actualizar
  $("#formEditarProducto").on("submit", function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append("__action","actualizar");

    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(resp){
        if(resp.ok){
          $("#modalEditarProducto").modal("hide");
          Swal.fire("Listo", resp.msg || "Producto actualizado","success");
          cargarLista();
        }else{
          Swal.fire("Atención", resp.msg || "No se pudo actualizar","warning");
        }
      },
      error: function(){ Swal.fire("Error","Conexión fallida","error"); }
    });
  });

  // Eliminar
  $(".tablas").on("click", ".btnEliminarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    Swal.fire({
      icon: "warning", title:"¿Eliminar producto?", text:"Acción irreversible",
      showCancelButton:true, confirmButtonText:"Sí, eliminar", cancelButtonText:"Cancelar"
    }).then((r)=>{
      if(r.value){
        const fd = new FormData();
        fd.append("__action","eliminar");
        fd.append("idProducto", idProducto);

        $.ajax({
          url: "ajax/productos.ajax.php",
          method: "POST",
          data: fd, processData:false, contentType:false, dataType:"json",
          success: function(resp){
            if(resp.ok){
              Swal.fire("Eliminado", resp.msg || "OK","success");
              cargarLista();
            }else{
              Swal.fire("Atención", resp.msg || "No se pudo eliminar","warning");
            }
          },
          error: function(){ Swal.fire("Error","Conexión fallida","error"); }
        });
      }
    });
  });

  // Primera carga
  cargarLista();
});
