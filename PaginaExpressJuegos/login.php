<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Verificar las credenciales
    $sql = "SELECT id_usuario, contraseña, tipo_usuario FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_usuario, $hashed_password, $tipo_usuario);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($contraseña, $hashed_password)) {
        session_start();
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['tipo_usuario'] = $tipo_usuario;

        if ($tipo_usuario == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($tipo_usuario == 'cliente') {
            header("Location: dashboard_cliente.php"); // Cambio realizado aquí
        }
        exit(); // Asegúrate de llamar a exit después de redirigir
    } else {
        echo 'Correo o contraseña incorrectos.';
    }

    $stmt->close();
    $conn->close();
}
?>
