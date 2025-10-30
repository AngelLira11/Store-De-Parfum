<?php
require 'conexion_mysql.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar datos
    $id = (int)$_POST['id'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $rol = $conn->real_escape_string($_POST['rol']);

    // Construir la consulta base
    $sql = "UPDATE usuarios SET usr_nombre='$nombre', usr_email='$email', usr_telefono='$telefono', usr_rol='$rol'";

    // Si se proporcionó una nueva contraseña, añadirla a la consulta
    if (!empty($_POST['contrasena'])) {
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        $sql .= ", usr_contrasena='$contrasena'";
    }

    // Añadir la condición WHEREpara actualizar solo el usuario correcto
    $sql .= " WHERE usr_id=$id";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        header("Location: ../frontend/gestion_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: ../frontend/gestion_usuarios.php");
    exit();
}
?>