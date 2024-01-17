<?php
// fetch_class_schedule.php
include 'layouts/config.php';

$sql = "SELECT pc.*, 
               e.nombre AS estudiante_nombre, 
               e.apellido AS estudiante_apellido,
               l.categoria_licencia AS categoria_postular, 
               v.tipo_vehiculo, 
               i.nombres AS instructor_nombre, 
               i.apellidos AS instructor_apellido,
               pc.fecha_clase,
               pc.hora_clase
        FROM programacion_clases pc
        LEFT JOIN estudiantes e ON pc.id_estudiante = e.id
        LEFT JOIN licencias l ON e.id_licencia_postula = l.id_licencia
        LEFT JOIN instructores i ON pc.id_instructor = i.id
        LEFT JOIN vehiculos v ON pc.id_vehiculo = v.id_vehiculo";
$resultado = $conn->query($sql);

// Luego puedes incluir este archivo en tu archivo principal donde se genera la tabla
?>