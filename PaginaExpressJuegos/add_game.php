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

// Obtener datos del formulario
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

// Manejar la subida de la imagen
$target_dir = "img/"; // Cambiado de "uploads/" a "img/"
$target_file = $target_dir . basename($_FILES["imagen"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Checkear si es una imagen real
$check = getimagesize($_FILES["imagen"]["tmp_name"]);
if ($check !== false) {
    // Subir la imagen
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        // Insertar nuevo juego
        $sql = "INSERT INTO juegos (titulo, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $titulo, $descripcion, $precio, $stock, $target_file);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "El archivo no es una imagen.";
}


$conn->close();
?>