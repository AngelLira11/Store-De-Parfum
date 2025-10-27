<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <title>Contacto</title>
</head>
<body class="contacto-page">
    <?php include("../backend/header.php"); ?>

    <main>
        <section class="contacto-hero">
            <div class="contacto-hero-texto">
                <h1>Contáctanos</h1>
            </div>
        </section>

        <div class="container">
            <section class="contacto">
                <div class="contacto-header">
                    <p class="subtitulo"><span>Contáctanos</span></p>
                    <h2 class="titulo">Estamos Aquí Para Ayudarte</h2>
                </div>

                <div class="form-container">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="nombre">Nombre Completo</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" required>
                        </div>

                        <div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" id="asunto" name="asunto" required>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Enviar Mensaje">
                        </div>
                    </form>
                </div>

                <div class="mapa-container">
                    <h3 class="mapa-titulo">Encuéntranos</h3>
                    <iframe title="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3600.123456789012!2d-103.4067!3d25.5428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDMyJzM0LjEiTiAxMDPCsDI0JzI0LjEiVw!5e0!3m2!1ses!2smx!4v1234567890123!5m2!1ses!2smx" allowfullscreen="" loading="lazy"></iframe>
                </div>

                <div class="telefono">
                    <p><strong>Teléfono:</strong> (871) 123-4567</p>
                    <p><strong>Email:</strong> contacto@tuempresa.com</p>
                    <p><strong>Horario:</strong> Lunes a Viernes de 9:00 AM - 6:00 PM</p>
                </div>
            </section>
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