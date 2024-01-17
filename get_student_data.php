<?php
// Incluye tu archivo de configuración de base de datos
include 'layouts/config.php';

// Verifica si se ha recibido el ID del estudiante a editar
if (isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];

    // Consulta SQL para obtener los datos del estudiante con el ID proporcionado
    $sql = "SELECT * FROM estudiantes WHERE id_estudiante = ?";
    
    // Prepara la consulta
    $stmt = $conn->prepare($sql);

    // Vincula el parámetro
    $stmt->bind_param("i", $studentId);

    // Ejecuta la consulta y maneja los errores
    if ($stmt->execute()) {
        // Obtiene los resultados
        $result = $stmt->get_result();

        // Verifica si se encontraron resultados
        if ($result->num_rows === 1) {
            // Obtiene los datos del estudiante
            $row = $result->fetch_assoc();

            // Prepara los datos para ser devueltos como JSON
            $studentData = array(
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'tipo_documento' => $row['tipo_documento'],
                'numero_documento' => $row['numero_documento'],
                'telefono' => $row['telefono'],
                'email' => $row['email'],
                'categoria_actual' => $row['categoria_actual'],
                'categoria_postula' => $row['categoria_postula'],
                'grado_instruccion' => $row['grado_instruccion'],
                'fecha_nacimiento' => $row['fecha_nacimiento'],
                'domicilio' => $row['domicilio'],
                'proceso' => $row['proceso']
            );

            // Devuelve los datos como JSON
            header('Content-Type: application/json');
            echo json_encode($studentData);
        } else {
            // No se encontró un estudiante con el ID proporcionado
            echo 'Estudiante no encontrado';
        }
    } else {
        // Manejo de error en la ejecución de la consulta
        echo 'Error al ejecutar la consulta: ' . mysqli_error($conn);
    }

    // Cierra la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // No se proporcionó el ID del estudiante
    echo 'ID de estudiante no proporcionado';
}
?>
