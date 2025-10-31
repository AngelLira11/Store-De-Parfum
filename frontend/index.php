<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Store de Parfum</title>
	<link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
	<div class="contenido-header">
		<div class="fondo" id="fondo">
			<h1 class="texto">STORE DE´ PARFUM</h1>
		</div>
	</div>

	<?php include("../backend/header.php"); ?>

	<main class="container">
		<div class="nosotros">
			<div class="nosotros-content">
				<h2 class="subtitulo"><span>¿Quienes somos?</span></h2>
				<h3 class="titulo">Nuestra pasión por satisfacer necesidades y expresar la esencia.</h3>
				<p>
Ofrecer una amplia gama de perfumes y productos relacionados que satisfagan las necesidades de los clientes, ayudándoles a encontrar fragancias que los identifiquen, les transmitan seguridad y les permitan expresar su individualidad. 				</p>
				<a href="catalogo.html" class="enlace">Descubre tu próxima esencia</a>
			</div>
		</div>

		<div class="productos">
			<article class="productos-header">
				<h2 class="subtitulo"><span>Lo que ofrecemos</span></h2>
				<p class="titulo">LO MAS VENDIDO</p>
				<p>Lo mas top de nuestra colección</p>
				<p>Productos que no pasan desapercibidos... y tu tampoco cuando los usas.</p>
			</article>

			<div class="productos-grid">
				<article class="producto-item">
					<figure class="producto">
						<img src="img/products/jpg elixir.webp" alt="">
						<figcaption class="overlay">
							<p class="overlay-texto">JPG ELIXIR</p>
						</figcaption>
					</figure>
				</article>

				<article class="producto-item">
					<figure class="producto">
						<img src="img/products/acqua di gio.png" alt="">
						<figcaption class="overlay">
							<p class="overlay-texto">ACQUA DI GIO</p>
						</figcaption>
					</figure>
				</article>

				<article class="producto-item">
					<figure class="producto">
						<img src="img/products/dolce and gabbana.webp" alt="">
						<figcaption class="overlay">
							<p class="overlay-texto">DLOCE & GABBANA</p>
						</figcaption>
					</figure>
				</article>

				<article class="producto-item">
					<figure class="producto">
						<img src="img/products/nautica.webp" alt="">
						<figcaption class="overlay">
							<p class="overlay-texto">NAUTICA VOLLAGE</p>
						</figcaption>
					</figure>
				</article>

		<a href="catalogo.php">
			<button class="btn-productos">Todos los productos</button>
		</a>			
		</div>
		</div>
	</main>

	<div class="separador">
		<p class="texto-separador"><q>DESCUBRE NUESTRAS MARCAS DE LUJO</q></p>
	</div>

	<div class="container">
		<div class="acerca-de">
			<article class="servicio-item">
				<figure>
					<img src="img/icons/icon-team.png" alt="">
					<figcaption>
						<p>
							<strong>Un equipo de expertos</strong><br>
							Probamos, seleccionamos y te recomendamos solo lo mejor para que encuentres tu aroma ideal,
							porque sabemos que un perfume no solo se usa... se siente.
						</p>
					</figcaption>
				</figure>
			</article>

			<article class="servicio-item">
				<figure>
					<img src="img/icons/icon-services.png" alt="">
					<figcaption>
						<p>
							<strong>Una historia de servicio</strong><br>
							En STORE DE' PARFUM creemos que cada cliente merece una experiencia única desde el empaque hasta la atención,
							cuidamos que cada detalle para que siempre quieras volver.
						</p>
					</figcaption>
				</figure>
			</article>
		</div>
	</div>

	<div class="galeria">
		<div class="galeria-grid">
			<div class="galeria-item">
				<img src="img/products/sauvage.webp" alt="">
			</div>
			<div class="galeria-item">
				<img src="img/products/stronger.webp" alt="">
			</div>
			<div class="galeria-item">
				<img src="img/products/valentino1.avif" alt="">
			</div>
		</div>
	</div>

	<div class="contacto-container">
		<section class="contacto">
			<div class="contacto-header">
				<h2 class="subtitulo"><span>Contactanos</span></h2>
			</div>

			<div class="mapa-container">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3682917.4013375365!2d-104.279862830412!3d25.651430347039724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8662bdeccae67f15%3A0x450eca15e8896f2d!2sEl%20Palacio%20de%20Hierro%20Monterrey!5e0!3m2!1ses-419!2smx!4v1759708316440!5m2!1ses-419!2smx" allowfullscreen="" loading="lazy" title="Ubicación"></iframe>
			</div>
			
			<div class="telefono">
				<p>
					<img src="img/icons/icon-cellphone.png" alt="">Tel: (871) 123-4567
				</p>
			</div>
		</section>

		<footer class="redes-sociales">
			<div class="redes-container">
				<a href="#" title="Facebook"><img src="img/icons/facebook.png" alt="Facebook"></a>
				<a href="#" title="Twitter"><img src="img/icons/twitter.png" alt="Twitter"></a>
				<a href="#" title="Instagram"><img src="img/icons/instagram-new.png" alt="Instagram"></a>
			</div>
		</footer>
	</div>

</body>
</html>