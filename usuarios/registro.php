<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <title>Registro</title>
</head>
<body class="registro-page">
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>

    <main>
        <section class="registro-hero">
            <div class="registro-hero-texto">
                <h1>Únete a Nosotros</h1>
            </div>
        </section>

        <div class="container">
            <div class="form-container">
                <h2 class="form-titulo">Crear Cuenta</h2>
                <form action="manejo_registro.php" method="POST">
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="juanperez@ejemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Nombre de Usuario:</label>
                        <input type="text" id="username" name="username" placeholder="Ej. JuanPerez11" minlength="3" maxlength="15" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" maxlength="12" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" minlength="6" maxlength="12" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Número de Teléfono:</label>
                        <input type="tel" id="phone" name="phone" placeholder="Ej. 8712631942" minlength="10" maxlength="10" required>
                    </div>
                    
                    <input type="submit" value="Registrar">
                </form>
                <p class="texto-enlace">¿Ya tienes una cuenta? <a href="login.html">Inicia sesión aquí.</a></p>
            </div>
        </div>
    </main>

    <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="../assets/img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="../assets/img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="../assets/img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
    <script src="../assets/js/validacion_registro.js"></script>
</body>
</html>