<?php
// get_course_data.php
include 'layouts/config.php';

$id_curso = isset($_GET['id_curso']) ? intval($_GET['id_curso']) : 0;

if ($id_curso > 0) {
    $stmt = $conn->prepare("SELECT * FROM cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();
    $curso = $result->fetch_assoc();
    
    echo json_encode($curso);
    $stmt->close();
}

$conn->close();
?>
