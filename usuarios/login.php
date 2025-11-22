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
    <title>Inicio de Sesión</title>
</head>
<body class="registro-page">
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>

    <main>
        <section class="login-hero">
            <div class="login-hero-texto">
                <h1>Inicio de Sesión</h1>
            </div>
        </section>
        
    <?php if(isset($_SESSION['mensaje_error'])): ?>
        <div class="mensaje-error">
            <?php 
                echo $_SESSION['mensaje_error']; 
                unset($_SESSION['mensaje_error']); // Eliminar el mensaje después de mostrarlo
            ?>
        </div>
    <?php endif; ?>        

        <div class="container">
            <div class="form-container">
                <h2 class="form-titulo">Iniciar Sesión</h2>
                <form action="login_logica.php" method="POST">
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" placeholder="juanperez@ejemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" maxlength="12" required>
                        <div style="margin-top: 10px;">
                            <input type="checkbox" id="togglePassword"> <label for="togglePassword" style="display:inline; font-weight:normal; font-size: 0.9rem;">Mostrar contraseña</label>
                        </div>
                    </div>                    
                    <input type="submit" value="Iniciar Sesión">
                </form>
            </div>
        </div>
    </main>

    <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
    <script src="../assets/js/validacion_login.js"></script>
</body>
</html>