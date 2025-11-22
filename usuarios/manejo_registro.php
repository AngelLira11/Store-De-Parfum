<?php
session_start();

require '../config/conexion_mysql.php';

// Obtener datos del formulario
$correo = $_POST['email'];
$usuario = $_POST['username'];
$contrasena = $_POST['password'];
$confirmar_contrasena = $_POST['confirm_password'];
$telefono = $_POST['phone'];

// Consulta para verificar si el usuario o correo ya existen
$stmt_select = $conn->prepare("SELECT * FROM usuarios WHERE usr_email = ? OR usr_nombre = ?");
$stmt_select->bind_param("ss", $correo, $usuario);

// Hash de la contraseña y definición del rol (Por default es usuario)
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
$rol = 'usuario';

// Sentence para insertar el nuevo usuario
$stmt_insert = $conn->prepare("INSERT INTO usuarios (usr_email, usr_nombre, usr_contrasena, usr_telefono, usr_rol) VALUES (?, ?, ?, ?, ?)");
$stmt_insert->bind_param("sssss", $correo, $usuario, $contrasena_hash, $telefono, $rol);

// Validaciones de la contraseña y existencia del usuario
if($contrasena !== $confirmar_contrasena) {
    echo "Las contraseñas no coinciden.";
    exit;
}
if($stmt_select->execute()) {
    $resultado = $stmt_select->get_result();
    if($resultado->num_rows > 0) {
        echo "El correo electrónico o nombre de usuario ya están en uso.";
        exit;
    }
} else {
    echo "Error al verificar el usuario existente.";
    exit;
}

$stmt_insert->execute(); // Insertar el nuevo usuario

if($stmt_insert->affected_rows > 0) {
    echo "Registro exitoso. Ahora puedes iniciar sesión.";
} else {
    echo "Error al registrar el usuario.";
}
