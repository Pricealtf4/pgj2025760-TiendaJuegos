<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    h1,
    h2 {
        color: #333;
    }

    form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin: 10px 0 5px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background: #0056b3;
    }

    #gamesList {
        margin: 20px 0;
    }

    .game-item {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
    }

    .game-item img {
        max-width: 200px;
        border-radius: 4px;
    }

    .game-item button {
        background: #28a745;
    }

    .game-item button:hover {
        background: #218838;
    }

    /* Estilos para el modal de edición */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .logout-button {
        background: #dc3545;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin: 10px 0;
    }

    .logout-button:hover {
        background: #dc3545;
    }
    </style>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <button id="logoutButton">Cerrar Sesión</button>
    <script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        fetch('logout.php', {
                method: 'POST',
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    alert('Error al cerrar sesión.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cerrar sesión.');
            });
    });
    </script>

    <h2>Agregar Nuevo Juego</h2>
    <form id="addGameForm" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="precio">Precio (Bs):</label>
        <input type="number" id="precio" name="precio" step="0.01" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>

        <button type="submit">Agregar Juego</button>
    </form>


    <h2>Lista de Juegos</h2>
    <div id="gamesList">
        <!-- La lista de juegos se cargará aquí mediante JavaScript -->
    </div>

    <!-- Modal para editar juegos -->
    <div id="editGameModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Juego</h2>
            <form id="editGameForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_id_juego" name="id_juego">
                <label for="edit_titulo">Título:</label>
                <input type="text" id="edit_titulo" name="titulo" required>

                <label for="edit_descripcion">Descripción:</label>
                <textarea id="edit_descripcion" name="descripcion" required></textarea>

                <label for="edit_precio">Precio (Bs):</label>
                <input type="number" id="edit_precio" name="precio" step="0.01" required>

                <label for="edit_stock">Stock:</label>
                <input type="number" id="edit_stock" name="stock" required>

                <label for="current_imagen">Imagen actual:</label>
                <img id="current_imagen" src="" alt="Imagen actual"
                    style="max-width: 200px; display: block; margin-bottom: 10px;">

                <label for="edit_imagen">Imagen (dejar en blanco para no cambiar):</label>
                <input type="file" id="edit_imagen" name="imagen" accept="image/*">

                <button type="submit">Actualizar Juego</button>
            </form>
        </div>
    </div>


    <script src="admin_dashboard.js"></script>
</body>

</html>