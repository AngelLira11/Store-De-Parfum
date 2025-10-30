<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Perfil de Usuario</title>
</head>
<body class="perfil-page">
    <?php include("../backend/header.php"); ?>

    <main>
        <!-- Hero Section con fondo -->
        <section class="perfil-hero">
            <div class="perfil-hero-texto">
                <h1>Mi Perfil</h1>
            </div>
        </section>

        <!-- Mensajes de éxito o error -->
        <?php if(isset($_SESSION['mensaje_exito'])): ?>
            <div class="mensaje-exito">
                <?php 
                    echo $_SESSION['mensaje_exito']; 
                    unset($_SESSION['mensaje_exito']); // Eliminar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <!-- Contenedor principal -->
        <div class="container">
            <div class="perfil-content">
                <!-- Título de bienvenida -->
                <h2 class="perfil-titulo">Bienvenido, <?php echo htmlspecialchars($_SESSION['usr_nombre']); ?>!</h2>
                
                <!-- Información actual del usuario -->
                <p class="perfil-info">
                    <strong>Correo Electrónico:</strong> 
                    <?php echo htmlspecialchars($_SESSION['usr_email']); ?>
                </p>
                
                <p class="perfil-info">
                    <strong>Número de Teléfono:</strong> 
                    <?php echo htmlspecialchars($_SESSION['usr_telefono']); ?>
                </p>
                
                <!-- Formulario de edición -->
                <form action="../backend/actualizar_perfil.php" method="POST"> 
                    <label for="nuevo_email">Editar Correo Electrónico:</label>
                    <input type="email" id="nuevo_email" name="nuevo_email" placeholder="Nuevo correo electrónico">
                    
                    <label for="nuevo_telefono">Editar Número de Teléfono:</label>
                    <input type="text" id="nuevo_telefono" name="nuevo_telefono" placeholder="Nuevo teléfono">
                    
                    <label for="nueva_contrasena">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Nueva contraseña (dejar en blanco para no cambiar)">
                    
                    <button type="submit" class="btn-primario">Actualizar Perfil</button>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer con redes sociales -->
    <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>