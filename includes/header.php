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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/estilos.css">


<style>
    /* --- VARIABLES DE ESTILO --- */
    :root {
        --main-blue-bg: #4864A7; /* Azul de la imagen */
        --submenu-bg: #3b528a;   /* Azul un poco más oscuro para el dropdown */
        --text-white: #ffffff;
        --badge-red: #E74C3C;
        --font-serif: "Times New Roman", Times, serif;
    }
    
    body { margin: 0; padding: 0; font-family: var(--font-serif); }
    
    /* Contenedor Principal */
    .site-header {
        background-color: var(--main-blue-bg);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        position: relative;
        z-index: 1000;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        max-width: 1200px;
        margin: 0 auto;
        min-height: 60px;
    }

    /* --- LOGO --- */
    .logo-container a { display: flex; align-items: center; }
    .logo-container img {
        max-height: 50px;
        width: auto;
    }

    /* --- NAVEGACIÓN PRINCIPAL (Escritorio) --- */
    /* Usamos '>' para asegurar que SOLO afecte a la lista principal */
    .nav-menu > ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex; /* Horizontal solo en nivel superior */
        align-items: center;
    }

    /* Enlaces del menú principal */
    .nav-menu > ul > li > a {
        display: block;
        text-decoration: none;
        color: var(--text-white);
        padding: 20px 15px; /* Padding generoso para facilitar el hover */
        font-family: var(--font-serif);
        font-style: italic;
        font-size: 18px;
        transition: 0.3s;
    }
    
    .nav-menu > ul > li > a:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* --- SUBMENÚ (Dropdown) --- */
    .dropdown {
        position: relative; /* Necesario para posicionar el hijo absoluto */
    }

    .submenu {
        display: none; /* OCULTO POR DEFECTO */
        position: absolute;
        top: 100%;
        left: 0;
        background-color: var(--submenu-bg);
        min-width: 220px;
        padding: 0;
        margin: 0;
        list-style: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1001;
        border-radius: 0 0 4px 4px;
    }

    /* Mostrar submenu al pasar el mouse sobre el padre (.dropdown) en ESCRITORIO */
    @media (min-width: 769px) {
        .dropdown:hover .submenu {
            display: block;
        }
    }

    /* Estilos de los enlaces del SUBMENÚ */
    .submenu li a {
        display: block;
        padding: 12px 20px;
        color: var(--text-white);
        text-decoration: none;
        font-size: 16px;
        font-style: normal; /* El submenu se lee mejor sin cursiva, o cámbialo si gustas */
        border-bottom: 1px solid rgba(255,255,255,0.1);
        text-align: left;
    }

    .submenu li a:hover {
        background-color: rgba(255,255,255,0.1);
        padding-left: 25px; /* Pequeña animación de desplazamiento */
    }

    /* --- CARRITO (Badge) --- */
    .carrito-link { position: relative; display: flex; align-items: center; }
    .contador-carrito {
        background-color: var(--badge-red);
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        font-family: sans-serif;
        font-style: normal;
        position: absolute;
        top: 5px;
        right: 5px;
    }
    .carrito i { font-size: 22px; }

    /* Botón Hamburguesa */
    .menu-toggle { display: none; background: none; border: none; font-size: 24px; color: white; cursor: pointer; }
    .header-bar-cart { display: none; } /* Carrito extra para móvil oculto en PC */

    /* =========================================
       RESPONSIVE (MÓVIL - Pantallas < 768px)
    ========================================= */
    @media (max-width: 768px) {
        .menu-toggle { display: block; }
        .header-bar-cart { display: flex; margin-right: 15px; color: white; text-decoration: none; font-size: 20px;}

        /* Reset del menú para móvil */
        .nav-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: var(--main-blue-bg);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-in-out;
        }
        
        .nav-menu.active { max-height: 800px; } /* Altura suficiente para desplegar todo */

        .nav-menu > ul {
            flex-direction: column; /* Vertical */
            align-items: stretch;
        }

        .nav-menu > ul > li > a {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 15px;
            text-align: center;
        }

        /* En móvil, el submenu NO debe ser absoluto, debe empujar el contenido */
        .submenu {
            position: static;
            display: none; /* Oculto hasta hover/click */
            background-color: rgba(0,0,0,0.2); /* Fondo más oscuro */
            box-shadow: none;
        }
        
        /* En móvil, permitimos ver el submenu al hacer hover o clic */
        .dropdown:hover .submenu, .dropdown:focus-within .submenu {
            display: block;
        }

        /* Ocultar carrito del menú principal en móvil (ya está arriba) */
        .nav-menu .carrito { display: none; }
    }
</style>

<header class="site-header">
    <div class="header-container">
        
        <div class="logo-container">
            <a href="<?php echo $base_path; ?>index.php">
                <img src="<?php echo $base_path; ?>assets/img/icons/img_logo.png" alt="SDP Logo">
            </a>
        </div>

        <div style="display: flex; align-items: center;">
            <a href="<?php echo $base_path; ?>ventas/carrito.php" class="header-bar-cart">
                 <i class="fas fa-shopping-cart"></i>
                 <?php if($total_items > 0): ?>
                    <span class="contador-carrito" style="position:relative; top:-10px; right:5px;"><?php echo $total_items; ?></span>
                 <?php endif; ?>
            </a>
            <button class="menu-toggle" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="nav-menu" id="main-nav">
            <ul>
                <li><a href="<?php echo $base_path; ?>quienes_somos.php">Quiénes Somos</a></li>
                <li><a href="<?php echo $base_path; ?>productos/catalogo.php">Catálogo</a></li>
                <li><a href="<?php echo $base_path; ?>contacto.php">Contacto</a></li>
                
                <li class="dropdown">
                    <a href="#">Mi Cuenta <i class="fas fa-caret-down" style="font-size: 0.8em; margin-left:5px;"></i></a>
                    <ul class="submenu">
                        <?php
                        if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
                            echo '<li><a href="' . $base_path . 'admin/panel_admin.php">Panel Admin</a></li>';
                            echo '<li><a href="' . $base_path . 'usuarios/perfil.php">Perfil</a></li>';
                            echo '<li><a href="?logout=true">Cerrar Sesión</a></li>';
                        } elseif(isset($_SESSION['usr_id'])) {
                            echo '<li><a href="' . $base_path . 'usuarios/perfil.php">Mi Perfil</a></li>';
                            echo '<li><a href="?logout=true">Cerrar Sesión</a></li>';
                        } else {
                            echo '<li><a href="' . $base_path . 'usuarios/login.php">Iniciar Sesión</a></li>';
                            echo '<li><a href="' . $base_path . 'usuarios/registro.php">Registrarse</a></li>';
                        }
                        ?>
                    </ul>
                </li>

                <li class="carrito">
                    <a href="<?php echo $base_path; ?>ventas/carrito.php" class="carrito-link">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if($total_items > 0): ?>
                            <span class="contador-carrito"><?php echo $total_items; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const nav = document.getElementById('main-nav');
        const icon = menuBtn.querySelector('i');

        menuBtn.addEventListener('click', function() {
            nav.classList.toggle('active');
            
            if(nav.classList.contains('active')){
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    });
</script>