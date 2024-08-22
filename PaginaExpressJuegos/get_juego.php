<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "games";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_juego = $_GET['id_juego'];

// Obtener detalles del juego
$sql = "SELECT * FROM juegos WHERE id_juego = $id_juego";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(array());
}

$conn->close();
?>