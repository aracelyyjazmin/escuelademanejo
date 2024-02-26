<?php
require 'vendor/autoload.php'; // Asegúrate de que esta ruta es correcta
include 'layouts/session.php'; // Asumiendo que aquí se establece la conexión a la base de datos
include 'layouts/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['emailaddress']; // Valida y limpia esta entrada

    // Preparar la consulta para verificar si el correo electrónico existe
    $stmt = $conn->prepare("SELECT * FROM users WHERE useremail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Genera un token seguro
        $linkDeRestablecimiento = "localhost/Escuela/escuelademanejo/reset_password.php?token=" . $token;

        // Actualiza el token en la base de datos para este usuario
        $updateStmt = $conn->prepare("UPDATE users SET token = ? WHERE useremail = ?");
        $updateStmt->bind_param("ss", $token, $email);
        $updateStmt->execute();

        // Configuración de PHPMailer para Gmail
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fundamentocalculo553@gmail.com'; // Tu dirección de Gmail
            $mail->Password = 'mlhrjqkkdrswquat'; // Tu contraseña de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('mariana2010mrv@gmail.com', 'Escuela de Manejo - Alo licencia de conducir');
            $mail->addAddress($email); // Añade un destinatario

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de contraseña';
            $mail->Body = '
            <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecimiento de Contraseña</title>
    <style>
        .email-container {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #d32f2f; /* Rojo */
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid #b71c1c; /* Rojo oscuro */
        }
        .email-body {
            padding: 20px;
            line-height: 1.5;
            background-color: #fff; /* Blanco */
            text-align: center; /* Centrar texto y botones */
        }
        .email-footer {
            background-color: #f2f2f2; /* Gris claro */
            color: #333;
            text-align: center;
            padding: 10px;
            font-size: 0.9em;
            border-top: 1px solid #ddd;
        }
        .btn-reset {
            background-color: #b71c1c; /* Rojo oscuro */
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-reset:hover {
            background-color: #d32f2f; /* Rojo */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        /* Centrar el botón */
        .btn-container {
            text-align: center; /* Centra el contenedor del botón */
        }
    </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1>Restablecimiento de Contraseña</h1>
                </div>
                <div class="email-body"> 
                    <p>Hola,</p>
                    <p>Has solicitado restablecer tu contraseña. Por favor, haz clic en el siguiente enlace para establecer una nueva contraseña:</p>
                    <div class="btn-container">
                        <a href="' . $linkDeRestablecimiento . '" class="btn-reset">Restablecer Contraseña</a>
                    </div>
                    <p>Si no solicitaste un restablecimiento de contraseña, ignora este correo.</p>
                </div>
                <div class="email-footer">
                    <p>Saludos cordiales,<br><strong>Escuela de Manejo</strong></p>
                </div>
            </div>
        </body>
        </html>
        ';
        
            $mail->send();
            $_SESSION['email_sent_to'] = $email;
            header("Location: auth-confirm-mail.php");
            exit();

        } catch (Exception $e) {
            echo "Error al enviar el correo de restablecimiento: {$mail->ErrorInfo}";
        }
    } else {
        echo "El correo electrónico proporcionado no existe en nuestros registros.";
    }
    $stmt->close();
    $updateStmt->close();
    $conn->close();
}
?>
