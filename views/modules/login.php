<div class="d-flex justify-content-center align-items-center vh-100" >
    <div class="card"> <!-- Añadido w-50 para hacer el contenedor más amplio -->
        <div class="card-body" style="width: 20rem;">
            <h3 class="text-center mt-0 mb-3">
                <a href="index.html" class="logo"><img src="views\assets\images\logo.png" height="100" alt="logo-img"></a>
            </h3>
            <h5 class="text-center mt-0 text-color"><b>Ingreso al sistema</b></h5>

            <form class="form-horizontal mt-3 mx-3" method="post">

                <div class="form-group mb-3">
                    <div class="col-12">
                        <input class="form-control" type="text" required="" placeholder="usuario" name="usuario_login">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="col-12 position-relative">
                        <input class="form-control" type="password" required="" placeholder="contraseña" name="password_login" id="password">
                        <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-0" id="togglePassword" style="height: 100%;"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12">
                        <!-- <div class="checkbox checkbox-primary">
                            <input id="checkbox-signup" type="checkbox" checked="">
                            <label for="checkbox-signup" class="text-color">
                                Recordarme
                            </label>
                        </div> -->
                    </div>
                </div>

                <div class="form-group text-center mt-3">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block btn-lg waves-effect waves-light w-100" type="submit">
                            Iniciar sesión</button>
                    </div>
                </div>

                <div class="form-group row mt-4 mb-0">
                    <!-- <div class="col-sm-7">
                        <a href="auth-recoverpw.html" class="text-color">
                            <i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                    </div>
                    <div class="col-sm-5 text-right">
                        <a href="auth-register.html" class="text-color">Create an account</a>
                    </div> -->
                </div>
                <?php
                $ingreso =  new ControllerUsuarios();
                $ingreso->ctrIngresoUsuario();
                ?>
            </form>
        </div>
    </div>
</div>