<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Validar el formato del correo en el servidor
    $allowed_domains = ['gmail.com', 'hotmail.com', 'univalle.com', 'gmail.bo', 'hotmail.bo', 'univalle.bo'];
    $correo_domain = substr(strrchr($correo, "@"), 1);

    if (!in_array($correo_domain, $allowed_domains)) {
        echo "El correo debe finalizar con uno de los siguientes dominios: gmail.com, hotmail.com, univalle.com, gmail.bo, hotmail.bo, univalle.bo.";
        exit;
    }

    // Verificar si el correo ya está registrado
    $sql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Este correo ya está registrado.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, 'cliente')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $correo, $contraseña);

        if ($stmt->execute()) {
            echo "Registro exitoso!";
            header("Location: index.html");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>