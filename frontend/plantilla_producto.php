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


// --- Código de consulta del producto (ya existente) ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID de producto no especificado o inválido.");
}
$id_producto = $_GET['id'];
// ... (código de consulta segura para obtener $producto) ...

// Obtener el stock máximo del producto
$stock_maximo = (int)$producto['stock_product'];

// ----------------------------------------------------
// 1. MANEJO DE LA CANTIDAD EN LA SESIÓN
// ----------------------------------------------------

// Usaremos la sesión para guardar la cantidad seleccionada para este producto
$sesion_key = 'cantidad_' . $id_producto;

// Inicializar la cantidad si no existe
if (!isset($_SESSION[$sesion_key])) {
    $_SESSION[$sesion_key] = 1;
}

// 2. PROCESAR ENVÍO DEL FORMULARIO (Si se presiona '+' o '-')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $cantidad_actual = (int)$_SESSION[$sesion_key];
    $nueva_cantidad = $cantidad_actual;
    
    if ($_POST['action'] === 'sumar') {
        $nueva_cantidad = $cantidad_actual + 1;
        // Límite superior: stock máximo
        if ($nueva_cantidad > $stock_maximo) {
            $nueva_cantidad = $stock_maximo;
            // Opcional: mostrar un mensaje de error o aviso.
        }
    } elseif ($_POST['action'] === 'restar') {
        $nueva_cantidad = $cantidad_actual - 1;
        // Límite inferior: 1
        if ($nueva_cantidad < 1) {
            $nueva_cantidad = 1;
        }
    }

    $_SESSION[$sesion_key] = $nueva_cantidad;
    
    // **Importante:** Redirigir para evitar reenvío del formulario (Post/Redirect/Get pattern)
    header("Location: plantilla_producto.php?id=" . $id_producto);
    exit();
}

// Obtener la cantidad final para mostrar
$cantidad_a_mostrar = $_SESSION[$sesion_key];


//carrito de compras
// --- 4. PROCESAR EL BOTÓN 'AGREGAR AL CARRITO' ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_carrito']) && $_POST['action_carrito'] === 'agregar') {
    
    // Obtener la cantidad FINAL del contador que está en la sesión
    $cantidad_a_agregar = (int)$_SESSION[$sesion_key]; 

    // Inicializar el carrito principal si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Agregar o Actualizar el producto en el carrito (suma si ya existe)
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += $cantidad_a_agregar;
    } else {
        $_SESSION['carrito'][$id_producto] = $cantidad_a_agregar;
    }
    
    // Opcional: Borrar la cantidad temporal del contador después de agregarlo
    unset($_SESSION[$sesion_key]); 

    // Redirigir al usuario a la página del carrito
    header("Location: carrito.php"); 
    exit();
}

// 5. Obtener la cantidad final para mostrar en el input
$cantidad_a_mostrar = isset($_SESSION[$sesion_key]) ? (int)$_SESSION[$sesion_key] : 1; 

// Obtener detalles del producto para el HTML (puedes ajustar el precio si quieres el formato con superíndice)
$nomb_product = htmlspecialchars($producto['nomb_product']);
$precio_product = number_format((float)$producto['precio_product'], 2, '.', ',');
$descripcion = htmlspecialchars($producto['descripcion']);
$img_product = htmlspecialchars($producto['img_product']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/productos_unique.css">
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
                <hr>
            </div>

          
            
            <div class="precio">
                <p>$<?php echo htmlspecialchars($producto['precio_product']); ?></p>
            </div>
            
            <!--
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
                -->
            <div class="parrafo">
                <img src="img/icons/camion.png" alt="">
                <p>Envios Gratis a todo el pais</p>
            </div>

                <div class="cantidad-control">
    <p>Cantidad:</p>
    <div class="input-group">
        
        <form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="restar">
            <button type="submit" class="btn-cantidad">-</button>
        </form>
        
        <input type="text" id="input-cantidad" value="<?php echo htmlspecialchars($cantidad_a_mostrar); ?>" readonly>
        
        <form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="sumar">
            <button type="submit" class="btn-cantidad">+</button>
        </form>
    </div>
</div>
            <div class="descripcion">
                <h2>Descripción</h2>
                <br>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
            </div>
            
            <!--boton para comprar-->
           <div class="butt">
             <form method="POST">
                <input type="hidden" name="action_carrito" value="agregar">
                <button type="submit" class="btn-agregar-carrito" style="padding: 10px 20px; background-color: #6a6a9b; color: white; border: none; cursor: pointer;">AGREGAR AL CARRITO</button>
            </form>
           </div>
            
		    

            </div>

    </div>

   
    
</body>
</html>