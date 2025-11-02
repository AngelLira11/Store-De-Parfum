<?php
session_start();

// OPCIÓN 1: Si instalaste con Composer
// require '../vendor/autoload.php';

// OPCIÓN 2: Si descargaste FPDF manualmente
require '../fpdf/fpdf.php';

require 'conexion_mysql.php';

// Verificar que existen datos de compra
if (!isset($_SESSION['compra_datos'])) {
    die("No hay datos de compra disponibles.");
}

$compra = $_SESSION['compra_datos'];

// Crear instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Colores
$azul = [41, 128, 185];
$gris = [127, 140, 141];
$verde = [39, 174, 96];
$azul_paypal = [0, 112, 186];

// ===== ENCABEZADO =====
$pdf->SetFillColor($azul[0], $azul[1], $azul[2]);
$pdf->Rect(0, 0, 210, 40, 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 15, '', 0, 1);
$pdf->Cell(0, 10, 'COMPROBANTE DE COMPRA', 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, 'Tu Tienda Online', 0, 1, 'C');

$pdf->Ln(10);

// ===== INFORMACIÓN DE LA ORDEN =====
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Informacion de la Orden', 0, 1);
$pdf->SetDrawColor($azul[0], $azul[1], $azul[2]);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, 'Numero de Orden:', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, $compra['numero_orden'], 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, 'Fecha:', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, date('d/m/Y H:i', strtotime($compra['fecha'])), 0, 1);

$pdf->Ln(3);

// ===== INFORMACIÓN DE PAYPAL =====
$pdf->SetFillColor(232, 244, 253);
$pdf->SetDrawColor($azul_paypal[0], $azul_paypal[1], $azul_paypal[2]);
$pdf->Rect(10, $pdf->GetY(), 190, 35, 'FD');

$y_start = $pdf->GetY();
$pdf->SetY($y_start + 3);

$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor($azul_paypal[0], $azul_paypal[1], $azul_paypal[2]);
$pdf->Cell(0, 6, 'Informacion de Pago - PayPal', 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);

$pdf->Cell(50, 5, 'ID de Transaccion:', 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, $compra['paypal']['transaction_id'], 0, 1);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(50, 5, 'Estado del Pago:', 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(39, 174, 96);
$pdf->Cell(0, 5, $compra['paypal']['status'], 0, 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(50, 5, 'Metodo de Pago:', 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, 'PayPal', 0, 1);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(50, 5, 'Fecha de Pago:', 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, date('d/m/Y H:i', strtotime($compra['paypal']['create_time'])), 0, 1);

$pdf->Ln(5);

// ===== INFORMACIÓN DEL PAGADOR =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'Informacion del Pagador', 0, 1);
$pdf->SetDrawColor($azul[0], $azul[1], $azul[2]);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, 'Nombre:', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, utf8_decode($compra['paypal']['payer_name']), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, 6, 'Email:', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, $compra['paypal']['payer_email'], 0, 1);

$pdf->Ln(3);

// ===== INFORMACIÓN DEL CLIENTE (si es usuario registrado) =====
if (isset($compra['usuario'])) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 8, 'Informacion del Cliente Registrado', 0, 1);
    $pdf->SetDrawColor($azul[0], $azul[1], $azul[2]);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 6, 'Nombre:', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, utf8_decode($compra['usuario']['usr_nombre']), 0, 1);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 6, 'Email:', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, $compra['usuario']['usr_email'], 0, 1);
    
    if (!empty($compra['usuario']['usr_telefono'])) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, 'Telefono:', 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, $compra['usuario']['usr_telefono'], 0, 1);
    }
    
    $pdf->Ln(3);
}

// ===== DETALLE DE PRODUCTOS =====
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'Detalle de Productos', 0, 1);
$pdf->SetDrawColor($azul[0], $azul[1], $azul[2]);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(3);

// Encabezado de tabla
$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 8, 'Producto', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Precio Unit.', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Subtotal', 1, 1, 'C', true);

// Productos
$pdf->SetFont('Arial', '', 9);
foreach ($compra['productos'] as $producto) {
    $pdf->Cell(80, 7, utf8_decode($producto['nombre']), 1, 0);
    $pdf->Cell(30, 7, '$' . number_format($producto['precio'], 2), 1, 0, 'R');
    $pdf->Cell(30, 7, $producto['cantidad'], 1, 0, 'C');
    $pdf->Cell(50, 7, '$' . number_format($producto['subtotal'], 2), 1, 1, 'R');
}

// Total
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor($verde[0], $verde[1], $verde[2]);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(140, 10, 'TOTAL PAGADO', 1, 0, 'R', true);
$pdf->Cell(50, 10, '$' . number_format($compra['total'], 2) . ' ' . $compra['paypal']['currency'], 1, 1, 'R', true);

// ===== PIE DE PÁGINA =====
$pdf->Ln(10);
$pdf->SetTextColor($gris[0], $gris[1], $gris[2]);
$pdf->SetFont('Arial', 'I', 9);
$pdf->MultiCell(0, 5, utf8_decode("Gracias por tu compra. Este comprobante es un documento válido de tu transacción.\nPago procesado de forma segura mediante PayPal.\n\nPara cualquier duda o aclaración, por favor contacta a nuestro servicio al cliente."), 0, 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Ln(3);
$pdf->Cell(0, 5, 'ID de Transaccion PayPal: ' . $compra['paypal']['transaction_id'], 0, 1, 'C');

// Generar PDF
$pdf->Output('D', 'Comprobante_' . $compra['numero_orden'] . '.pdf');
?>