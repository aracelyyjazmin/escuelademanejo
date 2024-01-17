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
            $mail->setFrom('mariana2010mrv@gmail.com', 'Escuela de Manejo');
            $mail->addAddress($email); // Añade un destinatario

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de contraseña';
            $mail->Body = '
            <html>
            <head>
                <style>
                    .email-container {
                        font-family: Arial, sans-serif;
                        color: #333333;
                        max-width: 600px;
                        margin: auto;
                    }
                    .email-header {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px;
                        text-align: center;
                    }
                    .email-body {
                        padding: 20px;
                    }
                    .email-footer {
                        text-align: center;
                        margin-top: 20px;
                        font-size: 0.9em;
                    }
                    .btn-reset {
                        background-color: #008CBA;
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        <h2>Restablecimiento de Contraseña</h2>
                    </div>
                    <div class="email-body">
                        <p>Hola,</p>
                        <p>Has solicitado restablecer tu contraseña. Por favor, haz clic en el siguiente enlace para establecer una nueva contraseña:</p>
                        <a href="' . $linkDeRestablecimiento . '" class="btn-reset">Restablecer Contraseña</a>
                        <p>Si no solicitaste un restablecimiento de contraseña, ignora este correo.</p>
                    </div>
                    <div class="email-footer">
                        <p>Saludos,<br>Escuela de Manejo</p>
                    </div>
                </div>
            </body>
            </html>';
        
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
