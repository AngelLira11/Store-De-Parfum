<?php
session_start();

if ($_SESSION['is_admin'] !== true) {
    // Si no ha iniciado sesión o no es admin, redirigir a la página principal.
    header("Location: index.php");
    exit(); // Es importante detener la ejecución del script después de redirigir.
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <title>Panel de Administración</title>
</head>
<body class="admin-page">

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <img src="./img/icons/logo.svg" alt="Logo">
            <h1>Panel de Administración</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Volver a Tienda</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container">
    <div class="welcome-section">
        <h2>Bienvenido, Administrador</h2>
        <p>Gestiona tu tienda desde este panel de control</p>
    </div>

    <div class="dashboard-grid">
        <a href="gestion_usuarios.php" class="admin-card card-usuarios">
            <div class="icon">👥</div>
            <h3>Gestión de Usuarios</h3>
            <p>Administra los usuarios registrados en el sistema</p>
        </a>

        <!-- Lira aqui este boton no hace nada, para que tu le anadas -->
        <a href="modificar_productos.php" class="admin-card card-productos">
            <div class="icon">📦</div>
            <h3>Gestión de Productos</h3>
            <p>Alta, baja y modificación del inventario</p>
        </a>
    </div>
</div>

<footer class="redes-sociales">
    <div class="redes-container">
        <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
        <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
        <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
    </div>
</footer>

</body>
</html>

<!-- lira aqui haz lo q quieras para q pues el admin pueda hacer cosas de admin 
 ahi si quieres puedes hacer mas paginas o no se, tipo yo
 lo de la gestion de usuarios puse en el navbar
  nada mas fijate que las cosas logicas 
 las pongas en la carpeta de backend, y pues el diseño en frontend-->
