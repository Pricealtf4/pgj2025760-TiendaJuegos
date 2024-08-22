<?php
include 'config.php';

session_start();

if (!isset($_GET['id_juego'])) {
    die('Error: Juego no especificado.');
}

$id_juego = $_GET['id_juego'];

// Consultar los detalles del juego seleccionado
$sql = "SELECT titulo, descripcion, precio, stock FROM juegos WHERE id_juego = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_juego);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $juego = $result->fetch_assoc();
} else {
    die('Error: Juego no encontrado.');
}

$mostrarRecibo = false; // Variable para mostrar el botón de recibo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cantidad = $_POST['cantidad'];
    $precio_total = $cantidad * $juego['precio'];

    // Insertar la orden
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de que la sesión almacene el ID del usuario
    $sql = "INSERT INTO ordenes (id_usuario, total) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $id_usuario, $precio_total);
    $stmt->execute();
    $id_orden = $stmt->insert_id;

    // Insertar el detalle de la orden
    $sql = "INSERT INTO detalle_ordenes (id_orden, id_juego, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $id_orden, $id_juego, $cantidad, $juego['precio']);
    $stmt->execute();

    // Actualizar el stock del juego
    $nuevo_stock = $juego['stock'] - $cantidad;
    $sql = "UPDATE juegos SET stock = ? WHERE id_juego = ?";
    $stmt->prepare($sql);
    $stmt->bind_param("ii", $nuevo_stock, $id_juego);
    $stmt->execute();

    // Insertar el recibo
    $detalle_recibo = "Juego: " . $juego['titulo'] . ", Cantidad: " . $cantidad . ", Precio Total: $" . $precio_total;
    $sql = "INSERT INTO recibos (id_orden, total, detalle) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $id_orden, $precio_total, $detalle_recibo);
    $stmt->execute();
    $id_recibo = $stmt->insert_id;

    $mostrarRecibo = true; // Ahora mostramos el botón de recibo
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Orden</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 100%;
    }

    h1,
    h2 {
        color: #333;
    }

    p {
        font-size: 16px;
        line-height: 1.5;
    }

    .form-group {
        margin: 15px 0;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-group input[type="number"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        background-color: #28a745;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        width: 100%;
    }

    button:hover {
        background-color: #218838;
    }

    .success {
        color: #28a745;
        font-weight: bold;
        position: fixed;
        /* Posicionar el mensaje en el costado derecho */
        top: 20px;
        right: 20px;
        background-color: #fff;
        /* Fondo blanco para destacar */
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        /* Asegurarse de que el mensaje esté encima de otros elementos */
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Detalle de Orden</h1>
        <h2><?php echo $juego['titulo']; ?></h2>
        <p><?php echo $juego['descripcion']; ?></p>
        <p>Precio: Bs.<?php echo $juego['precio']; ?></p>
        <p>Stock disponible: <?php echo $juego['stock']; ?></p>

        <form method="post">
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" min="1" max="<?php echo $juego['stock']; ?>"
                    required>
            </div>
            <button type="submit">Confirmar Compra</button>
        </form>

        <?php if ($mostrarRecibo): ?>
        <button onclick="showReciboAndRedirect(<?php echo $id_recibo; ?>)">Mostrar Recibo</button>
        <?php endif; ?>
    </div>

    <?php if ($mostrarRecibo): ?>
    <div class="success">¡Compra realizada con éxito!</div>
    <?php endif; ?>

    <script>
    function showReciboAndRedirect(idRecibo) {
        // Abrir el recibo en una nueva ventana
        window.open('recibo.php?id_recibo=' + idRecibo, '_blank');

        // Redirigir a dashboard_cliente.php después de 1 segundo
        setTimeout(function() {
            window.location.href = 'dashboard_cliente.php';
        }, 1000);
    }
    </script>
</body>

</html>

<?php
$conn->close();
?>