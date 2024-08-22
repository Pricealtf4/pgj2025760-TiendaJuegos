<?php
include 'config.php';

// Datos del nuevo usuario
$nombre = 'Jose Price Gutierrez';
$correo = 'priceadmin@univalle.com';
$contraseña = 'qwerty1234'; // La contraseña en texto plano
$tipo_usuario = 'admin';

// Encriptar la contraseña
$hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);

// Insertar el nuevo usuario en la base de datos
$sql = "INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $hashed_password, $tipo_usuario);

if ($stmt->execute()) {
    echo "Usuario administrador creado exitosamente!";
} else {
    echo "Error al crear el usuario: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
