<?php
session_start();
require '../config/conexion_mysql.php';

// 1. --- Obtener y validar el ID del Producto ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID de producto no especificado o inválido.");
}
$id_producto = $_GET['id'];

// 2. --- Consulta SQL Segura ---
$query = "SELECT nomb_product, precio_product, stock_product, categoria, estado, descripcion, img_product 
          FROM productos 
          WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: El producto con ID $id_producto no fue encontrado.");
}
$producto = $result->fetch_assoc();
$stmt->close();

// === NUEVA LÓGICA ===
// Variable para controlar si el producto se puede comprar y para el estado de los botones
$producto_disponible = ($producto['estado'] === 'disponible' && $producto['stock_product'] > 0);

// --- Manejo del contador de cantidad ---
$stock_maximo = (int)$producto['stock_product'];
$sesion_key = 'cantidad_' . $id_producto;

if (!isset($_SESSION[$sesion_key])) {
    $_SESSION[$sesion_key] = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($producto_disponible) { // Solo procesar si el producto está disponible
        $cantidad_actual = (int)$_SESSION[$sesion_key];
        $nueva_cantidad = $cantidad_actual;
        
        if ($_POST['action'] === 'sumar' && $cantidad_actual < $stock_maximo) {
            $nueva_cantidad++;
        } elseif ($_POST['action'] === 'restar' && $cantidad_actual > 1) {
            $nueva_cantidad--;
        }
        $_SESSION[$sesion_key] = $nueva_cantidad;
    }
    header("Location: plantilla_producto.php?id=" . $id_producto);
    exit();
}

// --- Procesar AGREGAR AL CARRITO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_carrito']) && $_POST['action_carrito'] === 'agregar') {
    // Doble verificación: no agregar si no está disponible
    if (!$producto_disponible) {
        // Redirigir o mostrar error si alguien intenta agregar un producto agotado
        header("Location: plantilla_producto.php?id=" . $id_producto);
        exit();
    }

    $cantidad_a_agregar = (int)$_SESSION[$sesion_key]; 
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += $cantidad_a_agregar;
    } else {
        $_SESSION['carrito'][$id_producto] = $cantidad_a_agregar;
    }
    unset($_SESSION[$sesion_key]); 
    header("Location: ../ventas/carrito.php"); 
    exit();
}

$cantidad_a_mostrar = isset($_SESSION[$sesion_key]) ? (int)$_SESSION[$sesion_key] : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/productos_unique.css">
    <title><?php echo htmlspecialchars($producto['nomb_product']); ?> - Tienda</title> 
</head>
<body>
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>

    <div class="container main-content">
        <div class="section"> 
            <img class="img_1" 
                 src="<?php 
    $ruta = $producto['img_product'];
    
    // Verificamos si la ruta contiene "http" (es de internet)
    if (strpos($ruta, 'http') !== false) {
        echo $ruta; 
    } 
    // Si NO tiene http, es local, así que le agregamos "../" para salir de la carpeta productos
    else {
        echo '../' . $ruta; 
    }
?>" 
                 alt="Imagen de <?php echo htmlspecialchars($producto['nomb_product']); ?>">
        </div>

        <div class="aside">
            <div class="nom_product">
                <h1><?php echo htmlspecialchars($producto['nomb_product']); ?></h1>
                <hr>
            </div>
            
            <div class="precio">
                <p>$<?php echo number_format((float)$producto['precio_product'], 2, '.', ','); ?></p>
            </div>
            
            <div class="stock">
                <p>Disponibilidad: 
                    <?php 
                    if ($producto_disponible) {
                        echo '<span style="color: green;">En Stock (' . $producto['stock_product'] . ' unidades)</span>';
                    } else {
                        echo '<span style="color: red;">Agotado</span>';
                    }
                    ?>
                </p>
            </div>
            
            <div class="parrafo">
                <img src="img/icons/camion.png" alt="Envío">
                <p>Envíos Gratis a todo el país</p>
            </div>

            <div class="cantidad-control">
                <p>Cantidad:</p>
                <div class="input-group">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="restar">
                        <button type="submit" class="btn-cantidad" <?php if (!$producto_disponible) echo 'disabled'; ?>>-</button>
                    </form>
                    
                    <input type="text" id="input-cantidad" value="<?php echo htmlspecialchars($cantidad_a_mostrar); ?>" readonly>
                    
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="sumar">
                        <button type="submit" class="btn-cantidad" <?php if (!$producto_disponible) echo 'disabled'; ?>>+</button>
                    </form>
                </div>
            </div>

            <div class="descripcion">
                <h2>Descripción</h2>
                <br>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            </div>
            
            <div class="butt">
                <form method="POST">
                    <input type="hidden" name="action_carrito" value="agregar">
                    <button type="submit" class="btn-agregar-carrito" style="padding: 10px 20px; background-color: #6a6a9b; color: white; border: none; cursor: pointer;" <?php if (!$producto_disponible) echo 'disabled'; ?>>
                        AGREGAR AL CARRITO
                    </button>
                </form>
            </div>
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