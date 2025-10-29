<?php
session_start();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar un producto
if (isset($_GET['agregar'])) {
    $id_producto = $_GET['agregar'];
    if (!isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] = 1;
    } else {
        $_SESSION['carrito'][$id_producto]++;
    }
    header("Location: index.php");
    exit();
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id_producto = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id_producto]);
    header("Location: carrito.php");
    exit();
}
