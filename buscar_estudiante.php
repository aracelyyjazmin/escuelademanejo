<?php
include 'layouts/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['success' => false, 'html' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['dni'])) {
    $dni = $conn->real_escape_string($_POST['dni']);
    
    // Preparar y ejecutar la consulta utilizando el campo 'numero' para el DNI
    $stmt = $conn->prepare("SELECT nombre, apellido, documento, numero, telefono, grado_instruccion, fecha_nacimiento, domicilio FROM estudiantes WHERE numero = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $estudiante = $result->fetch_assoc();
        $response['success'] = true;
        $response['html'] = "<p>Nombre: {$estudiante['nombre']} {$estudiante['apellido']}</p>
                             <p>Tipo de Documento: {$estudiante['documento']}</p>
                             <p>DNI: {$estudiante['numero']}</p>
                             <p>Grado de Instrucción: {$estudiante['grado_instruccion']}</p>
                             <p>Fecha de Nacimiento: {$estudiante['fecha_nacimiento']}</p>
                             <p>Número de Celular: {$estudiante['telefono']}</p>
                             <p>Domicilio: {$estudiante['domicilio']}</p>";
    } else {
        $response['html'] = 'Estudiante no encontrado.';
    }
    
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
