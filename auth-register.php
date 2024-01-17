<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registro</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
</head>
<body class="authentication-bg">
    <?php include 'layouts/background.php'; ?>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <!-- Contenido del formulario -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Encabezado del formulario -->
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="auth-register.php">
                                <span><img src="assets/images/logo3.png" alt="logo" height="100"></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <!-- Contenido del formulario -->
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Crear cuenta</h4>
                                <p class="text-muted mb-4">¿No tienes una cuenta? Crea tu cuenta, te llevará menos de un minuto</p>
                            </div>
                            <form action="register.php" method="post">
                                <!-- Campos del formulario -->
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Nombre completo</label>
                                    <input class="form-control" type="text" id="fullname" name="fullname" placeholder="Ingrese su nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Correo electronico</label>
                                    <input class="form-control" type="email" id="emailaddress" name="emailaddress" required placeholder="Ingrese el correo electronico">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese la contraseña">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Botón de envío -->
                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit"> Crear cuenta </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Enlaces adicionales -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted bg-body">Tienes una cuenta?<a href="auth-login.php" class="text-muted ms-1 link-offset-3 text-decoration-underline"><b>Iniciar Sesion</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="footer footer-alt fw-medium">
        <span class="bg-body">
            <script>
                document.write(new Date().getFullYear())
            </script> © Alo Licencia de Conducir
        </span>
    </footer>

    <?php include 'layouts/footer-scripts.php'; ?>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
