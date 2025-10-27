<?php

$servername = "localhost"; // Hay que cambiar esto cuando se suba al servidor
$username = "root";
$password = "";
$dbname = "sistema_venta";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}