<?php
// Incluye la configuración de la base de datos
include 'layouts/config.php';

// Inicia la sesión PHP
session_start();

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera y limpia los datos del formulario
    $email = $conn->real_escape_string($_POST['emailaddress']);
    $password = $_POST['password']; // La contraseña no necesita ser escapada

    // Prepara la consulta SQL para buscar el usuario
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE useremail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Cierra la sentencia
    $stmt->close();

    // Verifica si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Establece las variables de sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirige al usuario a la página principal
        header('Location: calendario.php');
        exit;
    } else {
        // Establece un mensaje de error y redirige al login
        $_SESSION['error'] = 'El correo electrónico o la contraseña no son correctos.';
        header('Location: auth-login.php');
        exit;
    }
} else {
    // Si no se accedió mediante un POST, redirige al formulario de inicio de sesión
    header('Location: auth-login.php');
    exit;
}

// Cierra la conexión a la base de datos
$conn->close();
?>
