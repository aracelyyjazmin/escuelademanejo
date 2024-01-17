<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>

<?php
// Inicia la sesión y prepara la variable de mensaje de error
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Borra el error para futuras peticiones
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Iniciar Sesion</title>
    <?php include 'layouts/title-meta.php'; ?>
    <?php include 'layouts/head-css.php'; ?>
</head>

<body class="authentication-bg position-relative">

<?php include 'layouts/background.php'; ?>

<!-- Aquí puedes colocar el mensaje de error justo antes del contenedor principal o donde tenga más sentido visualmente -->
<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">


                        <!-- Logo -->
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="auth-login.php">
                                <span><img src="assets/images/logo3.png" alt="logo" height="100"></span>
                            
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Iniciar Sesion</h4>
                            </div>

                            <form action="auth-validate.php" method="post">


                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Correo electronico</label>
                                    <input class="form-control" type="email" name="emailaddress" id="emailaddress" required="" placeholder="Enter your email">
                                </div>

                                <div class="mb-3">
                                    <a href="auth-recoverpw.php" class="text-muted float-end fs-12">Olvidaste tu contraseña?</a>
                                    <label for="password" class="form-label">Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                        <label class="form-check-label" for="checkbox-signin">Recuerdame</label>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Ingresar </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted bg-body">No tienes una cuenta? <a href="auth-register.php" class="text-muted ms-1 link-offset-3 text-decoration-underline"><b>Crear cuenta</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="bg-body">
            <script>
                document.write(new Date().getFullYear())
            </script> © Alo Licencias de Conducir
        </span>
    </footer>
    <?php include 'layouts/footer-scripts.php'; ?>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>