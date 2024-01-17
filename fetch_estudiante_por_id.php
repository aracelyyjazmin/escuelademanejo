<?php
include 'layouts/config.php'; // Asegúrate de que esta línea apunte a tu archivo de configuración real

// Verifica que se haya enviado un ID de estudiante
if (isset($_GET['id'])) {
    $estudianteId = $_GET['id'];

    // Prepara la consulta SQL para obtener los datos del estudiante
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
              WHERE e.id = ?";

    // Prepara la declaración para la base de datos
    if ($stmt = $conn->prepare($query)) {
        // Vincula el parámetro 'id' al marcador de posición en la consulta SQL
        $stmt->bind_param("i", $estudianteId);
        $stmt->execute();

        // Obtiene los resultados de la consulta
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Si se encontró el estudiante, ajusta la lógica para establecer las horas requeridas
            $categoriaActual = $row['licencia_actual'];
            $categoriaPostula = $row['licencia_postula'];

            if ($categoriaActual === $categoriaPostula) {
                $row['horas_teoricas_requeridas'] = 15;
                $row['horas_practicas_requeridas'] = 8;
            } elseif ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC') {
                $row['horas_teoricas_requeridas'] = 50;
                $row['horas_practicas_requeridas'] = 50;
            } elseif (
                ($categoriaActual === 'AI' && $categoriaPostula === 'AIIA') ||
                ($categoriaActual === 'AIIA' && $categoriaPostula === 'AIIB') ||
                ($categoriaActual === 'AIIB' && $categoriaPostula === 'AIIIA') ||
                ($categoriaActual === 'AIIIA' && $categoriaPostula === 'AIIIB') ||
                ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC')
            ) {
                $row['horas_teoricas_requeridas'] = 30;
                $row['horas_practicas_requeridas'] = 25;
                $row['nombre_modulo'] = 'Recategorización';
            } else {
                $row['nombre_modulo'] = 'N/A'; // O manejar según sea necesario
            }

            // Envía la información como JSON
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
    // Si no se ha proporcionado un ID, envía un mensaje de error
    echo json_encode(array("error" => "No se proporcionó el ID del estudiante."));
}

// Cierra la conexión a la base de datos
$conn->close();
?>
