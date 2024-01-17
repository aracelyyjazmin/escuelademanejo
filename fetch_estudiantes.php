<?php
include 'layouts/config.php'; // Incluye tu configuración de base de datos

$query = "SELECT e.*, 
                 l1.categoria_licencia AS licencia_actual, 
                 l2.categoria_licencia AS licencia_postula,
                 c.nombre_modulo,
                 (SELECT MIN(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_inicio,
                 (SELECT MAX(fecha_clase) FROM programacion_clases WHERE id_estudiante = e.id) AS fecha_termino
          FROM estudiantes e 
          LEFT JOIN licencias l1 ON e.id_licencia_actual = l1.id_licencia 
          LEFT JOIN licencias l2 ON e.id_licencia_postula = l2.id_licencia
          LEFT JOIN cursos c ON e.id_curso = c.id_curso";

          

$result = $conn->query($query);

$estudiantes = array();
while ($row = $result->fetch_assoc()) {
    $categoriaActual = $row['licencia_actual'];
    $categoriaPostula = $row['licencia_postula'];

    if ($categoriaActual === $categoriaPostula) {
        $row['horas_teoricas_requeridas'] = 15;
        $row['horas_practicas_requeridas'] = 8;
    } elseif ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC') {
        $row['horas_teoricas_requeridas'] = 50;
        $row['horas_practicas_requeridas'] = 50;
    } elseif (($categoriaActual === 'AI' && $categoriaPostula === 'AIIA') ||
            ($categoriaActual === 'AIIA' && $categoriaPostula === 'AIIB') ||
            ($categoriaActual === 'AIIB' && $categoriaPostula === 'AIIIA') ||
            ($categoriaActual === 'AIIIA' && $categoriaPostula === 'AIIIB') ||
            ($categoriaActual === 'AIIIB' && $categoriaPostula === 'AIIIC')) {
                $row['horas_teoricas_requeridas'] = 30;
                $row['horas_practicas_requeridas'] = 25;
                $row['nombre_modulo'] = 'Recategorización';
            } else {
    $row['nombre_modulo'] = 'N/A'; // O manejar según sea necesario
}

    $estudiantes[] = $row;
}

echo json_encode($estudiantes);
?>