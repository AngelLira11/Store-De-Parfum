<?php

$servername = "sql113.byetcluster.com"; // Hay que cambiar esto cuando se suba al servidor
$username = "if0_40318520";
$password = "ctmborsuk1";
$dbname = "if0_40318520_sistema_venta"; // Funciona en el XAMPP

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}