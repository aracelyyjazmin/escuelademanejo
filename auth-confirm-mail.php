<?php include 'layouts/session.php'; ?>
<?php include 'layouts/main.php'; ?>

<head>
    <title>Confirmar correo</title>
    <?php include 'layouts/title-meta.php'; ?>

    <?php include 'layouts/head-css.php'; ?>
</head>

<body class="authentication-bg">

    <?php include 'layouts/background.php'; ?>

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

                            <div class="text-center m-auto">
                                <img src="assets/images/svg/mail_sent.svg" alt="mail sent image" height="64" />
                                <h4 class="text-dark-50 text-center mt-4 fw-bold">Por favor revisar su correo electronico</h4>
                                <p class="text-muted mb-4">
                                Se ha enviado un correo electrónico a <b><?php echo $_SESSION['email_sent_to'] ?? 'youremail@domain.com'; ?></b>.
                                Verifique si hay un correo electrónico y haga clic en el enlace incluido para restablecer su contraseña.
    
                                </p>
                            </div>

                            <form action="auth-login.php">
                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"><i class="ri-home-4-line me-1"></i>Regresar al inicio</button>
                                </div>
                            </form>

                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card-->

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
            </script> © Alo Licencia de Conducir
        </span>
    </footer>
    <?php include 'layouts/footer-scripts.php'; ?>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>