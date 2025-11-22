<?php
session_start();

if ($_SESSION['is_admin'] !== true) {
    // Si no ha iniciado sesión o no es admin, redirigir a la página principal.
    header("Location: ../index.php");
    exit(); // Es importante detener la ejecución del script después de redirigir.
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <title>Crear Usuario</title>
</head>
<body class="admin-page">

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <a href="../index.php"><img src="../assets/img/icons/logo.svg" alt="Logo"></a>
            <h1>Crear Nuevo Usuario</h1>
        </div>
        <ul class="nav-links">
            <li><a href="gestion_usuarios.php">Volver a Gestión</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container form-container main-content">
    <h2>Datos del Nuevo Usuario</h2>
    <form action="../usuarios/guardar_usuario.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>
        </div>
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
    </form>
</div>
        <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="../assets/img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="../assets/img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="../assets/img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>