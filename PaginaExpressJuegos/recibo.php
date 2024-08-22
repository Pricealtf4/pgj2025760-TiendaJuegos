<?php
require('vendor/setasign/fpdf/fpdf.php'); // Asegúrate de que la ruta sea correcta
include 'config.php';

if (!isset($_GET['id_recibo'])) {
    die('Error: Recibo no especificado.');
}

$id_recibo = $_GET['id_recibo'];

// Consultar los detalles del recibo
$sql = "SELECT r.id_recibo, r.total, r.detalle, r.cambio, o.fecha 
        FROM recibos r
        INNER JOIN ordenes o ON r.id_orden = o.id_orden
        WHERE r.id_recibo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_recibo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $recibo = $result->fetch_assoc();
} else {
    die('Error: Recibo no encontrado.');
}

if (isset($_POST['download_pdf'])) {
    // Generar el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Encabezado del recibo
    $pdf->SetTextColor(0, 123, 255); // Color azul
    $pdf->Cell(0, 10, 'Recibo de Compra', 0, 1, 'C');
    $pdf->Ln(10);

    // Detalles del recibo
    $pdf->SetTextColor(0, 0, 0); // Color negro
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 10, 'ID Recibo: ' . $recibo['id_recibo'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha: ' . $recibo['fecha'], 0, 1);
    $pdf->Cell(0, 10, 'Total: Bs ' . number_format($recibo['total'], 2, '.', ','), 0, 1);
    $pdf->Ln(10);

    // Detalle del recibo
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Detalle:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $detalle = str_replace('$', 'Bs', $recibo['detalle']);
    $pdf->MultiCell(0, 10, $detalle, 0, 1);

    if (!is_null($recibo['cambio'])) {
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'Cambio: Bs ' . number_format($recibo['cambio'], 2, '.', ','), 0, 1);
    }

    // Añadir borde alrededor de la sección de detalle
    $pdf->SetLineWidth(0.5);
    $pdf->SetDrawColor(0, 123, 255); // Color azul
    $pdf->Rect(10, 60, 190, 100);

    // Generar y descargar el PDF
    $pdf->Output('D', 'Recibo_' . $recibo['id_recibo'] . '.pdf');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Compra</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .recibo-container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
    }

    .recibo-header {
        text-align: center;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .recibo-header h1 {
        margin: 0;
        font-size: 24px;
        color: #007BFF;
    }

    .recibo-details {
        margin-bottom: 20px;
    }

    .recibo-details p {
        margin: 5px 0;
        font-size: 16px;
    }

    .recibo-details h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #333333;
    }

    .download-button {
        display: block;
        width: 100%;
        text-align: center;
        background-color: #007BFF;
        color: #ffffff;
        padding: 10px 0;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .download-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="recibo-container">
        <div class="recibo-header">
            <h1>Recibo de Compra</h1>
        </div>
        <div class="recibo-details">
            <p><strong>ID Recibo:</strong> <?php echo $recibo['id_recibo']; ?></p>
            <p><strong>Fecha:</strong> <?php echo $recibo['fecha']; ?></p>
            <p><strong>Total:</strong> Bs <?php echo number_format($recibo['total'], 2, '.', ','); ?></p>
            <h3>Detalle:</h3>
            <p><?php echo nl2br(str_replace('$', 'Bs', $recibo['detalle'])); ?></p>

            <?php if (!is_null($recibo['cambio'])): ?>
            <p><strong>Cambio:</strong> Bs <?php echo number_format($recibo['cambio'], 2, '.', ','); ?></p>
            <?php endif; ?>
        </div>

        <!-- Botón para descargar el PDF -->
        <form method="post">
            <button type="submit" name="download_pdf" class="download-button">Descargar Recibo en PDF</button>
        </form>
    </div>
</body>

</html>