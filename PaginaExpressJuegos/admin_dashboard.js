document.addEventListener('DOMContentLoaded', function() {
    const addGameForm = document.getElementById('addGameForm');
    const editGameForm = document.getElementById('editGameForm');
    const gamesList = document.getElementById('gamesList');
    const editGameModal = document.getElementById('editGameModal');
    const closeModal = document.querySelector('.close');

    // Cargar los juegos disponibles
    function loadGames() {
        fetch('get_juegos.php')
            .then(response => response.json())
            .then(juegos => {
                gamesList.innerHTML = ''; // Limpiar la lista antes de cargar
                juegos.forEach(juego => {
                    const gameDiv = document.createElement('div');
                    gameDiv.className = 'game-item';
                    gameDiv.innerHTML = `
                        <p><strong>${juego.titulo}</strong> - Bs.${juego.precio}</p>
                        <p>${juego.descripcion}</p>
                        <p>Stock: ${juego.stock}</p>
                        <img src="${juego.imagen}" alt="${juego.titulo}">
                        <button onclick="editGame(${juego.id_juego})">Editar</button>
                        <button onclick="deleteGame(${juego.id_juego})">Eliminar</button>
                        <hr>
                    `;
                    gamesList.appendChild(gameDiv);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    addGameForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addGameForm);
        fetch('add_game.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            console.log(result); // Agregar esto para ver la respuesta
            if (result === 'success') {
                alert('Juego agregado exitosamente!');
                loadGames(); // Recargar la lista de juegos
                addGameForm.reset(); // Limpiar el formulario
            } else {
                alert('Error al agregar el juego.');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Función para mostrar el modal de edición
    window.editGame = function(id_juego) {
        fetch(`get_juego.php?id_juego=${id_juego}`)
            .then(response => response.json())
            .then(juego => {
                document.getElementById('edit_id_juego').value = juego.id_juego;
                document.getElementById('edit_titulo').value = juego.titulo;
                document.getElementById('edit_descripcion').value = juego.descripcion;
                document.getElementById('edit_precio').value = juego.precio;
                document.getElementById('edit_stock').value = juego.stock;
                document.getElementById('current_imagen').src = juego.imagen; // Mostrar la imagen actual
                document.getElementById('current_imagen').style.display = 'block'; // Asegurarse de que la imagen sea visible
                editGameModal.style.display = 'block'; // Mostrar el modal
            })
            .catch(error => console.error('Error:', error));
    }

    // Manejar el envío del formulario de editar juego
    editGameForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(editGameForm);
        fetch('edit_game.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                alert('Juego editado exitosamente!');
                loadGames(); // Recargar la lista de juegos
                editGameModal.style.display = 'none'; // Ocultar el modal
            } else {
                alert('Error al editar el juego.');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Cerrar el modal de edición
    closeModal.addEventListener('click', function() {
        editGameModal.style.display = 'none';
    });

    // Eliminar juego
    window.deleteGame = function(id_juego) {
        if (confirm('¿Estás seguro de que quieres eliminar este juego?')) {
            fetch('delete_game.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_juego=${id_juego}`
            })
            .then(response => response.text())
            .then(result => {
                if (result === 'success') {
                    alert('Juego eliminado exitosamente!');
                    loadGames(); // Recargar la lista de juegos
                } else {
                    alert('Error al eliminar el juego.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Cerrar sesión
    document.getElementById('logoutButton').addEventListener('click', function() {
        fetch('logout.php', {
            method: 'POST',
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                // Redirigir a index.html
                window.location.href = 'index.html';
            } else {
                alert('Cierre de sesion exitoso.');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Cargar juegos al inicio
    loadGames();
});