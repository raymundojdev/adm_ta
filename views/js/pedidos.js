/* =========================================================================
   PEDIDOS - JS
   ========================================================================= */

// Utils: calcular subtotales y total
function recalcTotal(scope) {
  var total = 0;
  $(scope)
    .find(".fila-detalle")
    .each(function () {
      var qty = parseInt($(this).find(".pde_cantidad").val() || "0", 10);
      var prc = parseFloat($(this).find(".pde_precio").val() || "0");
      var sub = qty * prc;
      $(this).find(".pde_subtotal").val(sub.toFixed(2));
      total += sub;
    });
  $(scope)
    .find(".pedido_total")
    .text("$" + total.toFixed(2));
}

function addRow(scope, data) {
  var tpl =
    '<tr class="fila-detalle">' +
    "<td>" +
    '<select name="{pro_name}[]" class="form-select pro_id" required>' +
    window.__productosOptions + // se llena en la vista desde PHP
    "</select>" +
    "</td>" +
    '<td><input type="number" min="1" step="1" name="{cant_name}[]" class="form-control pde_cantidad" value="{cant}" required></td>' +
    '<td><input type="number" min="0" step="0.01" name="{prec_name}[]" class="form-control pde_precio" value="{prec}" required></td>' +
    '<td><input type="text" class="form-control pde_subtotal" value="{sub}" readonly></td>' +
    '<td><button type="button" class="btn btn-outline-danger btn-sm btnQuitarFila"><i class="fas fa-times"></i></button></td>' +
    "</tr>";

  var html = tpl
    .replace("{pro_name}", data.pro_name)
    .replace("{cant_name}", data.cant_name)
    .replace("{prec_name}", data.prec_name)
    .replace("{cant}", data.cant || 1)
    .replace("{prec}", data.prec || 0)
    .replace("{sub}", ((data.cant || 1) * (data.prec || 0)).toFixed(2));

  var $row = $(html);
  // Seleccionar producto si viene
  if (data.pro_id) $row.find(".pro_id").val(data.pro_id);

  $(scope).find("tbody.detalles-body").append($row);
  recalcTotal(scope);
}

// Agregar fila (Nuevo)
$(document).on("click", ".btnAddFilaNuevo", function () {
  var scope = $("#modalAgregarPedido");
  addRow(scope, {
    pro_name: "pro_id",
    cant_name: "pde_cantidad",
    prec_name: "pde_precio",
    cant: 1,
    prec: 0,
  });
});

// Agregar fila (Editar)
$(document).on("click", ".btnAddFilaEditar", function () {
  var scope = $("#modalEditarPedido");
  addRow(scope, {
    pro_name: "editar_pro_id",
    cant_name: "editar_pde_cantidad",
    prec_name: "editar_pde_precio",
    cant: 1,
    prec: 0,
  });
});

// Quitar fila
$(document).on("click", ".btnQuitarFila", function () {
  var scope = $(this).closest(".modal");
  $(this).closest(".fila-detalle").remove();
  recalcTotal(scope);
});

// Recalcular al cambiar cantidad/precio
$(document).on("input", ".pde_cantidad, .pde_precio", function () {
  var scope = $(this).closest(".modal");
  recalcTotal(scope);
});

// EDITAR: trae encabezado + detalles y abre modal
$(document).on("click", ".btnEditarPedido", function () {
  var id = $(this).attr("idPedido");
  if (!id) return;

  var datos = new FormData();
  datos.append("idPedido", id);

  $.ajax({
    url: "ajax/pedidos.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (res) {
      var p = res.pedido || {};
      var dets = res.detalles || [];

      // Encabezado
      $("#ped_id").val(p.ped_id);
      $("#editar_folio").val(p.ped_folio);
      $("#editar_tipo").val(p.ped_tipo);
      $("#editar_estado").val(p.ped_estado);
      $("#editar_suc_id").val(p.suc_id);
      $("#editar_cli_id").val(p.cli_id);

      // Detalles
      var scope = $("#modalEditarPedido");
      var $tbody = scope.find("tbody.detalles-body");
      $tbody.empty();
      dets.forEach(function (d) {
        addRow(scope, {
          pro_name: "editar_pro_id",
          cant_name: "editar_pde_cantidad",
          prec_name: "editar_pde_precio",
          pro_id: d.pro_id,
          cant: d.pde_cantidad,
          prec: d.pde_precio,
        });
      });
      recalcTotal(scope);

      var el = document.getElementById("modalEditarPedido");
      var modal = bootstrap.Modal.getOrCreateInstance(el);
      modal.show();
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon: "error", title: "Error al cargar pedido" });
    },
  });
});

// ELIMINAR
$(document).on("click", ".btnEliminarPedido", function () {
  var id = $(this).attr("idPedido");
  if (!id) return;

  Swal.fire({
    icon: "warning",
    title: "¿Eliminar pedido?",
    text: "Se eliminará el pedido y sus detalles.",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((r) => {
    if (r.isConfirmed) {
      window.location =
        "index.php?url=pedidos&idPedido=" + encodeURIComponent(id);
    }
  });
});
