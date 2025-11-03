<?php
session_start();
require '../backend/conexion_mysql.php';

if ($_SESSION['is_admin'] !== true) {
    // Si no ha iniciado sesión o no es admin, redirigir a la página principal.
    header("Location: index.php");
    exit(); // Es importante detener la ejecución del script después de redirigir.
}

// Obtener el ID del usuario de la URL
$id = $_GET['id'];
if (filter_var($id, FILTER_VALIDATE_INT) === false) {
    // Si el ID no es un entero válido, redirigir
    header("Location: gestion_usuarios.php");
    exit();
}

// Consultar los datos del usuario específico
$sql = "SELECT usr_nombre, usr_email, usr_telefono, usr_rol FROM usuarios WHERE usr_id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Editar Usuario</title>
</head>
<body class="admin-page">

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <a href="admin.php"><img src="./img/icons/logo.svg" alt="Logo"></a>
            <h1>Editar Usuario</h1>
        </div>
        <ul class="nav-links">
            <li><a href="gestion_usuarios.php">Volver a Gestión</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container form-container main-content">
    <h2>Editando a <?php echo htmlspecialchars($usuario['usr_nombre']); ?></h2>
    <form action="../backend/actualizar_usuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['usr_nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['usr_email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="contrasena">Nueva Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena">
            <small>Deja este campo en blanco para no cambiar la contraseña.</small>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['usr_telefono']); ?>" required>
        </div>
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="usuario" <?php if($usuario['usr_rol'] == 'usuario') echo 'selected'; ?>>Usuario</option>
                <option value="admin" <?php if($usuario['usr_rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
    </form>
</div>
        <footer class="redes-sociales">
        <div class="redes-container">
            <a href="#"><img src="img/icons/facebook.png" alt="facebook"></a>
            <a href="#"><img src="img/icons/twitter.png" alt="twitter"></a>
            <a href="#"><img src="img/icons/instagram-new.png" alt="instagram"></a>
        </div>
    </footer>
</body>
</html>