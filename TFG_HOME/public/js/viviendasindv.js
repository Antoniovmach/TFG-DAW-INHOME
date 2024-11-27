let totalPrecio = 0;
let eventosSeleccionados = [];

document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID de la vivienda desde la URL
    let url = window.location.href;
    let viviendaId = url.substring(url.lastIndexOf('/') + 1);

    $.ajax({
        url: `http://localhost:8000/api/viviendasdisponible/${viviendaId}`,
        type: 'GET',
        dataType: 'json',
        success: function(disponibilidades) {
            console.log('Disponibilidades:', disponibilidades);

            // Filtar fechas
            let fechaActual = new Date();
            let eventosFuturos = disponibilidades.filter(disponibilidad => new Date(disponibilidad.fecha) > fechaActual);

            let events = eventosFuturos.map(disponibilidad => ({
                id: disponibilidad.id_disponibilidad_vivienda,
                title: `\u00A0\u00A0\u00A0${disponibilidad.precio}c`,
                start: disponibilidad.fecha,
                extendedProps: {
                    precio: parseFloat(disponibilidad.precio) // Convertir el precio a número flotante
                }
            }));

            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                firstDay: 0, // Establece el domingo como el primer día de la semana
                selectable: true,
                select: function(info) {
                    // No hacer nada en select ya que estamos manejando clics en los eventos
                },
                unselect: function(info) {
                    // No hacer nada en unselect ya que estamos manejando clics en los eventos
                },
                eventClick: function(info) {
                    let event = info.event;
                    let tdElement = info.jsEvent.target.closest('td'); // Obtener el elemento <td> más cercano
                    if (eventosSeleccionados.some(e => e.id === event.id)) {
                        totalPrecio -= event.extendedProps.precio;
                        eventosSeleccionados = eventosSeleccionados.filter(e => e.id !== event.id);
                        tdElement.style.border = ""; // Revertir el estilo del borde
                    } else {
                        totalPrecio += event.extendedProps.precio;
                        eventosSeleccionados.push({
                            id: event.id,
                            fecha: event.start.toISOString().split('T')[0],
                            precio: event.extendedProps.precio
                        });
                        tdElement.style.border = "2px solid green"; // Establecer el borde del día seleccionado como verde
                    }
                    $('#total-precio').text(totalPrecio.toFixed(0)); // Redondear el total a dos decimales y mostrarlo
                    console.log('IDs de viviendas disponibilidad seleccionadas:', eventosSeleccionados); // Imprimir los IDs seleccionados en la consola
                }
            });

            calendar.render();
        },
        error: function(xhr, status, error) {
            var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Error en el servidor';
            console.error('Error en la solicitud:', error);
            alert("Error al obtener disponibilidades: " + errorMessage);
        }
    });

    // Agregar evento al botón "Alquilar"
    const botonalq = document.getElementById("alquilar");
    botonalq.addEventListener("click", function() {
        const UsuarioIdLabel = document.getElementById("idUsuario"); // Mover la obtención del elemento aquí
    
        if (UsuarioIdLabel) { // Verificar si el elemento existe antes de acceder a sus propiedades
            const UsuarioId = UsuarioIdLabel.textContent;
    
            const data = {
                Usuarioid: UsuarioId,
                disponibilidades: eventosSeleccionados
            };
    
            console.log(JSON.stringify(data, null, 2));
    
            fetch('/reservas/crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                } else {
                    console.log('Reserva creada:', data);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        } else {
            alert("Debe iniciar sesión");
        }
    });

// Agregar evento al botón "Intercambiar"
const botonint = document.getElementById("intercambiar");
botonint.addEventListener("click", function() {
    // Obtener el ID del usuario
    const UsuarioIdLabel = document.getElementById("idUsuario");
    if (UsuarioIdLabel) {
      
        const UsuarioId = UsuarioIdLabel.textContent;
        // Obtener las fechas de los eventos seleccionados y formatearlas a dd/mm/yyyy
        let fechas = eventosSeleccionados.map(evento => {
            let fecha = new Date(evento.fecha);
            let dia = fecha.getDate().toString().padStart(2, '0'); // Obtener el día, asegurándose de tener dos dígitos
            let mes = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Obtener el mes (los meses son indexados desde 0 en JavaScript)
            let anio = fecha.getFullYear();
            return `${dia}/${mes}/${anio}`;
        });

        // Realizar la solicitud POST a la API para obtener las viviendas
        fetch('http://localhost:8000/api/viviendasporusuario', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ usuarioId: parseInt(UsuarioId), fechas: fechas })
        })
        .then(response => response.json())
        .then(data => {
            // Mostrar viviendas disponibles en el modal
            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = ''; // Limpiar contenido previo del modal

            data.forEach(vivienda => {
                // Crear elementos HTML para cada vivienda
                const viviendaElement = document.createElement('div');
                viviendaElement.classList.add('d-flex', 'align-items-center', 'justify-content-between', 'mb-2');
                viviendaElement.innerHTML = `
                    <span style="display:none;">ID: ${vivienda.id}</span>
                    <span>Título: ${vivienda.titulo}</span>
                    <span style="display:none;">Disponibilidades: ${vivienda.disponibilidades.join(', ')}</span>
                    <button type="button" class="btn btn-primary btn-aceptar" data-id="${vivienda.id}" data-disponibilidades="${JSON.stringify(vivienda.disponibilidades)}">Aceptar</button>
                `;
                modalBody.appendChild(viviendaElement);

                // Añadir evento clic al botón de aceptar
                viviendaElement.querySelector('.btn-aceptar').addEventListener('click', function() {
                    const viviendaId = this.getAttribute('data-id');
                    const disponibilidades = JSON.parse(this.getAttribute('data-disponibilidades'));
                    
                    // Construir el objeto de datos para enviar al backend
                    const data = {
                        Usuarioid: UsuarioId,
                        disponibilidades: disponibilidades.map(id => ({ id: id })),
                        intercambiojson: { id: viviendaId } // Ajusta según lo que necesites enviar
                    };

                    console.log(data);

                    // Realizar la solicitud POST para crear el intercambio
                    fetch('http://localhost:8000/intercambio/crear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                        } else {
                            console.log('Intercambio creado:', data);
                          
                        }
                    })
                    .catch(error => {
                        console.error('Error al enviar la solicitud:', error);
                        alert('Error al realizar el intercambio.');
                    });
                });
            });

            // Mostrar el modal al hacer clic en el botón "Intercambiar"
            const intercambiarModal = new bootstrap.Modal(document.getElementById('intercambiarModal'));
            intercambiarModal.show();
        })
        .catch(error => {
            console.error('Error al enviar la solicitud:', error);
            alert('Error al obtener las viviendas disponibles.');
        });
    } else {
        alert("Debe iniciar sesión para realizar esta acción.");
    }
});
});