<?php
session_start();
require '../config/conexion_mysql.php';

// Obtener todos los usuarios
$query = "SELECT usr_id, usr_email, usr_nombre, usr_telefono, usr_rol, usr_fecha_creacion FROM usuarios ORDER BY usr_fecha_creacion DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/estilos.css">

    <title>Gestión de Usuarios</title>
</head>
<body class="admin-page">

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <img src="./img/icons/logo.svg" alt="Logo">
            <h1>Gestión de Usuarios</h1>
        </div>
        <ul class="nav-links">
            <li><a href="panel_admin.php">Panel Admin</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container">
    <div class="usuarios-section">
        <div class="section-header">
            <h3>Lista de Usuarios</h3>
            <button class="btn-primary" onclick="abrirModalCrear()">+ Nuevo Usuario</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $usuario['usr_id']; ?></td>
                    <td><?php echo htmlspecialchars($usuario['usr_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['usr_email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['usr_telefono']); ?></td>
                    <td><?php echo ucfirst($usuario['usr_rol']); ?></td>
                    <td class="acciones">
                        <button class="btn-editar" onclick='abrirModalEditar(<?php echo json_encode($usuario); ?>)'>Editar</button>
                        <button class="btn-eliminar" onclick="eliminar(<?php echo $usuario['usr_id']; ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear -->
<div id="modalCrear" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalCrear')">&times;</span>
        <h2>Nuevo Usuario</h2>
        <form action="guardar_usuario.php" method="POST">
            <input type="hidden" name="accion" value="crear">
            
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Teléfono:</label>
            <input type="tel" name="telefono" required>
            
            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>
            
            <label>Rol:</label>
            <select name="rol" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Admin</option>
            </select>
            
            <button type="submit" class="btn-primary">Crear</button>
        </form>
    </div>
</div>

<!-- Modal Editar -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal('modalEditar')">&times;</span>
        <h2>Editar Usuario</h2>
        <form action="../usuarios/actualizar_usuario.php" method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" id="edit_usr_id">
            
            <label>Nombre:</label>
            <input type="text" name="nombre" id="edit_nombre" required>
            
            <label>Email:</label>
            <input type="email" name="email" id="edit_email" required>
            
            <label>Teléfono:</label>
            <input type="tel" name="telefono" id="edit_telefono" required>
            
            <label>Nueva Contraseña (opcional):</label>
            <input type="password" name="contrasena">
            
            <label>Rol:</label>
            <select name="rol" id="edit_rol" required>
                <option value="usuario">Usuario</option>
                <option value="admin">Admin</option>
            </select>
            
            <button type="submit" class="btn-primary">Guardar</button>
        </form>
    </div>
</div>

<script>
function abrirModalCrear() {
    document.getElementById('modalCrear').style.display = 'block';
}

function abrirModalEditar(usuario) {
    document.getElementById('edit_usr_id').value = usuario.usr_id;
    document.getElementById('edit_nombre').value = usuario.usr_nombre;
    document.getElementById('edit_email').value = usuario.usr_email;
    document.getElementById('edit_telefono').value = usuario.usr_telefono;
    document.getElementById('edit_rol').value = usuario.usr_rol;
    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function eliminar(id) {
    if (confirm('¿Eliminar usuario?')) {
        window.location.href = 'eliminar_usuario.php?accion=eliminar&usr_id=' + id;
    }
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

</body>
</html>