<?php
session_start();

require 'conexion_mysql.php';

$correo = $_POST['email'];
$contrasena = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usr_email = ? AND usr_contrasena = ?");
$stmt->bind_param("ss", $correo, $contrasena);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $_SESSION['usr_id'] = $usuario['usr_id'];
    $_SESSION['usr_nombre'] = $usuario['usr_nombre'];
    $_SESSION['usr_email'] = $usuario['usr_email'];
    echo "Inicio de sesión exitoso";

    if($usuario['usr_rol'] === 'admin') {
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['is_admin'] = false;
    }


} else {
    echo "Correo o contraseña incorrectos";
}
