<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Definir base_path si no está definido
if (!isset($base_path)) {
    $base_path = './';
}

$total_items = 0; // Contador de items en el carrito
if (isset($_SESSION['carrito'])) {
    $total_items = array_sum($_SESSION['carrito']);
}

// Manejo de logout
if(isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: " . $base_path . "index.php"); 
    exit();
}
?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/estilos.css">

<nav class="menu">
    <ul>
        <li class="home">
            <a href="<?php echo $base_path; ?>index.php">
                <img src="<?php echo $base_path; ?>assets/img/icons/logo.svg" alt="Página de inicio" class="home-icon"> 
            </a>
        </li>
        <li><a href="<?php echo $base_path; ?>quienes_somos.php">Quiénes Somos</a></li>
        <li><a href="<?php echo $base_path; ?>productos/catalogo.php">Catalogo</a></li>
        <li><a href="<?php echo $base_path; ?>contacto.php">Contacto</a></li>
        <li class="dropdown">
            <a href="#">Mi Cuenta</a>
            <ul class="submenu">
<?php
if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    echo '<li><a href="' . $base_path . 'admin/panel_admin.php">Panel de Administración</a></li>';
    echo '<li><a href="' . $base_path . 'usuarios/perfil.php">Perfil</a></li>';
    echo '<li><a href="?logout=true">Cerrar Sesión</a></li>';
} elseif(isset($_SESSION['usr_id'])) {
    // Usuario normal (tiene sesión pero no es admin)
    echo '<li><a href="' . $base_path . 'usuarios/perfil.php">Mi Perfil</a></li>';
    echo '<li><a href="?logout=true">Cerrar Sesión</a></li>';
} else {
    // No hay sesión activa
    echo '<li><a href="' . $base_path . 'usuarios/login.php">Iniciar Sesión</a></li>';
    echo '<li><a href="' . $base_path . 'usuarios/registro.php">Registrarse</a></li>';
}
?>
            </ul>
        </li>
        <li class="carrito">
    <a href="<?php echo $base_path; ?>ventas/carrito.php">
        <img src="<?php echo $base_path; ?>assets/img/icons/carrito3.svg" alt="Carrito" class="icono-carrito">
        <span class="contador-carrito"><?php echo $total_items; ?></span>
    </a>
</li>
    </ul>
</nav>
