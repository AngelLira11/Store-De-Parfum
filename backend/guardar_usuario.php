<?php
// Incluye tu archivo de conexión
require 'conexion_mysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $rol = $conn->real_escape_string($_POST['rol']);
    
    // Hashear la contraseña para seguridad
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (usr_nombre, usr_email, usr_contrasena, usr_telefono, usr_rol) VALUES ('$nombre', '$email', '$contrasena', '$telefono', '$rol')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página de gestión si fue exitoso
        header("Location: ../frontend/gestion_usuarios.php");
        exit();
    } else {
        // Manejar el error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Si no es una solicitud POST, redirigir
    header("Location: ../frontend/crear_usuario.php");
    exit();
}
?>