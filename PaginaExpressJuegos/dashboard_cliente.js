document.addEventListener('DOMContentLoaded', function() {
    const juegosSelect = document.getElementById('juego');
    const ordenForm = document.getElementById('ordenForm');

    // Cargar los juegos disponibles
    fetch('get_juegos.php')
        .then(response => response.json())
        .then(juegos => {
            juegos.forEach(juego => {
                const option = document.createElement('option');
                option.value = juego.id_juego;
                option.textContent = `${juego.titulo} - Bs.${juego.precio}`;
                juegosSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));

    // Manejar el envío del formulario de orden
    ordenForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const juegoId = juegosSelect.value;
        const cantidad = document.getElementById('cantidad').value;

        fetch('realizar_orden.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                juegoId: juegoId,
                cantidad: cantidad
            })
        })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                alert('Orden realizada con éxito!');
            } else {
                alert('Error al realizar la orden.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
