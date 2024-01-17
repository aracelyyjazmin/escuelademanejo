<?php
include 'layouts/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['emailaddress']);
    $password = $conn->real_escape_string($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepara la sentencia para insertar los datos en la base de datos
    $stmt = $conn->prepare("INSERT INTO users (username, useremail, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Error en la preparación de la sentencia: " . $conn->error);
    }

    // Vincula los parámetros a la sentencia preparada
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    // Ejecuta la sentencia preparada
    if ($stmt->execute()) {
        // Cierra la sentencia y la conexión
        $stmt->close();
        $conn->close();

        // Redirige al usuario a la página de login
        header('Location: auth-login.php');
        exit; // Asegúrate de llamar a exit después de header()
    } else {
        echo "Error: " . $stmt->error;
    }

    // Es importante no cerrar la conexión si hay un error,
    // especialmente si vas a hacer más operaciones de base de datos para manejar el error.
}
?>
