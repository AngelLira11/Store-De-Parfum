<?php
session_start();
include '../config/conexion_mysql.php'; 
if ($_SESSION['is_admin'] !== true) {
    // Si no ha iniciado sesión o no es admin, redirigir a la página principal.
    header("Location: ../index.php");
    exit(); // Es importante detener la ejecución del script después de redirigir.
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i|Open+Sans:400,700&display=swap" rel="stylesheet"> 
    <title>Gestión de Usuarios</title>
</head>
<body class="admin-page">

<header class="admin-header">
    <nav class="admin-nav">
        <div class="logo">
            <a href="../index.php"><img src="../assets/img/icons/logo.svg" alt="Logo"></a>
            <h1>Gestión de Usuarios</h1>
        </div>
        <ul class="nav-links">
            <li><a href="panel_admin.php">Volver al Panel</a></li>
        </ul>
    </nav>
</header>
<div class="admin-container main-content">
    <div class="welcome-section">
        <h2>Administración de Usuarios</h2>
        <p>Aquí puedes ver, crear, editar y eliminar usuarios.</p>
    </div>

    <div class="admin-actions">
        <a href="crear_usuario.php" class="btn btn-primary">Crear Nuevo Usuario</a>
    </div>

    <div class="user-table-container">
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
                <?php
                // Consulta para obtener todos los usuarios
                $sql = "SELECT usr_id, usr_nombre, usr_email, usr_telefono, usr_rol FROM usuarios";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Muestra los datos de cada usuario
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["usr_id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["usr_nombre"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["usr_email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["usr_telefono"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["usr_rol"]) . "</td>";
                        echo '<td class="action-links">';
                        echo '<a href="editar_usuario.php?id=' . $row["usr_id"] . '" class="btn btn-secondary">Editar</a>';
                        // El enlace para eliminar ahora apunta a un script en el backend
                        echo '<a href="eliminar_usuario.php?id=' . $row["usr_id"] . '" class="btn btn-danger" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este usuario?\');">Eliminar</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron usuarios.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
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