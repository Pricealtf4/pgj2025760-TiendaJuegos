<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "games";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_juego = $_POST['id_juego'];

// Validar entrada
if (!is_numeric($id_juego)) {
    echo "Error: ID de juego inválido.";
    exit();
}

// Eliminar juego
$sql = "DELETE FROM juegos WHERE id_juego=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_juego);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>