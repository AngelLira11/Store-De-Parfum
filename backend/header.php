<?php
session_start();
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
if(isset($_SESSION['usuario']) && $_SESSION['usuario'] && !isset($_SESSION['is_admin'])) {
    echo '<li><a href="perfil.php">Perfil</a></li>';
    echo '<li><a href="logout.php">Cerrar Sesión</a></li>';
} elseif(isset($_SESSION['is_admin'])) {
    echo '<li><a href="admin_panel.php">Panel de Administración</a></li>';
} else {
    echo '<li><a href="login.php">Iniciar Sesión</a></li>';
    echo '<li><a href="registro.php">Registrarse</a></li>';
}
?>
            </ul>
        </li>
    </ul>
</nav>
