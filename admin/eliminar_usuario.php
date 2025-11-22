<?php
require '../config/conexion_mysql.php';

if (isset($_GET['id'])) {
    // Obtener y validar el ID del usuario
    $id = (int)$_GET['id'];

    if ($id > 0) {
        // Preparar la consulta para eliminar el usuario
        $sql = "DELETE FROM usuarios WHERE usr_id = $id";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            // Redirigir de vuelta a la página de gestión
            header("Location: gestion_usuarios.php");
            exit();
        } else {
            echo "Error al eliminar el usuario: " . $conn->error;
        }
    } else {
        echo "ID de usuario no válido.";
    }

    $conn->close();
} else {
    // Si no se proporciona un ID, redirigir
    header("Location: ../frontend/gestion_usuarios.php");
    exit();
}
?>