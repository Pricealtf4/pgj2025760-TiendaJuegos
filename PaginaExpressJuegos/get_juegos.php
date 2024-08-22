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

// Obtener juegos
$sql = "SELECT * FROM juegos";
$result = $conn->query($sql);

$juegos = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $juegos[] = $row;
    }
}

echo json_encode($juegos);

$conn->close();
?>