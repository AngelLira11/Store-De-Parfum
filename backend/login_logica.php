<?php
session_start();

require 'conexion_mysql.php';

$correo = $_POST['email'];
$contrasena = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usr_email = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc(); // Obtener los datos del usuario
    if(password_verify($contrasena, $usuario['usr_contrasena'])) { // Verificar la contraseña
        $_SESSION['usr_id'] = $usuario['usr_id'];
        $_SESSION['usr_nombre'] = $usuario['usr_nombre'];
        $_SESSION['usr_email'] = $usuario['usr_email'];
        $_SESSION['usr_telefono'] = $usuario['usr_telefono'];
        $_SESSION['is_admin'] = ($usuario['usr_rol'] === 'admin') ? true : false; // Establecer rol de administrador si aplica
        $_SESSION['mensaje_exito'] = "Inicio de sesión exitoso";
        header("Location: ../frontend/index.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = "Correo o contraseña incorrectos";
        header("Location: ../frontend/login.php");
        exit();
    }

} else {
    $_SESSION['mensaje_error'] = "Correo o contraseña incorrectos";
    header("Location: ../frontend/login.php");
    exit();
}

$stmt->close();
$conn->close();
