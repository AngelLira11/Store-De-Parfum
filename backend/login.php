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
        $_SESSION['is_admin'] = ($usuario['usr_rol'] === 'admin') ? true : false; // Establecer rol de administrador si aplica
        echo "Inicio de sesión exit oso";
        header("Location: ../frontend/index.php");
        exit();
    } else {
        echo "Correo o contraseña incorrectos";
    }

} else {
    echo "Correo o contraseña incorrectos";
}

$stmt->close();
$conn->close();
