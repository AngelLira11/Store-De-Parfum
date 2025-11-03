<?php
session_start();
require 'conexion_mysql.php';

header('Content-Type: application/json');

// Verificar que hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo json_encode(['success' => false, 'message' => 'Carrito vacío']);
    exit();
}

// Obtener datos de PayPal
$input = file_get_contents('php://input');
$paypal_data = json_decode($input, true);

if (!$paypal_data) {
    echo json_encode(['success' => false, 'message' => 'Datos de PayPal inválidos']);
    exit();
}

try {
    $conn->begin_transaction();
    
    // === 1. CREAR O OBTENER CLIENTE ===
    $cli_id = null;
    $cli_tipo = 'invitado';
    $cli_usr_id = null;
    
    // Si el usuario está logueado
    if (isset($_SESSION['usr_id'])) {
        $cli_usr_id = $_SESSION['usr_id'];
        $cli_tipo = 'usuario';
        
        // Buscar si ya existe el cliente
        $query_cli = "SELECT cli_id FROM clientes WHERE cli_usr_id = ?";
        $stmt_cli = $conn->prepare($query_cli);
        $stmt_cli->bind_param("i", $cli_usr_id);
        $stmt_cli->execute();
        $result_cli = $stmt_cli->get_result();
        
        if ($result_cli->num_rows > 0) {
            $cli_id = $result_cli->fetch_assoc()['cli_id'];
        } else {
            // Obtener datos del usuario
            $query_usr = "SELECT usr_nombre, usr_email FROM usuarios WHERE usr_id = ?";
            $stmt_usr = $conn->prepare($query_usr);
            $stmt_usr->bind_param("i", $cli_usr_id);
            $stmt_usr->execute();
            $usr_data = $stmt_usr->get_result()->fetch_assoc();
            
            // Crear nuevo cliente
            $insert_cli = "INSERT INTO clientes (cli_tipo, cli_usr_id, cli_nombre, cli_email) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($insert_cli);
            $stmt_insert->bind_param("siss", $cli_tipo, $cli_usr_id, $usr_data['usr_nombre'], $usr_data['usr_email']);
            $stmt_insert->execute();
            $cli_id = $conn->insert_id;
        }
    } else {
        // Cliente invitado - usar datos de PayPal
        $cli_nombre = $paypal_data['payer_name'];
        $cli_email = $paypal_data['payer_email'];
        
        $insert_cli = "INSERT INTO clientes (cli_tipo, cli_usr_id, cli_nombre, cli_email) VALUES (?, NULL, ?, ?)";
        $stmt_insert = $conn->prepare($insert_cli);
        $stmt_insert->bind_param("sss", $cli_tipo, $cli_nombre, $cli_email);
        $stmt_insert->execute();
        $cli_id = $conn->insert_id;
    }
    
    // === 2. OBTENER PRODUCTOS DEL CARRITO ===
    $productos_ids = array_keys($_SESSION['carrito']);
    $ids_string = implode(',', array_map('intval', $productos_ids));
    
    $query = "SELECT product_id, nomb_product, precio_product FROM productos WHERE product_id IN ({$ids_string})";
    $result = $conn->query($query);
    
    $productos_compra = [];
    $total_general = 0;
    
    while ($producto = $result->fetch_assoc()) {
        $id = $producto['product_id'];
        $cantidad = $_SESSION['carrito'][$id];
        $subtotal = $producto['precio_product'] * $cantidad;
        
        $productos_compra[] = [
            'id'       => $id,
            'nombre'   => $producto['nomb_product'],
            'precio'   => (float)$producto['precio_product'],
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total_general += $subtotal;
    }
    
    // === 3. REGISTRAR LA VENTA ===
    $insert_venta = "INSERT INTO ventas (ven_cli_id, ven_total) VALUES (?, ?)";
    $stmt_venta = $conn->prepare($insert_venta);
    $stmt_venta->bind_param("id", $cli_id, $total_general);
    $stmt_venta->execute();
    $ven_id = $conn->insert_id;
    
    // === 4. REGISTRAR DETALLES DE LA VENTA ===
    $insert_detalle = "INSERT INTO detalle_venta (ven_id, product_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
    $stmt_detalle = $conn->prepare($insert_detalle);
    
    foreach ($productos_compra as $producto) {
        $stmt_detalle->bind_param("iiid", $ven_id, $producto['id'], $producto['cantidad'], $producto['subtotal']);
        $stmt_detalle->execute();
        
        // Actualizar stock y estado si es necesario
        $update_stock = "
            UPDATE productos 
            SET stock_product = stock_product - ?, estado = CASE 
            WHEN (stock_product - ?) <= 0 THEN 'agotado' 
            ELSE estado 
            END
            WHERE product_id = ?";
        $stmt_stock = $conn->prepare($update_stock);
        // Nota: Ahora se pasan 3 parámetros, la cantidad se usa dos veces
        $stmt_stock->bind_param("iii", $producto['cantidad'], $producto['cantidad'], $producto['id']);
        $stmt_stock->execute();
    }
    
    // === 5. GUARDAR DATOS DE PAYPAL Y COMPRA EN SESIÓN ===
    $_SESSION['compra_datos'] = [
        'ven_id' => $ven_id,
        'productos' => $productos_compra,
        'total' => $total_general,
        'fecha' => date('Y-m-d H:i:s'),
        'numero_orden' => 'ORD-' . str_pad($ven_id, 8, '0', STR_PAD_LEFT),
        'paypal' => [
            'transaction_id' => $paypal_data['transaction_id'],
            'status' => $paypal_data['status'],
            'payer_name' => $paypal_data['payer_name'],
            'payer_email' => $paypal_data['payer_email'],
            'amount' => $paypal_data['amount'],
            'currency' => $paypal_data['currency'],
            'create_time' => $paypal_data['create_time'],
            'update_time' => $paypal_data['update_time']
        ]
    ];
    
    // Si es usuario registrado, añadir sus datos
    if (isset($_SESSION['usr_id'])) {
        $query_usuario = "SELECT usr_nombre, usr_email, usr_telefono FROM usuarios WHERE usr_id = ?";
        $stmt = $conn->prepare($query_usuario);
        $stmt->bind_param("i", $_SESSION['usr_id']);
        $stmt->execute();
        $result_usuario = $stmt->get_result();
        if ($result_usuario->num_rows > 0) {
            $_SESSION['compra_datos']['usuario'] = $result_usuario->fetch_assoc();
        }
    }
    
    // Confirmar transacción
    $conn->commit();
    
    // Limpiar carrito
    unset($_SESSION['carrito']);
    
    echo json_encode(['success' => true, 'message' => 'Compra procesada exitosamente']);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al procesar la compra: ' . $e->getMessage()]);
}

$conn->close();
?>