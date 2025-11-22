<?php
session_start();
// Asegúrate que esta ruta a tu archivo de conexión sea la correcta
require '../config/conexion_mysql.php'; 

// --- INICIO DEPURACIÓN: Verifica la conexión ---
if ($conn->connect_error) {
    // Si la conexión falla, detiene el script y muestra el error
    die("Error de Conexión: " . $conn->connect_error);
}
// --- FIN DEPURACIÓN ---

$query = "SELECT product_id, nomb_product, img_product FROM productos ORDER BY product_id ASC";
$result = $conn->query($query);

// --- INICIO DEPURACIÓN: Verifica la consulta ---
if ($result === false) {
    // Si la consulta falla (error de sintaxis, tabla incorrecta), detiene el script
    die("Error en la consulta SQL: " . $conn->error);
}

$num_productos = $result->num_rows;
if ($num_productos == 0) {
    // Si la consulta es exitosa pero no devuelve filas
    echo "<h3 style='text-align: center; margin-top: 50px;'>No se encontraron productos activos en la base de datos.</h3>";
}
// --- FIN DEPURACIÓN ---

// Variables para el diseño de filas en el catálogo
$productos_por_fila = 4;
$contador_productos = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/estilos_catalogo.css">
    <title>CATALOGO</title>
</head>
<body class="catalogo-page">
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>
    
    <div class="container">
        <div class="slider">
            <ul>
                <li><img src="../assets/img/catalogo/oferta1.webp" alt="Oferta 1"></li>
                <li><img src="../assets/img/catalogo/oferta3.jpg" alt="Oferta 3"></li>
                <li><img src="../assets/img/catalogo/oferta1.1.webp" alt="Oferta 1.1"></li>
                <li><img src="../assets/img/catalogo/oferta3.1.jpg" alt="Oferta 3.1"></li>
            </ul>
        </div>

        <div class="tit">
            <h2 class="subtitulo"><span>Nuestros Productos</span></h2>
        </div>

        <sec class="cata">

            <div class="mostrador" id="mostrador">
            
            <?php 
            // Solo si se encontraron productos, comenzamos a dibujar las filas
            if ($num_productos > 0) {
                // Inicializa la primera fila
                echo '<div class="fila">'; 
                
                while ($producto = $result->fetch_assoc()): 
                    
                    // Cierra la fila actual y abre una nueva si ya se mostraron 4 productos
                    if ($contador_productos > 0 && $contador_productos % $productos_por_fila === 0) {
                        echo '</div>'; // Cierra la fila anterior
                        echo '<div class="fila">'; // Abre una nueva fila
                    }
                ?>
                    
                    <div class="item">
                        <div class="contenedor_foto">
                            
                            <a href="plantilla_producto.php?id=<?php echo htmlspecialchars($producto['product_id']); ?>">
                                
                                <img src="<?php echo htmlspecialchars($producto['img_product']); ?>" alt="<?php echo htmlspecialchars($producto['nomb_product']); ?>">
                                <p class="descripcion"><?php echo htmlspecialchars($producto['nomb_product']); ?></p>
                            </a>
                        </div>
                    </div>

                <?php 
                $contador_productos++; 
                endwhile; 
                
                // Asegura que la última fila se cierre correctamente
                echo '</div>'; 
            }
            ?>
            
            </div>
        </sec>
    </div>

    <footer class="row justify-content-center redes-sociales">
        <div class="col-auto">
            <a href="#"><img src="../assets/img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="../assets/img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="../assets/img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
    
</body>
</html>