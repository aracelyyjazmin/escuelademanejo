<?php 
include 'layouts/session.php';
include 'layouts/config.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['emailaddress'], FILTER_SANITIZE_EMAIL);
    $newPassword = $_POST['newpassword'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Formato de correo electrónico inválido";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE useremail = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: auth-login.php");
            exit();
        } else {
            $mensaje = "No se pudo actualizar la contraseña. Por favor, inténtalo de nuevo.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Restablecer Contraseña</title>
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
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="auth-login.php">
                                <span><img src="assets/images/logo3.png" alt="logo" height="80"></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Restablecer Contraseña</h4>
                                <p class="text-muted mb-4">Ingresa tu nueva contraseña a continuación</p>
                                <?php 
                                if (!empty($mensaje)) {
                                    echo "<p class='text-danger'>$mensaje</p>";
                                }
                                ?>
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Correo Electrónico</label>
                                    <input class="form-control" type="email" id="emailaddress" name="emailaddress" required placeholder="Ingresa tu correo electrónico">
                                </div>
                                <div class="mb-3">
                                    <label for="newpassword" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="newpassword" name="newpassword" class="form-control" placeholder="Ingresa tu nueva contraseña" required>
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit">Restablecer Contraseña</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted bg-body"><a href="auth-login.php" class="text-muted ms-1 link-offset-3 text-decoration-underline"><b>Iniciar Sesión</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt fw-medium">
        <span class="bg-body">
            <script>document.write(new Date().getFullYear())</script> © Escuela de manejo
        </span>
    </footer>

    <?php include 'layouts/footer-scripts.php'; ?>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
