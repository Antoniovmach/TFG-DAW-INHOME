$(function() {
  initMap();
});

var map; // Variable global para el objeto del mapa
var marker; // Variable global para el marcador

function initMap() {
  var viviendaId = obtenerIdVivienda(); // Obtener el ID de la vivienda desde la URL
  var apiUrl = 'http://localhost:8000/api/viviendas/' + viviendaId;

  // Realizar una solicitud AJAX para obtener la vivienda por su ID
  $.ajax({
    url: apiUrl,
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      // Acceder a la latitud y longitud de la vivienda
      var latitud = data.latitud;
      var longitud = data.longitud;

      // Crear un nuevo mapa con Leaflet
      map = L.map('mapa').setView([latitud, longitud], 15);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

      // Inicializar el marcador con la ubicación proporcionada
      marker = L.marker([latitud, longitud]).addTo(map);

      // Popup opcional para mostrar información en el marcador
      marker.bindPopup(data.titulo).openPopup();
    },
    error: function() {
      console.error('Error al obtener la vivienda por su ID');
    }
  });
}

// Función para obtener el ID de la vivienda desde la URL
function obtenerIdVivienda() {
  var ruta = window.location.href;
  var partes = ruta.split('/');
  return partes[partes.length - 1]; // El ID estará en la última parte de la URL
}

function geocodificarDireccion() {
  var direccion = $("#ini-ruta").val();
  var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(direccion);

  $.ajax({
    url: nominatimURL,
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      if (data.length > 0) {
        var latitud = data[0].lat;
        var longitud = data[0].lon;

        // Actualizar la posición del marcador
        marker.setLatLng([latitud, longitud])
          .bindPopup('Ubicación: ' + direccion)
          .openPopup();

        // Centrar el mapa en la nueva posición del marcador
        map.setView([latitud, longitud], 15);
      } else {
        alert("No se encontraron coordenadas para la dirección proporcionada.");
      }
    },
    error: function() {
      alert("Error al obtener las coordenadas. Por favor, inténtalo de nuevo.");
    }
  });
}
