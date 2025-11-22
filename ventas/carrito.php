<?php
session_start();
require '../config/conexion_mysql.php'; 

$productos_en_carrito = [];
$total_general = 0;

// --- 1. PROCESAR SI EL USUARIO QUIERE ELIMINAR UN PRODUCTO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id_a_eliminar = filter_var($_POST['eliminar_id'], FILTER_VALIDATE_INT);
    if ($id_a_eliminar !== false && isset($_SESSION['carrito'][$id_a_eliminar])) {
        unset($_SESSION['carrito'][$id_a_eliminar]);
    }
    header("Location: carrito.php");
    exit();
}

// --- 2. MOSTRAR EL CONTENIDO DEL CARRITO ---
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    
    $productos_ids = array_keys($_SESSION['carrito']);
    $ids_string = implode(',', array_map('intval', $productos_ids));
    
    $query = "SELECT product_id, nomb_product, precio_product, img_product FROM productos WHERE product_id IN ({$ids_string})";
    $result = $conn->query($query);
    
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Carrito de Compras</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>

    <div class="carrito-container main-content">
        <h1 class="carrito-titulo">Tu Carrito</h1>
        
        <?php if (empty($productos_en_carrito)): ?>
            <p class="carrito-vacio">
                Tu carrito está vacío. 
                <a href="../productos/catalogo.php" class="link-seguir-comprando">¡Empieza a comprar!</a>
            </p>
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
                            <div class="producto-info">
                                <img src="../assets/<?php echo $item['imagen']; ?>" 
                                     alt="<?php echo $item['nombre']; ?>" 
                                     class="producto-imagen">
                                <span class="producto-nombre"><?php echo $item['nombre']; ?></span>
                            </div>
                        </td>
                        <td class="precio-cell">$<?php echo number_format($item['precio'], 2, '.', ','); ?></td>
                        <td class="cantidad-cell"><?php echo $item['cantidad']; ?></td>
                        <td class="subtotal-cell">$<?php echo number_format($item['subtotal'], 2, '.', ','); ?></td>
                        <td class="acciones-cell">
                            <form method="POST" class="form-eliminar">
                                <input type="hidden" name="eliminar_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-eliminar" title="Eliminar producto">X</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-fila">
                        <td colspan="3">Total General:</td>
                        <td class="total-precio">$<?php echo number_format($total_general, 2, '.', ','); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="carrito-acciones">
                <a href="catalogo.php" class="btn-seguir-comprando">Seguir Comprando</a>
                <div id="paypal-button-container"></div>
            </div>
            
        <?php endif; ?>

        <p id="result-message"></p>
    </div>

    <!-- Integración de PAYPAL -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=AZ9YLo6S1FGFLg41ofSsIkyfG3UN2j6ezBPg5kzusyr4c3D9TiZKGQtUiBJuIBPnl8CVuyO_NKP1mrWO&buyer-country=MX&currency=MXN&components=buttons&enable-funding=card&disable-funding=venmo,paylater"
        data-sdk-integration-source="developer-studio">
    </script>
    
    <script>
        paypal.Buttons({
            style: {
                shape: "pill",
                layout: "vertical",
                color: "blue",
                label: "pay"
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $total_general; ?>'
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    console.log("Detalles completos de PayPal:", details);
                    
                    if (details.status === "COMPLETED") {
                        // Preparar datos para enviar al servidor
                        const paypalData = {
                            transaction_id: details.id,
                            status: details.status,
                            payer_id: details.payer.payer_id,
                            payer_name: details.payer.name.given_name + ' ' + details.payer.name.surname,
                            payer_email: details.payer.email_address,
                            amount: details.purchase_units[0].amount.value,
                            currency: details.purchase_units[0].amount.currency_code,
                            create_time: details.create_time,
                            update_time: details.update_time
                        };
                        
                        // Enviar datos al servidor mediante fetch
                        fetch('procesar_compra.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(paypalData)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                window.location.href = "compra_completada.php";
                            } else {
                                alert("Error al procesar la compra: " + result.message);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            alert("Error al procesar la compra");
                        });
                        
                    } else {
                        alert("El pago no se completó correctamente.");
                        console.log(details);
                    }
                }).catch(function(error) {
                    console.error("Error al capturar el pago:", error);
                    alert("Error al procesar el pago");
                });
            },
            onCancel: function (data) {
                alert("Pago cancelado");
                console.log(data);
            },
            onError: function(err) {
                console.error("Error en PayPal:", err);
            }
        }).render("#paypal-button-container");
    </script>
        <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="../assets/img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="../assets/img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="../assets/img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>