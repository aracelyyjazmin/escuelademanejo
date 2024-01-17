<?php
// Incluye el archivo de configuración de la base de datos y otras configuraciones
include 'layouts/config.php';

// Verifica si se ha recibido el ID del vehículo a editar
if (isset($_POST['vehiculo_id'])) {
    // Obtiene el ID del vehículo desde la solicitud POST
    $vehiculo_id = $_POST['vehiculo_id'];

    // Recupera los datos del vehículo de la base de datos utilizando el ID
    $sql = "SELECT * FROM vehiculos WHERE id_vehiculo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehiculo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // El vehículo se encontró en la base de datos
        $vehiculo = $result->fetch_assoc();

        // Aquí puedes mostrar un formulario prellenado con los datos actuales del vehículo
        // y permitir al usuario realizar modificaciones

        // Después de que el usuario envíe el formulario de edición
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recupera los valores actualizados del formulario
            $nuevo_tipo_vehiculo = $_POST['nuevo_tipo_vehiculo'];
            $nueva_placa = $_POST['nueva_placa'];
            // Agrega aquí más campos actualizados según tu estructura de base de datos

            // Actualiza el registro del vehículo en la base de datos
            $sql_actualizar = "UPDATE vehiculos SET tipo_vehiculo = ?, placa = ? WHERE id_vehiculo = ?";
            $stmt_actualizar = $conn->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("ssi", $nuevo_tipo_vehiculo, $nueva_placa, $vehiculo_id);

            if ($stmt_actualizar->execute()) {
                // Éxito al actualizar el vehículo
                echo "El vehículo se actualizó correctamente.";
            } else {
                // Error al actualizar el vehículo
                echo "Error al actualizar el vehículo: " . $conn->error;
            }
        }
    } else {
        echo "No se encontró el vehículo con el ID proporcionado.";
    }

    // Cierra la conexión a la base de datos
    $stmt->close();
    $stmt_actualizar->close();
} else {
    echo "No se proporcionó un ID de vehículo para editar.";
}
?>
