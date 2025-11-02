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
    <title>Compra Completada</title>
</head>
<body class="compra-completada-page">
    <?php include("../backend/header.php"); ?>

    <main>
        <section class="compra-completada-hero">
            <div class="compra-completada-hero-texto">
                <h1>¡Gracias por tu compra!</h1>
            </div>
        </section>

        <div class="container">
            <section class="compra-completada-mensaje">
                <h2 class="subtitulo"><span>Compra Completada</span></h2>
                <p>Hemos recibido tu pedido y estamos procesándolo. Pronto recibirás un correo electrónico con los detalles de tu compra y la información de envío.</p>
                <p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos a través de nuestra página de contacto.</p>
                <a href="catalogo.php" class="boton-volver">Volver al Catálogo</a>
            </section>
        </div>
    </main>
</body>
</html>