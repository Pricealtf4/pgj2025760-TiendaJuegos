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

// Obtener datos del formulario
$id_juego = $_POST['id_juego'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

// Manejar la subida de la imagen
$target_file = null;
if ($_FILES['imagen']['size'] > 0) {
    $target_dir = "img/"; // Cambiado de "uploads/" a "img/"
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Checkear si es una imagen real
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if ($check !== false) {
        // Subir la imagen
        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            echo "Error al subir la imagen.";
            exit();
        }
    } else {
        echo "El archivo no es una imagen.";
        exit();
    }
}

// Actualizar juego
$sql = "UPDATE juegos SET titulo=?, descripcion=?, precio=?, stock=?, imagen=IFNULL(?, imagen) WHERE id_juego=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdisi", $titulo, $descripcion, $precio, $stock, $target_file, $id_juego);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>