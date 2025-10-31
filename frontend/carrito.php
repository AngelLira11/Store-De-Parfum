<?php
session_start();
// **ATENCIÓN: AJUSTA ESTA RUTA SI ES NECESARIO**
require '../backend/conexion_mysql.php'; 

$productos_en_carrito = [];
$total_general = 0;

// --- 1. PROCESAR SI EL USUARIO QUIERE ELIMINAR UN PRODUCTO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id_a_eliminar = filter_var($_POST['eliminar_id'], FILTER_VALIDATE_INT);
    if ($id_a_eliminar !== false && isset($_SESSION['carrito'][$id_a_eliminar])) {
        unset($_SESSION['carrito'][$id_a_eliminar]);
    }
    // Redirigir para limpiar el POST y reflejar el cambio
    header("Location: carrito.php");
    exit();
}


// --- 2. MOSTRAR EL CONTENIDO DEL CARRITO ---
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    
    $productos_ids = array_keys($_SESSION['carrito']);
    $ids_string = implode(',', array_map('intval', $productos_ids));
    
    // Consulta la base de datos para obtener los detalles
    $query = "SELECT product_id, nomb_product, precio_product, img_product FROM productos WHERE product_id IN ({$ids_string})";
    $result = $conn->query($query);
    
    // Procesar resultados y calcular subtotales
    while ($producto = $result->fetch_assoc()) {
        $id = $producto['product_id'];
        $cantidad = $_SESSION['carrito'][$id];
        $subtotal = $producto['precio_product'] * $cantidad;
        
        $productos_en_carrito[] = [
            'id'       => $id,
            'nombre'   => htmlspecialchars($producto['nomb_product']),
            'imagen'   => htmlspecialchars($producto['img_product']),
            'precio'   => (float)$producto['precio_product'],
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total_general += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito de Compras</title>
    <style>
        .carrito-container { width: 90%; max-width: 1000px; margin: 50px auto; }
        .carrito-tabla { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .carrito-tabla th, .carrito-tabla td { border: 1px solid #ddd; padding: 15px; text-align: left; vertical-align: middle; }
        .producto-imagen { width: 80px; height: auto; display: block; }
        .total-fila { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
        <?php include("../backend/header.php"); ?>

    
    <div class="carrito-container">
        <h1>Tu Carrito</h1>
        
        <?php if (empty($productos_en_carrito)): ?>
            <p>Tu carrito está vacío. <a href="catalogo.php">¡Empieza a comprar!</a></p>
        <?php else: ?>
            
            <table class="carrito-tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_en_carrito as $item): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $item['imagen']; ?>" alt="<?php echo $item['nombre']; ?>" class="producto-imagen">
                            <?php echo $item['nombre']; ?>
                        </td>
                        <td>$<?php echo number_format($item['precio'], 2, '.', ','); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['subtotal'], 2, '.', ','); ?></td>
                        
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="eliminar_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" style="color: red; border: none; background: none; cursor: pointer; font-size: 1.2em;">X</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-fila">
                        <td colspan="3">Total General:</td>
                        <td>$<?php echo number_format($total_general, 2, '.', ','); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <div style="text-align: right; margin-top: 20px;">
                <a href="catalogo.php" style="margin-right: 15px; text-decoration: none;">Seguir Comprando</a>
                <button style="padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer;">Finalizar Compra</button>
            </div>
            
        <?php endif; ?>
    </div>
    
</body>
</html>