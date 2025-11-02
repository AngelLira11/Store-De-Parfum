<?php
session_start();
require '../backend/conexion_mysql.php';

// Verificar que hay datos de compra
if (!isset($_SESSION['compra_datos'])) {
    header("Location: catalogo.php");
    exit();
}

$compra = $_SESSION['compra_datos'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Completada</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?php include("../backend/header.php"); ?>

    <div class="compra-exitosa">
        <div class="icono-exito">✓</div>
        <h1>¡Compra Completada Exitosamente!</h1>
        <p>Gracias por tu compra. Tu pedido ha sido procesado correctamente.</p>
        
        <div class="numero-orden">
            <strong>Número de Orden:</strong> <?php echo $compra['numero_orden']; ?>
        </div>
        
        <!-- Información de PayPal -->
        <div class="info-paypal">
            <h4>📧 Información de PayPal</h4>
            <p><strong>ID de Transacción:</strong> <?php echo htmlspecialchars($compra['paypal']['transaction_id']); ?></p>
            <p><strong>Estado:</strong> <span class="estado-completado"><?php echo htmlspecialchars($compra['paypal']['status']); ?></span></p>
            <p><strong>Pagador:</strong> <?php echo htmlspecialchars($compra['paypal']['payer_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($compra['paypal']['payer_email']); ?></p>
            <p><strong>Monto:</strong> <?php echo $compra['paypal']['amount']; ?> <?php echo $compra['paypal']['currency']; ?></p>
            <p><strong>Fecha de transacción:</strong> <?php echo date('d/m/Y H:i', strtotime($compra['paypal']['create_time'])); ?></p>
        </div>
        
        <?php if (isset($compra['usuario'])): ?>
        <div class="detalle-compra">
            <h3>👤 Información del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($compra['usuario']['usr_nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($compra['usuario']['usr_email']); ?></p>
            <?php if (!empty($compra['usuario']['usr_telefono'])): ?>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($compra['usuario']['usr_telefono']); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="detalle-compra">
            <h3>🛒 Resumen de tu Pedido</h3>
            <?php foreach ($compra['productos'] as $item): ?>
            <div class="producto-item">
                <span><?php echo htmlspecialchars($item['nombre']); ?> x<?php echo $item['cantidad']; ?></span>
                <span>$<?php echo number_format($item['subtotal'], 2, '.', ','); ?></span>
            </div>
            <?php endforeach; ?>
            
            <div class="total-final">
                Total: $<?php echo number_format($compra['total'], 2, '.', ','); ?>
            </div>
        </div>
        
        <div class="botones-accion">
            <a href="../backend/generar_pdf.php" class="btn-compra btn-pdf" target="_blank">📄 Descargar Comprobante PDF</a>
            <a href="catalogo.php" class="btn-compra btn-inicio">🏠 Volver al Catálogo</a>
        </div>
    </div>
</body>
</html>