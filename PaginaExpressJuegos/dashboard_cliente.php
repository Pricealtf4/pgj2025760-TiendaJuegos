<?php
include 'config.php';

$sql = "SELECT id_juego, titulo, descripcion, precio, imagen, stock FROM juegos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cliente</title>
    <link rel="stylesheet" href="style_cliente.css">
    <style>
    .card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 16px;
        margin: 16px;
        text-align: center;
        position: relative;
    }

    .card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .card.out-of-stock {
        background-color: #d3d3d3;

        pointer-events: none;

    }

    .card.out-of-stock .btn {
        background-color: #a9a9a9;
        /* Gris más oscuro */
        cursor: not-allowed;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .logout-button {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #dc3545;
        color: #fff;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .logout-button:hover {
        background-color: #c82333;
    }
    </style>
</head>

<body>
    <h1>Bienvenido al Dashboard del Cliente</h1>

    <a href="logout.php" class="logout-button">Cerrar Sesión</a>

    <h2>Lista de Juegos Disponibles</h2>
    <div id="juegosContainer">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $outOfStockClass = $row['stock'] == 0 ? 'out-of-stock' : '';
                $buttonText = $row['stock'] == 0 ? 'Sin Stock' : 'Realizar Compra';
                $buttonDisabled = $row['stock'] == 0 ? 'disabled' : '';
                
                echo '<div class="card ' . $outOfStockClass . '">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<p>' . $row['descripcion'] . '</p>';
                echo '<p>Precio: Bs. ' . number_format($row['precio'], 2, '.', ',') . '</p>';
                echo '<a href="detalle_orden.php?id_juego=' . $row['id_juego'] . '" class="btn" ' . $buttonDisabled . '>' . $buttonText . '</a>';
                echo '</div>';
            }
        } else {
            echo "No hay juegos disponibles.";
        }
        ?>
    </div>

    <script src="dashboard_cliente.js"></script>
</body>

</html>

<?php
$conn->close();
?>