<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$total_items = 0; // Contador de items en el carrito
if (isset($_SESSION['carrito'])) {
    $total_items = array_sum($_SESSION['carrito']);
}

// Manejo de logout
if(isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: index.php"); 
    exit();
}
?>
<link rel="stylesheet" href="../frontend/css/estilos.css">

<nav class="menu">
    <ul>
        <li class="home">
            <a href="index.php">
                <img src="./img/icons/logo.svg" alt="Página de inicio" class="home-icon"> 
            </a>
        </li>
        <li><a href="quienes_somos.php">Quiénes Somos</a></li>
        <li><a href="catalogo.php">Catalogo</a></li>
        <li><a href="contacto.php">Contacto</a></li>
        <li class="dropdown">
            <a href="#">Mi Cuenta</a>
            <ul class="submenu">
<?php
if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    echo '<li><a href="admin_panel.php">Panel de Administración</a></li>';
    echo '<li><a href="perfil.php">Perfil</a></li>';
    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
} elseif(isset($_SESSION['usr_id'])) {
    // Usuario normal (tiene sesión pero no es admin)
    echo '<li><a href="perfil.php">Mi Perfil</a></li>';
    echo '<li><a href="?logout=true">Cerrar Sesión</a></li>';
} else {
    // No hay sesión activa
    echo '<li><a href="login.php">Iniciar Sesión</a></li>';
    echo '<li><a href="registro.php">Registrarse</a></li>';
}
?>
            </ul>
        </li>
        <li class="carrito">
    <a href="carrito.php">
        <img src="./img/icons/carrito3.svg" alt="Carrito" class="icono-carrito">
        <span class="contador-carrito"><?php echo $total_items; ?></span>
    </a>
</li>
    </ul>
</nav>
