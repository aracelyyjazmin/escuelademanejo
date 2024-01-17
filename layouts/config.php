<?php
if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost');
}
if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', ''); // La contraseña para el usuario de MySQL, si es aplicable.
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'attex-php'); // El nombre de tu base de datos.
}

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Utilizar variables de entorno para las credenciales de Gmail
$gmailid = getenv('GMAIL_ID'); // Tu email de Gmail
$gmailpassword = getenv('GMAIL_PASSWORD'); // Tu contraseña de Gmail
$gmailusername = getenv('GMAIL_USERNAME'); // Tu nombre de usuario de Gmail
?>
