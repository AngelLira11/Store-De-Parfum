<?php
session_start();
require 'conexion_mysql.php';

$usr_id = $_SESSION['usr_id'];
$nuevo_email = $_POST['nuevo_email'];
$nuevo_telefono = $_POST['nuevo_telefono'];
$nueva_contrasena = $_POST['nueva_contrasena'];
$campos_a_actualizar = [];
$parametros = [];

if (!empty($nuevo_email)) {
    $campos_a_actualizar[] = "usr_email = ?";
    $parametros[] = $nuevo_email;
} 
if (!empty($nuevo_telefono)) {
    $campos_a_actualizar[] = "usr_telefono = ?";
    $parametros[] = $nuevo_telefono;
}
if (!empty($nueva_contrasena)) {
    $hashed_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
    $campos_a_actualizar[] = "usr_contrasena = ?";
    $parametros[] = $hashed_password;
}

if (count($campos_a_actualizar) > 0) {
    $parametros[] = $usr_id; 
    $sql = "UPDATE usuarios SET " . implode(", ", $campos_a_actualizar) . " WHERE usr_id = ?";
    $stmt = $conn->prepare($sql);
    
    $tipos = str_repeat("s", count($parametros) - 1) . "i"; 
    $stmt->bind_param($tipos, ...$parametros);
    
    if ($stmt->execute()) {
        if (!empty($nuevo_email)) {
            $_SESSION['usr_email'] = $nuevo_email;
        }
        if (!empty($nuevo_telefono)) {
            $_SESSION['usr_telefono'] = $nuevo_telefono;
        }
        $_SESSION['mensaje_exito'] = "Perfil actualizado correctamente";
    } else {
        $_SESSION['mensaje_error'] = "Error al actualizar el perfil";
    }
    
    $stmt->close();
} else {
    $_SESSION['mensaje_error'] = "No se proporcionaron datos para actualizar";
}

$conn->close();
header("Location: ../frontend/perfil.php");
exit();
