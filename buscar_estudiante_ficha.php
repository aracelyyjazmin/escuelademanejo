<?php
// buscar_estudiante.php
include 'layouts/config.php'; // Incluye la configuración de la base de datos

// Verifica que se haya proporcionado un DNI a través de la petición GET
if (isset($_GET['numero']) && trim($_GET['numero']) !== '') {
    $dni = $_GET['numero'];

    // Prepara la consulta SQL para buscar al estudiante por su DNI
    $query = "SELECT e.*, 
                     l1.categoria_licencia AS licencia_actual, 
                     l2.categoria_licencia AS licencia_postula,
                     c.nombre_modulo,
                     (SELECT MIN(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_inicio,
                     (SELECT MAX(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_termino
              FROM estudiantes e 
              LEFT JOIN licencias l1 ON e.id_licencia_actual = l1.id_licencia 
              LEFT JOIN licencias l2 ON e.id_licencia_postula = l2.id_licencia
              LEFT JOIN cursos c ON e.id_curso = c.id_curso
              WHERE e.numero = ?";

    // Prepara la declaración para la base de datos
    if ($stmt = $conn->prepare($query)) {
        // Vincula el parámetro 'numero' al marcador de posición en la consulta SQL
        $stmt->bind_param("s", $dni);
        $stmt->execute();

        // Obtiene los resultados de la consulta
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Si se encontró el estudiante, envía la información como JSON
            echo json_encode($row);
        } else {
            // Si no se encuentra ningún estudiante, envía un mensaje de error
            echo json_encode(array("error" => "Estudiante no encontrado."));
        }

        // Cierra la declaración preparada
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Error al preparar la consulta."));
    }
} else {
    // Si no se ha proporcionado un DNI, envía un mensaje de error
    echo json_encode(array("error" => "No se proporcionó el DNI."));
}

// Cierra la conexión a la base de datos
$conn->close();
?>
