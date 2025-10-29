<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <title>Perfil de Usuario</title>
</head>
<body class = "perfil-page">
    <?php include("../backend/header.php"); ?>

    <main>
        <section class="perfil-hero">
            <div class="perfil-hero-texto">
                <h1>Mi Perfil</h1>
            </div>
        </section>

        <div class="container">
            <div class="perfil-content">
                <form action="../backend/actualizar_perfil.php" method="POST"> 
                    <h2 class="perfil-titulo">Bienvenido, <?php echo htmlspecialchars($_SESSION['usr_nombre']); ?>!</h2>
                    <p class="perfil-info"><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($_SESSION['usr_email']); ?></p>
                    <p class="perfil-info"><strong>Número de Teléfono:</strong> <?php echo htmlspecialchars($_SESSION['usr_telefono']); ?></p>
                    <!-- Aquí puedes agregar más información del perfil si es necesario -->
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
</body>
</html>