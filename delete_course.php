<?php
// delete_course.php
include 'layouts/config.php';

$id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;

if ($id_curso > 0) {
    $stmt = $conn->prepare("DELETE FROM cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $result = $stmt->execute();
    
    if ($result) {
        echo "Curso eliminado con éxito.";
    } else {
        echo "Error al eliminar el curso.";
    }
    
    $stmt->close();
}

$conn->close();
?>
