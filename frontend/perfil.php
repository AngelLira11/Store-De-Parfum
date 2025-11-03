<?php
session_start();
require '../backend/conexion_mysql.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usr_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener pedidos del usuario
$usr_id = $_SESSION['usr_id'];

$query_pedidos = "
    SELECT 
        v.ven_id,
        v.ven_fecha,
        v.ven_total,
        c.cli_nombre,
        c.cli_email
    FROM ventas v
    INNER JOIN clientes c ON v.ven_cli_id = c.cli_id
    WHERE c.cli_usr_id = ?
    ORDER BY v.ven_fecha DESC
";

$stmt = $conn->prepare($query_pedidos);
$stmt->bind_param("i", $usr_id);
$stmt->execute();
$result_pedidos = $stmt->get_result();
$pedidos = $result_pedidos->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Perfil de Usuario</title>
</head>
<body class="perfil-page">
    <?php include("../backend/header.php"); ?>

    <main>
        <!-- Hero Section con fondo -->
        <section class="perfil-hero">
            <div class="perfil-hero-texto">
                <h1>Mi Perfil</h1>
            </div>
        </section>

        <!-- Mensajes de éxito o error -->
        <?php if(isset($_SESSION['mensaje_exito'])): ?>
            <div class="mensaje-exito">
                <?php 
                    echo $_SESSION['mensaje_exito']; 
                    unset($_SESSION['mensaje_exito']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Contenedor principal -->
        <div class="container">
            <div class="perfil-content">
                <!-- Título de bienvenida -->
                <h2 class="perfil-titulo">Bienvenido, <?php echo htmlspecialchars($_SESSION['usr_nombre']); ?>!</h2>
                
                <!-- Información actual del usuario -->
                <p class="perfil-info">
                    <strong>Correo Electrónico:</strong> 
                    <?php echo htmlspecialchars($_SESSION['usr_email']); ?>
                </p>
                
                <p class="perfil-info">
                    <strong>Número de Teléfono:</strong> 
                    <?php echo htmlspecialchars($_SESSION['usr_telefono']); ?>
                </p>
                
                <!-- Formulario de edición -->
                <form action="../backend/actualizar_perfil.php" method="POST"> 
                    <label for="nuevo_email">Editar Correo Electrónico:</label>
                    <input type="email" id="nuevo_email" name="nuevo_email" placeholder="Nuevo correo electrónico">
                    
                    <label for="nuevo_telefono">Editar Número de Teléfono:</label>
                    <input type="text" id="nuevo_telefono" name="nuevo_telefono" placeholder="Nuevo teléfono">
                    
                    <label for="nueva_contrasena">Nueva Contraseña:</label>
                    <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Nueva contraseña (dejar en blanco para no cambiar)">
                    
                    <button type="submit" class="btn-primario">Actualizar Perfil</button>
                </form>
            </div>

            <!-- Sección de Mis Pedidos -->
            <div class="pedidos-section">
                <h2 class="pedidos-titulo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    Mis Pedidos
                </h2>

                <?php if (empty($pedidos)): ?>
                    <div class="pedidos-vacio">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <p>No tienes pedidos todavía</p>
                        <a href="catalogo.php" class="btn-ver-catalogo">Ver Catálogo</a>
                    </div>
                <?php else: ?>
                    <div class="pedidos-lista">
                        <?php foreach ($pedidos as $pedido): ?>
                            <?php
                            // Obtener detalles del pedido
                            $query_detalles = "
                                SELECT 
                                    dv.cantidad,
                                    dv.subtotal,
                                    p.nomb_product
                                FROM detalle_venta dv
                                INNER JOIN productos p ON dv.product_id = p.product_id
                                WHERE dv.ven_id = ?
                            ";
                            $stmt_detalles = $conn->prepare($query_detalles);
                            $stmt_detalles->bind_param("i", $pedido['ven_id']);
                            $stmt_detalles->execute();
                            $result_detalles = $stmt_detalles->get_result();
                            $detalles = $result_detalles->fetch_all(MYSQLI_ASSOC);
                            ?>
                            
                            <div class="pedido-card">
                                <div class="pedido-header">
                                    <div class="pedido-numero">
                                        <strong>Pedido #<?php echo str_pad($pedido['ven_id'], 8, '0', STR_PAD_LEFT); ?></strong>
                                        <span class="pedido-fecha">
                                            <?php echo date('d/m/Y H:i', strtotime($pedido['ven_fecha'])); ?>
                                        </span>
                                    </div>
                                    <div class="pedido-total">
                                        $<?php echo number_format($pedido['ven_total'], 2, '.', ','); ?>
                                    </div>
                                </div>
                                
                                <div class="pedido-productos">
                                    <?php foreach ($detalles as $detalle): ?>
                                        <div class="pedido-producto-item">
                                            <span class="producto-nombre-pedido">
                                                <?php echo htmlspecialchars($detalle['nomb_product']); ?>
                                            </span>
                                            <span class="producto-cantidad-pedido">
                                                x<?php echo $detalle['cantidad']; ?>
                                            </span>
                                            <span class="producto-subtotal-pedido">
                                                $<?php echo number_format($detalle['subtotal'], 2, '.', ','); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="pedido-footer">
                                    <span class="pedido-estado estado-completado">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                            <polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                        Completado
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Footer con redes sociales -->
    <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>