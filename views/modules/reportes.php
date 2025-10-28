<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="" style="font-size: 25px;"><i class="fas fa-user"></i> Modulo Reportes</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body ">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                                <button type="button" class="btn btn-success d-md-block me-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarReporte"><i class="fas fa-user-plus"></i> Agregar Reportes</button>
                            </div>

                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap example2 tablas" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>

                                        <th>Responsable</th>
                                        <th>Fecha reporte</th>
                                        <th>Reporte</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $item = null;
                                    $valor = null;

                                    $reportes = ControllerReportes::ctrMostrarReportes($item, $valor);

                                    foreach ($reportes as $key => $value) {
                                        echo '<tr>
                                            <td>' . ($key + 1) . '</td>
                                            <td>' . $value["responsable"] . '</td>
                                            <td>' . date("H:i", strtotime($value["fecha_reporte"])) . ' ' . date("d-m-Y", strtotime($value["fecha_reporte"])) . '</td>
                                            <td>' . $value["descripcion_reporte"] . '</td>
                                           
                                            
                                           
                                            <td>
                                                <div class="btn-group">
                                                        <button class="btn btn-warning btn-sm btnConsultarReporte" reporte_id="' . $value["reporte_id"] . '" data-bs-toggle="modal" data-bs-target="#modalConsultarReporte"><i class="fas fa-pencil-alt"></i> Reporte</button>
                                                        <button class="btn btn-primary btn-sm btnEditarReporte" reporte_id="' . $value["reporte_id"] . '" data-bs-toggle="modal" data-bs-target="#modalEditarReporte"><i class="fas fa-pencil-alt"></i> Editar</button>                                                        
                                                        <button class="btn btn-danger btn-sm btnEliminarReporte" reporte_id="' . $value["reporte_id"] . '"><i class="fas fa-trash-alt"></i> Eliminar</button>
                                                </div>
                                            </td>
                                            ';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>

<div class="modal fade " id="modalAgregarReporte" tabindex="-1" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-file-alt"></i> Agregar Reporte</h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="responsable" class="form-label"><i class="fas fa-user"></i> Responsable</label>
                                <input type="text" class="form-control" value="<?php echo $_SESSION["nombre"]; ?>" name="responsable" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label"><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_reporte" class="form-label"><i class="fas fa-file-alt"></i> Descripción del Reporte</label>
                                <input type="text" class="form-control" name="descripcion_reporte" required>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="domicilio_reporte" class="form-label"><i class="fas fa-map-marker-alt"></i> Domicilio del Reporte</label>
                                <input type="text" class="form-control" name="domicilio_reporte" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label"><i class="fas fa-map-signs"></i> Dirección</label>
                                <select class="form-control" name="id_direccion" required>
                                    <option value="">Seleccione una dirección</option>
                                    <?php
                                    $item = null;
                                    $valor = null;

                                    $direcciones = ControllerDirecciones::ctrMostrarDirecciones($item, $valor);
                                    foreach ($direcciones as $key => $value) {
                                        echo '<option value="' . $value["id_direccion"] . '">' . $value["nombre_direccion"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_seccion" class="form-label"><i class="fas fa-list"></i> ID Sección</label>
                                <select class="form-control" name="id_seccion" required>
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $item = null;
                                    $valor = null;

                                    $secciones = ControllerSecciones::ctrMostrarSecciones($item, $valor);
                                    foreach ($secciones as $key => $value) {
                                        echo '<option value="' . $value["id_seccion"] . '">' . $value["seccion"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>

            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <?php
            $crearReporte = new ControllerReportes();
            $crearReporte->ctrGuardarReportes();
            ?>

            </form>



        </div>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div>


<div class="modal fade " id="modalEditarReporte" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-user-edit"></i> Editar Seccion </h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="reporte_id" name="reporte_id">

                                <label for="responsable" class="form-label"><i class="fas fa-user"></i> Responsable</label>
                                <input type="text" class="form-control" value="<?php echo $_SESSION["nombre"]; ?>" id="editar_responsable" name="editar_responsable" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label"><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="text" class="form-control" id="editar_telefono" name="editar_telefono" maxlength="10" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_reporte" class="form-label"><i class="fas fa-file-alt"></i> Descripción del Reporte</label>
                                <input type="text" class="form-control" id="editar_descripcion_reporte" name="editar_descripcion_reporte" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="domicilio_reporte" class="form-label"><i class="fas fa-map-marker-alt"></i> Domicilio del Reporte</label>
                                <input type="text" class="form-control" id="editar_domicilio_reporte" name="editar_domicilio_reporte" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label"><i class="fas fa-map-signs"></i> Dirección</label>
                                <select class="form-control" name="editar_id_direccion">
                                    <option id="editar_id_direccion"></option>
                                    <?php
                                    $item = null;
                                    $valor = null;

                                    $direcciones = ControllerDirecciones::ctrMostrarDirecciones($item, $valor);
                                    foreach ($direcciones as $key => $value) {
                                        echo '<option value="' . $value["id_direccion"] . '">' . $value["nombre_direccion"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_seccion" class="form-label"><i class="fas fa-list"></i>Sección</label>
                                <select class="form-control" name="editar_id_seccion" required>
                                    <option id="editar_id_seccion"></option>
                                    <?php

                                    $item = null;
                                    $valor = null;

                                    $secciones = ControllerSecciones::ctrMostrarSecciones($item, $valor);

                                    foreach ($secciones as $key => $value) {
                                        echo '<option value="' . $value["id_seccion"] . '">' . $value["seccion"] . '</option>';
                                    }
                                    ?>
                                </select>


                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    <?php
                    $editarReportes = new ControllerReportes();
                    $editarReportes->ctrEditarReportes();
                    ?>

                </form>



            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade " id="modalConsultarReporte" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel"><i class="fas fa-file-alt"></i> Reporte completo </h5>

                <hr>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="reporte_id" name="reporte_id">

                                <label for="responsable" class="form-label"><i class="fas fa-user"></i> Responsable</label>
                                <input type="text" class="form-control" value="<?php echo $_SESSION["nombre"]; ?>" id="consultar_responsable" name="consultar_responsable" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label"><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="text" class="form-control" id="consultar_telefono" name="consultar_telefono"  disabled>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_reporte" class="form-label"><i class="fas fa-file-alt"></i> Descripción del Reporte</label>
                                <input type="text" class="form-control" id="consultar_descripcion_reporte" name="consultar_descripcion_reporte" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="domicilio_reporte" class="form-label"><i class="fas fa-map-marker-alt"></i>Fecha reporte</label>
                                <input type="text" class="form-control" id="consultar_fecha_reporte" name="consultar_fecha_reporte" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="domicilio_reporte" class="form-label"><i class="fas fa-map-marker-alt"></i> Domicilio del Reporte</label>
                                <input type="text" class="form-control" id="consultar_domicilio_reporte" name="consultar_domicilio_reporte" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label"><i class="fas fa-map-signs"></i> Dirección</label>
                                <select class="form-control" name="consultar_id_direccion" disabled>
                                    <option id="consultar_id_direccion"></option>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_seccion" class="form-label"><i class="fas fa-list"></i>Sección</label>
                                <select class="form-control" name="consultar_id_seccion"  disabled>
                                    <option id="consultar_id_seccion"></option>

                                </select>


                            </div>

                        </div>


                    </div>


                </form>


            </div>

        </div>
    </div>
</div>

<?php

$borrarReportes = new ControllerReportes();
$borrarReportes->ctrEliminarReportes();

?>