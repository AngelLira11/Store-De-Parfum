<?php
session_start();
require '../backend/conexion_mysql.php'; // Asegúrate de que esta ruta sea correcta

// 1. --- Obtener el ID del Producto de la URL (Método GET) ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Si el ID no existe o no es un número, mostramos un error o redirigimos
    die("Error: ID de producto no especificado o inválido.");
}

$id_producto = $_GET['id'];

// 2. --- Consulta SQL Segura (Prepared Statement) ---
// Seleccionamos TODOS los campos necesarios para la página de detalle
$query = "SELECT nomb_product, precio_product, stock_product, categoria, estado, descripcion, img_product 
          FROM productos 
          WHERE product_id = ?";

// Prepara la consulta
$stmt = $conn->prepare($query);
// Enlaza el ID como un entero ('i' = integer)
$stmt->bind_param("i", $id_producto);
// Ejecuta
$stmt->execute();
// Obtiene el resultado
$result = $stmt->get_result();

// 3. --- Procesar el Resultado ---
if ($result->num_rows === 0) {
    die("Error: El producto con ID $id_producto no fue encontrado.");
}

// Obtiene la única fila de datos y la guarda en $producto
$producto = $result->fetch_assoc();

// Cierra la declaración
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/plantilla_producto.css">
    <title><?php echo htmlspecialchars($producto['nomb_product']); ?> - Tienda</title> 
</head>
<body>
    <?php include("../backend/header.php"); ?>

    <div class="container">
        
        <div class="section">
            <img class="img_1" 
                 src="<?php echo htmlspecialchars($producto['img_product']); ?>" 
                 alt="Imagen de <?php echo htmlspecialchars($producto['nomb_product']); ?>">
        </div>

        <div class="aside">

            <div class="nom_product">
                <h1><?php echo htmlspecialchars($producto['nomb_product']); ?></h1>
            </div>
            
            <div class="precio">
                <p>Precio: $<?php echo htmlspecialchars($producto['precio_product']); ?></p>
            </div>
            
            <div class="stock">
                <p>Disponibilidad: 
                    <?php 
                    if ($producto['stock_product'] > 0) {
                        echo '<span style="color: green;">En Stock (' . $producto['stock_product'] . ' unidades)</span>';
                    } else {
                        echo '<span style="color: red;">Agotado</span>';
                    }
                    ?>
                </p>
            </div>

            <div class="descripcion">
                <h2>Descripción</h2>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            </div>

            </div>

    </div>
    
</body>
</html>