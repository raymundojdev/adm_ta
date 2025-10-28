function featureRow(prefix=""){
    return `
    <div class="row g-2 align-items-end feature-item">
      <div class="col-md-3">
        <label class="form-label">Título</label>
        <input class="form-control" name="${prefix}feature_titulo[]" />
      </div>
      <div class="col-md-4">
        <label class="form-label">Descripción</label>
        <input class="form-control" name="${prefix}feature_desc[]" />
      </div>
      <div class="col-md-3">
        <label class="form-label">Icono (clase FA)</label>
        <input class="form-control" name="${prefix}feature_icono[]" placeholder="fa-solid fa-briefcase"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">URL</label>
        <div class="input-group">
          <input class="form-control" name="${prefix}feature_url[]" />
          <button type="button" class="btn btn-outline-danger removeRow"><i class="fas fa-times"></i></button>
        </div>
      </div>
    </div>`;
  }
  function metricRow(prefix=""){
    return `
    <div class="row g-2 align-items-end metric-item">
      <div class="col-md-3">
        <label class="form-label">Número</label>
        <input class="form-control" name="${prefix}metric_numero[]" placeholder="1833+"/>
      </div>
      <div class="col-md-7">
        <label class="form-label">Etiqueta</label>
        <input class="form-control" name="${prefix}metric_label[]" placeholder="Vacantes cubiertas"/>
      </div>
      <div class="col-md-2">
        <label class="form-label"> </label>
        <div><button type="button" class="btn btn-outline-danger removeRow w-100"><i class="fas fa-times"></i></button></div>
      </div>
    </div>`;
  }
  function serviceRow(prefix=""){
    return `
    <div class="row g-2 align-items-end service-item">
      <div class="col-md-5">
        <label class="form-label">Título</label>
        <input class="form-control" name="${prefix}service_titulo[]"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Icono (clase FA)</label>
        <input class="form-control" name="${prefix}service_icono[]" placeholder="fa-solid fa-user-check"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">URL</label>
        <input class="form-control" name="${prefix}service_url[]"/>
      </div>
      <div class="col-md-2">
        <label class="form-label"> </label>
        <div><button type="button" class="btn btn-outline-danger removeRow w-100"><i class="fas fa-times"></i></button></div>
      </div>
    </div>`;
  }
  function testiRow(prefix=""){
    return `
    <div class="row g-2 align-items-end testi-item">
      <div class="col-md-5">
        <label class="form-label">Cita</label>
        <input class="form-control" name="${prefix}testi_cita[]"/>
      </div>
      <div class="col-md-3">
        <label class="form-label">Autor</label>
        <input class="form-control" name="${prefix}testi_autor[]"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">Cargo</label>
        <input class="form-control" name="${prefix}testi_cargo[]"/>
      </div>
      <div class="col-md-2">
        <label class="form-label">Foto (URL)</label>
        <div class="input-group">
          <input class="form-control" name="${prefix}testi_foto[]"/>
          <button type="button" class="btn btn-outline-danger removeRow"><i class="fas fa-times"></i></button>
        </div>
      </div>
    </div>`;
  }
  
  // Inicialización en modal AGREGAR
  $("#modalAgregarHome").on("shown.bs.modal", function(){
    const fW = $("#featuresWrapper");
    const mW = $("#metricsWrapper");
    const sW = $("#servicesWrapper");
    const tW = $("#testiWrapper");
    if(!fW.children().length){ fW.append(featureRow()); fW.append(featureRow()); fW.append(featureRow()); }
    if(!mW.children().length){ mW.append(metricRow()); mW.append(metricRow()); mW.append(metricRow()); mW.append(metricRow()); }
    if(!sW.children().length){ for(let i=0;i<6;i++) sW.append(serviceRow()); }
    if(!tW.children().length){ tW.append(testiRow()); tW.append(testiRow()); }
  });
  
  $("#addFeature").on("click", ()=> $("#featuresWrapper").append(featureRow()));
  $("#addMetric").on("click", ()=> $("#metricsWrapper").append(metricRow()));
  $("#addService").on("click", ()=> $("#servicesWrapper").append(serviceRow()));
  $("#addTesti").on("click", ()=> $("#testiWrapper").append(testiRow()));
  
  $(document).on("click",".removeRow", function(){ $(this).closest(".row").remove(); });
  
  // EDITAR (llenado dinámico con prefijo 'editar_')
  $(".tablas").on("click",".btnEditarHome", function(){
    const id = $(this).data("id");
    const datos = new FormData();
    datos.append("idHome", id);
  
    $.ajax({
      url: "ajax/home.ajax.php",
      method: "POST",
      data: datos,
      cache: false, contentType: false, processData: false, dataType: "json",
      success: function(res){
        // Campos base
        $("#id_home").val(res.id);
        $("#editar_slug").val(res.slug);
        $("#editar_seo_title").val(res.seo_title);
        $("#editar_seo_desc").val(res.seo_desc);
        $("#editar_og_image").val(res.og_image);
        $("#editar_is_active").val(res.is_active);
  
        // Hero/About
        $("#editar_hero_titulo").val(res.hero_titulo||"");
        $("#editar_hero_subtitulo").val(res.hero_subtitulo||"");
        $("#editar_hero_imagen").val(res.hero_imagen||"");
        $("#editar_hero_cta1_texto").val(res.hero_cta1_texto||"");
        $("#editar_hero_cta1_url").val(res.hero_cta1_url||"");
        $("#editar_hero_cta2_texto").val(res.hero_cta2_texto||"");
        $("#editar_hero_cta2_url").val(res.hero_cta2_url||"");
  
        $("#editar_about_badge").val(res.about_badge||"");
        $("#editar_about_titulo").val(res.about_titulo||"");
        $("#editar_about_texto").val(res.about_texto||"");
        $("#editar_about_imagen").val(res.about_imagen||"");
  
        // Repetidores
        const fW = $("#featuresWrapperEdit").empty();
        (res.features||[]).forEach(it=>{
          const row = $(featureRow("editar_"));
          row.find('[name="editar_feature_titulo[]"]').val(it.titulo||"");
          row.find('[name="editar_feature_desc[]"]').val(it.descripcion||"");
          row.find('[name="editar_feature_icono[]"]').val(it.icono||"");
          row.find('[name="editar_feature_url[]"]').val(it.url||"");
          fW.append(row);
        });
        const mW = $("#metricsWrapperEdit").empty();
        (res.metrics||[]).forEach(it=>{
          const row = $(metricRow("editar_"));
          row.find('[name="editar_metric_numero[]"]').val(it.numero||"");
          row.find('[name="editar_metric_label[]"]').val(it.label||"");
          mW.append(row);
        });
        const sW = $("#servicesWrapperEdit").empty();
        (res.services||[]).forEach(it=>{
          const row = $(serviceRow("editar_"));
          row.find('[name="editar_service_titulo[]"]').val(it.titulo||"");
          row.find('[name="editar_service_icono[]"]').val(it.icono||"");
          row.find('[name="editar_service_url[]"]').val(it.url||"");
          sW.append(row);
        });
        const tW = $("#testiWrapperEdit").empty();
        (res.testimonials||[]).forEach(it=>{
          const row = $(testiRow("editar_"));
          row.find('[name="editar_testi_cita[]"]').val(it.cita||"");
          row.find('[name="editar_testi_autor[]"]').val(it.autor||"");
          row.find('[name="editar_testi_cargo[]"]').val(it.cargo||"");
          row.find('[name="editar_testi_foto[]"]').val(it.foto||"");
          tW.append(row);
        });
      }
    });
  });
  
  $(".tablas").on("click",".btnEliminarHome", function(){
    const id = $(this).data("id");
    Swal.fire({
      icon:"info", title:"¿Eliminar este contenido?", text:"Esta acción no se puede deshacer.",
      showCancelButton:true, confirmButtonColor:"#3085d6", cancelButtonColor:"#d33",
      confirmButtonText:"Sí, eliminar", cancelButtonText:"Cancelar"
    }).then(r=>{
      if(r.value){ window.location = "index.php?url=home-content&idHome=" + encodeURIComponent(id); }
    });
  });
  
  // Botones de agregar filas en modal editar
  $("#addFeatureEdit").on("click", ()=> $("#featuresWrapperEdit").append(featureRow("editar_")));
  $("#addMetricEdit").on("click",  ()=> $("#metricsWrapperEdit").append(metricRow("editar_")));
  $("#addServiceEdit").on("click", ()=> $("#servicesWrapperEdit").append(serviceRow("editar_")));
  $("#addTestiEdit").on("click",   ()=> $("#testiWrapperEdit").append(testiRow("editar_")));
  