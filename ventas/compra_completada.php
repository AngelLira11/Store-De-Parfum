<?php
session_start();
require '../config/conexion_mysql.php';

// Verificar que hay datos de compra
if (!isset($_SESSION['compra_datos'])) {
    header("Location: ../productos/catalogo.php");
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
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body>
    <style>
        body { margin: 0 !important; padding: 0 !important; }
        header, nav { margin-bottom: 0 !important; }
    </style>
    <?php 
    $base_path = '../';
    include("../includes/header.php"); 
    ?>

    <div class="confirmacion-wrapper">
        <div class="confirmacion-container">
            
            <!-- Sección de éxito -->
            <div class="confirmacion-header">
                <div class="check-circle">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="38" stroke="#4CAF50" stroke-width="4"/>
                        <path d="M25 40L35 50L55 30" stroke="#4CAF50" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1>¡Pago Confirmado!</h1>
                <p class="mensaje-exito">Tu pedido ha sido procesado exitosamente</p>
                <div class="orden-badge">
                    <span class="orden-numero"><?php echo $compra['numero_orden']; ?></span>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="confirmacion-content">
                
                <!-- Resumen del pedido -->
                <div class="info-card resumen-card">
                    <div class="card-header-simple">
                        <h2>Resumen de tu Pedido</h2>
                    </div>
                    <div class="card-body">
                        <div class="productos-lista-simple">
                            <?php foreach ($compra['productos'] as $item): ?>
                            <div class="producto-item-simple">
                                <div class="producto-info-simple">
                                    <span class="producto-nombre-simple"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                    <span class="producto-cantidad-simple">x<?php echo $item['cantidad']; ?></span>
                                </div>
                                <span class="producto-precio-simple">$<?php echo number_format($item['subtotal'], 2, '.', ','); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="total-section-simple">
                            <span class="total-label">Total Pagado</span>
                            <span class="total-monto">$<?php echo number_format($compra['total'], 2, '.', ','); ?></span>
                        </div>
                        
                        <div class="pago-info-simple">
                            <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" class="paypal-icon">
                            <span>Pago procesado con PayPal</span>
                        </div>
                    </div>
                </div>

                <!-- Información del cliente (solo si está registrado) -->
                <?php if (isset($compra['usuario'])): ?>
                <div class="info-card cliente-card">
                    <div class="card-header-simple">
                        <h2>Información de Envío</h2>
                    </div>
                    <div class="card-body">
                        <p class="cliente-nombre"><?php echo htmlspecialchars($compra['usuario']['usr_nombre']); ?></p>
                        <p class="cliente-email"><?php echo htmlspecialchars($compra['usuario']['usr_email']); ?></p>
                        <?php if (!empty($compra['usuario']['usr_telefono'])): ?>
                        <p class="cliente-telefono"><?php echo htmlspecialchars($compra['usuario']['usr_telefono']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Botones de acción -->
                <div class="acciones-grid">
                    <a href="generar_pdf.php" class="btn-accion-large btn-pdf-large" target="_blank">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>Descargar Comprobante</span>
                    </a>
                    <a href="catalogo.php" class="btn-accion-large btn-catalogo-large">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        </svg>
                        <span>Seguir Comprando</span>
                    </a>
                </div>

                <!-- Mensaje de confirmación -->
                <div class="mensaje-final">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <p>Te hemos enviado la confirmación por correo electrónico</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>