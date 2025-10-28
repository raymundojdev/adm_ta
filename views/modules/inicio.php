<?php
// Asegúrate de tener cargados ControllerHome/ModelHome antes (ver mensaje anterior)
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="col-xl-12">
        <div class="card"><div class="card-body text-center">
          <div style="font-size:25px;"><i class="fas fa-home"></i> Contenido Homepage (home_content)</div>
        </div></div>
      </div>

      <div class="row"><div class="col-12"><div class="card"><div class="card-body">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="button" class="btn btn-success d-md-block mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarHome">
            <i class="fas fa-plus-circle"></i> Nuevo Contenido
          </button>
        </div>

        <table class="table table-striped table-bordered dt-responsive nowrap tablas" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>Slug</th>
              <th>SEO Title</th>
              <th>Activo</th>
              <th>Actualizado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $rows = ControllerHome::ctrMostrarHome(null, null);
            foreach ($rows as $i => $r) {
              echo '<tr>
                <td>'.($i+1).'</td>
                <td>'.htmlspecialchars($r["slug"]).'</td>
                <td>'.htmlspecialchars($r["seo_title"]).'</td>
                <td>'.($r["is_active"]?'✅':'⛔').'</td>
                <td>'.$r["updated_at"].'</td>
                <td>
                  <div class="btn-group">
                    <button class="btn btn-primary btn-sm btnEditarHome" data-id="'.$r["id"].'" data-bs-toggle="modal" data-bs-target="#modalEditarHome"><i class="fas fa-pencil-alt"></i> Editar</button>
                    <button class="btn btn-danger btn-sm btnEliminarHome" data-id="'.$r["id"].'"><i class="fas fa-trash-alt"></i> Eliminar</button>
                  </div>
                </td>
              </tr>';
            }
          ?>
          </tbody>
        </table>

      </div></div></div></div>
    </div>
  </div>
</div>

<!-- ============ MODAL AGREGAR ============ -->
<div class="modal fade" id="modalAgregarHome" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl"><div class="modal-content">
    <form method="post">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nuevo Contenido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <!-- META -->
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Slug</label><input class="form-control" name="slug" placeholder="inicio" required></div>
          <div class="col-md-4"><label class="form-label">SEO Title</label><input class="form-control" name="seo_title" required></div>
          <div class="col-md-4"><label class="form-label">SEO Description</label><input class="form-control" name="seo_desc"></div>
          <div class="col-md-6"><label class="form-label">OG Image (URL)</label><input class="form-control" name="og_image"></div>
          <div class="col-md-3"><label class="form-label">¿Activo?</label>
            <select class="form-control" name="is_active"><option value="1" selected>Sí</option><option value="0">No</option></select>
          </div>
        </div>
        <hr>

        <!-- HERO -->
        <h6><i class="fas fa-image"></i> Hero</h6>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Título</label><input class="form-control" name="hero_titulo"></div>
          <div class="col-md-6"><label class="form-label">Subtítulo</label><input class="form-control" name="hero_subtitulo"></div>
          <div class="col-md-6"><label class="form-label">Imagen de Fondo (URL)</label><input class="form-control" name="hero_imagen"></div>

          <div class="col-md-3"><label class="form-label">CTA1 Texto</label><input class="form-control" name="hero_cta1_texto"></div>
          <div class="col-md-3"><label class="form-label">CTA1 URL</label><input class="form-control" name="hero_cta1_url"></div>
          <div class="col-md-3"><label class="form-label">CTA2 Texto</label><input class="form-control" name="hero_cta2_texto"></div>
          <div class="col-md-3"><label class="form-label">CTA2 URL</label><input class="form-control" name="hero_cta2_url"></div>
        </div>
        <hr>

        <!-- ABOUT -->
        <h6><i class="fas fa-info-circle"></i> Sección About</h6>
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label">Badge</label><input class="form-control" name="about_badge" placeholder="DESDE 2010"></div>
          <div class="col-md-9"><label class="form-label">Título</label><input class="form-control" name="about_titulo"></div>
          <div class="col-md-9"><label class="form-label">Texto</label><textarea class="form-control" name="about_texto" rows="3"></textarea></div>
          <div class="col-md-3"><label class="form-label">Imagen (URL)</label><input class="form-control" name="about_imagen"></div>
        </div>
        <hr>

        <!-- FEATURES (repetidor) -->
        <h6><i class="fas fa-star"></i> Features</h6>
        <div id="featuresWrapper"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addFeature"><i class="fas fa-plus"></i> Agregar Feature</button>
        <hr>

        <!-- MÉTRICAS (repetidor) -->
        <h6><i class="fas fa-chart-line"></i> Métricas</h6>
        <div id="metricsWrapper"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addMetric"><i class="fas fa-plus"></i> Agregar Métrica</button>
        <hr>

        <!-- SERVICIOS (repetidor) -->
        <h6><i class="fas fa-list-ul"></i> Servicios</h6>
        <div id="servicesWrapper"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addService"><i class="fas fa-plus"></i> Agregar Servicio</button>
        <hr>

        <!-- TESTIMONIOS (repetidor) -->
        <h6><i class="fas fa-comment"></i> Testimonios</h6>
        <div id="testiWrapper"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addTesti"><i class="fas fa-plus"></i> Agregar Testimonio</button>

      </div><!-- modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>

      <?php
        $c = new ControllerHome();
        $c->ctrGuardarHome(); // arma JSON desde inputs simples
      ?>
    </form>
  </div></div>
</div>

<!-- ============ MODAL EDITAR ============ -->
<div class="modal fade" id="modalEditarHome" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl"><div class="modal-content">
    <form method="post">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-pencil-alt"></i> Editar Contenido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="id_home" name="id_home">

        <!-- META -->
        <div class="row g-3">
          <div class="col-md-4"><label class="form-label">Slug</label><input class="form-control" id="editar_slug" name="editar_slug" required></div>
          <div class="col-md-4"><label class="form-label">SEO Title</label><input class="form-control" id="editar_seo_title" name="editar_seo_title" required></div>
          <div class="col-md-4"><label class="form-label">SEO Description</label><input class="form-control" id="editar_seo_desc" name="editar_seo_desc"></div>
          <div class="col-md-6"><label class="form-label">OG Image (URL)</label><input class="form-control" id="editar_og_image" name="editar_og_image"></div>
          <div class="col-md-3"><label class="form-label">¿Activo?</label>
            <select class="form-control" id="editar_is_active" name="editar_is_active"><option value="1">Sí</option><option value="0">No</option></select>
          </div>
        </div>
        <hr>

        <!-- HERO -->
        <h6><i class="fas fa-image"></i> Hero</h6>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Título</label><input class="form-control" id="editar_hero_titulo" name="editar_hero_titulo"></div>
          <div class="col-md-6"><label class="form-label">Subtítulo</label><input class="form-control" id="editar_hero_subtitulo" name="editar_hero_subtitulo"></div>
          <div class="col-md-6"><label class="form-label">Imagen de Fondo (URL)</label><input class="form-control" id="editar_hero_imagen" name="editar_hero_imagen"></div>

          <div class="col-md-3"><label class="form-label">CTA1 Texto</label><input class="form-control" id="editar_hero_cta1_texto" name="editar_hero_cta1_texto"></div>
          <div class="col-md-3"><label class="form-label">CTA1 URL</label><input class="form-control" id="editar_hero_cta1_url" name="editar_hero_cta1_url"></div>
          <div class="col-md-3"><label class="form-label">CTA2 Texto</label><input class="form-control" id="editar_hero_cta2_texto" name="editar_hero_cta2_texto"></div>
          <div class="col-md-3"><label class="form-label">CTA2 URL</label><input class="form-control" id="editar_hero_cta2_url" name="editar_hero_cta2_url"></div>
        </div>
        <hr>

        <!-- ABOUT -->
        <h6><i class="fas fa-info-circle"></i> Sección About</h6>
        <div class="row g-3">
          <div class="col-md-3"><label class="form-label">Badge</label><input class="form-control" id="editar_about_badge" name="editar_about_badge"></div>
          <div class="col-md-9"><label class="form-label">Título</label><input class="form-control" id="editar_about_titulo" name="editar_about_titulo"></div>
          <div class="col-md-9"><label class="form-label">Texto</label><textarea class="form-control" id="editar_about_texto" name="editar_about_texto" rows="3"></textarea></div>
          <div class="col-md-3"><label class="form-label">Imagen (URL)</label><input class="form-control" id="editar_about_imagen" name="editar_about_imagen"></div>
        </div>
        <hr>

        <!-- Repetidores edición -->
        <h6><i class="fas fa-star"></i> Features</h6>
        <div id="featuresWrapperEdit"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addFeatureEdit"><i class="fas fa-plus"></i> Agregar Feature</button>
        <hr>

        <h6><i class="fas fa-chart-line"></i> Métricas</h6>
        <div id="metricsWrapperEdit"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addMetricEdit"><i class="fas fa-plus"></i> Agregar Métrica</button>
        <hr>

        <h6><i class="fas fa-list-ul"></i> Servicios</h6>
        <div id="servicesWrapperEdit"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addServiceEdit"><i class="fas fa-plus"></i> Agregar Servicio</button>
        <hr>

        <h6><i class="fas fa-comment"></i> Testimonios</h6>
        <div id="testiWrapperEdit"></div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addTestiEdit"><i class="fas fa-plus"></i> Agregar Testimonio</button>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>

      <?php
        $e = new ControllerHome();
        $e->ctrEditarHome(); // arma JSON desde inputs simples
      ?>
    </form>
  </div></div>
</div>

<?php
  $d = new ControllerHome();
  $d->ctrEliminarHome();
?>
