<?php
session_start();
// **ATENCIÓN: AJUSTA ESTA RUTA SI ES NECESARIO**
// Asumo que estás en una carpeta que requiere subir un nivel para llegar a backend/
require '../config/conexion_mysql.php'; 

// --- FUNCIÓN DE SANEAMIENTO DE ENTRADA ---
// Función para limpiar la entrada de datos (seguridad)
function sanear($conn, $dato) {
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($dato)));
}

// ----------------------------------------------------
// A. LÓGICA PARA AGREGAR NUEVO PRODUCTO (action = 'agregar')
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar') {
    
    // Captura y saneamiento de datos
    $nomb = sanear($conn, $_POST['nomb_product']);
    $precio = (float)$_POST['precio_product'];
    $stock = (int)$_POST['stock_product'];
    $cat = sanear($conn, $_POST['categoria']);
    $est = sanear($conn, $_POST['estado']); // No cambia la lógica PHP
    $desc = sanear($conn, $_POST['descripcion']);
    $img = sanear($conn, $_POST['img_product']);
    
    // Verificación básica
    if (!empty($nomb) && $precio > 0 && $stock >= 0) {
        
        $query = "INSERT INTO productos (nomb_product, precio_product, stock_product, categoria, estado, descripcion, img_product) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdissss", $nomb, $precio, $stock, $cat, $est, $desc, $img);
        
        if ($stmt->execute()) {
            $mensaje_agregar = "✅ Producto '{$nomb}' agregado con éxito!";
        } else {
            $mensaje_agregar = "❌ Error al agregar producto: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensaje_agregar = "⚠️ Por favor, complete todos los campos requeridos correctamente.";
    }
}

// ----------------------------------------------------
// B. LÓGICA PARA BUSCAR PRODUCTO PARA MODIFICAR (action = 'buscar')
// ----------------------------------------------------
$producto_a_modificar = null; 
$mensaje_buscar = null;
$id_buscar = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'buscar') {
    $id_buscar = (int)$_POST['product_id'];
    
    $query = "SELECT * FROM productos WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_buscar);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $producto_a_modificar = $result->fetch_assoc();
        $mensaje_buscar = "✅ Producto ID {$id_buscar} cargado para modificación.";
    } else {
        $mensaje_buscar = "⚠️ Producto ID {$id_buscar} no encontrado.";
    }
    $stmt->close();
}

// ----------------------------------------------------
// C. LÓGICA PARA ACTUALIZAR PRODUCTO (action = 'modificar')
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modificar') {
    
    $id_modificar = (int)$_POST['product_id_modificar'];
    $nomb = sanear($conn, $_POST['nomb_product']);
    $precio = (float)$_POST['precio_product'];
    $stock = (int)$_POST['stock_product'];
    $cat = sanear($conn, $_POST['categoria']);
    $est = sanear($conn, $_POST['estado']); // No cambia la lógica PHP
    $desc = sanear($conn, $_POST['descripcion']);
    $img = sanear($conn, $_POST['img_product']);
    
    $query = "UPDATE productos SET 
                  nomb_product=?, precio_product=?, stock_product=?, categoria=?, 
                  estado=?, descripcion=?, img_product=? 
                WHERE product_id=?";
                  
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdissssi", $nomb, $precio, $stock, $cat, $est, $desc, $img, $id_modificar);
    
    if ($stmt->execute()) {
        $mensaje_modificar = "✅ Producto ID {$id_modificar} modificado con éxito!";
    } else {
        $mensaje_modificar = "❌ Error al modificar producto: " . $conn->error;
    }
    $stmt->close();
}

// Obtener la lista de todos los productos para referencia rápida
$result_list = $conn->query("SELECT product_id, nomb_product, stock_product FROM productos ORDER BY product_id DESC LIMIT 10");
$productos_lista = [];
while ($row = $result_list->fetch_assoc()) {
    $productos_lista[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificación de Productos</title>
    <link rel="stylesheet" href="../assets/css/modificar_catalogo.css"> 
</head>
<body>

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <a href="../index.php"><img src="../assets/img/icons/logo.svg" alt="Logo"></a>
            <h1 class="titulo-responsive">Modificación de Productos</h1>
        </div>
        <ul class="nav-links">
            <li><a href="../admin/panel_admin.php">Volver al Panel</a></li>
        </ul>
    </nav>
</header> 

<div class="container">
    <h1>⚙️ Panel de Modificación de Productos</h1>
    <hr>
    
    <div class="listado-productos">
        <h3>PRODUCTOS DEL INVENTARIO</h3>
        <?php if (!empty($productos_lista)): ?>
            <ul>
            <?php foreach ($productos_lista as $p): ?>
                <li>ID: <?php echo $p['product_id']; ?>| Nombre: <?php echo htmlspecialchars($p['nomb_product']); ?> | Stock: <?php echo $p['stock_product']; ?></li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay productos registrados en la base de datos.</p>
        <?php endif; ?>
    </div>
    <hr>

    <div class="formulario">
        <h2>➕ Agregar Nuevo Producto</h2>
        <?php if (isset($mensaje_agregar)): ?>
            <p class="mensaje <?php echo (strpos($mensaje_agregar, '✅') !== false) ? 'success' : 'error'; ?>"><?php echo $mensaje_agregar; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="action" value="agregar">
            
            <label for="nomb_product">Nombre del Producto:</label>
            <input type="text" name="nomb_product" required>
            
            <label for="precio_product">Precio:</label>
            <input type="number" name="precio_product" step="0.01" required>
            
            <label for="stock_product">Stock:</label>
            <input type="number" name="stock_product" required>
            
            <label for="categoria">Categoría:</label>
            <input type="text" name="categoria">
            
            <label for="estado">Estado:</label>
            <select name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="agotado">Agotado</option>
                <option value="descontinuado">Descontinuado</option>
            </select>
            
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion"></textarea>
            
            <label for="img_product">URL de Imagen (img_product):</label>
            <input type="text" name="img_product">
            
            <button type="submit">Guardar Producto</button>
        </form>
    </div>
    <hr>
    
    <div class="formulario">
        <h2>✏️ Modificar Producto Existente</h2>
        <?php if (isset($mensaje_modificar)): ?>
            <p class="mensaje <?php echo (strpos($mensaje_modificar, '✅') !== false) ? 'success' : 'error'; ?>"><?php echo $mensaje_modificar; ?></p>
        <?php endif; ?>
        
        <h3>Paso 1: Buscar por ID</h3>
        <?php if (isset($mensaje_buscar)): ?>
            <p class="mensaje <?php echo (strpos($mensaje_buscar, '✅') !== false) ? 'success' : 'error'; ?>"><?php echo $mensaje_buscar; ?></p>
        <?php endif; ?>
        
        <form method="POST" style="display: flex; gap: 10px; align-items: flex-end;">
            <input type="hidden" name="action" value="buscar">
            <div style="flex-grow: 1;">
                <label for="product_id">ID del Producto a Modificar:</label>
                <input type="number" name="product_id" required value="<?php echo htmlspecialchars($id_buscar ?? ''); ?>">
            </div>
            <button type="submit">Buscar</button>
        </form>

        <?php if ($producto_a_modificar): ?>
            <hr>
            <h3>Paso 2: Modificar Producto ID #<?php echo $producto_a_modificar['product_id']; ?></h3>
            <form method="POST">
                <input type="hidden" name="action" value="modificar">
                <input type="hidden" name="product_id_modificar" value="<?php echo $producto_a_modificar['product_id']; ?>">
                
                <label for="nomb_product">Nombre del Producto:</label>
                <input type="text" name="nomb_product" value="<?php echo htmlspecialchars($producto_a_modificar['nomb_product']); ?>" required>
                
                <label for="precio_product">Precio:</label>
                <input type="number" name="precio_product" step="0.01" value="<?php echo htmlspecialchars($producto_a_modificar['precio_product']); ?>" required>
                
                <label for="stock_product">Stock:</label>
                <input type="number" name="stock_product" value="<?php echo htmlspecialchars($producto_a_modificar['stock_product']); ?>" required>
                
                <label for="categoria">Categoría:</label>
                <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto_a_modificar['categoria']); ?>">
                
                <label for="estado">Estado:</label>
                <select name="estado" required>
                    <option value="disponible" <?php if ($producto_a_modificar['estado'] === 'disponible') echo 'selected'; ?>>Disponible</option>
                    <option value="agotado" <?php if ($producto_a_modificar['estado'] === 'agotado') echo 'selected'; ?>>Agotado</option>
                    <option value="descontinuado" <?php if ($producto_a_modificar['estado'] === 'descontinuado') echo 'selected'; ?>>Descontinuado</option>
                </select>
                
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion"><?php echo htmlspecialchars($producto_a_modificar['descripcion']); ?></textarea>
                
                <label for="img_product">URL de Imagen (img_product):</label>
                <input type="text" name="img_product" value="<?php echo htmlspecialchars($producto_a_modificar['img_product']); ?>">
                
                <button type="submit">Guardar Cambios</button>
            </form>
        <?php endif; ?>
    </div>
</div>
    <footer>
        <div class="redes-container">
            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>