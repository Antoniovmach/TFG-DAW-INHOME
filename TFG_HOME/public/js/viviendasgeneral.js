document.addEventListener('DOMContentLoaded', function () {
  const urlParams = new URLSearchParams(window.location.search);
  const provinciaId = urlParams.get('provinciaId');
  const localidadId = urlParams.get('localidadId');
  const fechaInicio = urlParams.get('fechaInicio');
  const fechaFin = urlParams.get('fechaFin');

  let apiUrl = '/api/viviendas';

  // Construir la URL de la API según los parámetros presentes
  if (provinciaId || localidadId || fechaInicio || fechaFin) {
      apiUrl += '/filtro?';

      if (provinciaId) {
          apiUrl += `provinciaId=${provinciaId}&`;
      }
      if (localidadId) {
          apiUrl += `localidadId=${localidadId}&`;
      }
      if (fechaInicio) {
          apiUrl += `fechaInicio=${fechaInicio}&`;
      }
      if (fechaFin) {
          apiUrl += `fechaFin=${fechaFin}`;
      }

      // Eliminar el último '&' si existe
      apiUrl = apiUrl.replace(/&$/, '');
  }

  fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
          const propertiesContainer = document.getElementById('properties-container');
          propertiesContainer.innerHTML = '';

          data.forEach(property => {
              const imageUrl = property.vivienda_fotos.length > 0 ? property.vivienda_fotos[0].foto_url : 'default.jpg';
            //   const categorias = property.categorias.slice(0, 3);
            //   const hasMoreCategories = property.categorias.length > 3;
            //   const moreCategoriesHTML = hasMoreCategories ? '<li>...</li>' : '';

              const propertyHTML = `
                  <div class="col-md-4 wow-outer">
                      <article class="post-modern wow slideInLeft">
                          <a href="/viviendas/${property.id}">
                              <div class="post-modern-media" style="width: 370px; height: 228px; overflow: hidden;">
                                  <img src="/uploads/images/${imageUrl}" alt="" width="370" height="228" style="object-fit: cover;" />
                              </div>
                          </a>
                      </article>
                      <h5 class="post-modern-title"><a class="post-modern-title" href="/viviendas/${property.id}">${property.titulo}</a></h5>
                      <ul class="post-modern-meta">
                          <li>${property.localidad.nombre}</li>
                          <li>${property.provincia.nombre}</li>
                          <li>${property.npersonas} personas</li>
                      </ul>
                      <p>${property.descripcion}</p>
                  </div>
              `;
              propertiesContainer.insertAdjacentHTML('beforeend', propertyHTML);
          });
      })
      .catch(error => console.error('Error fetching properties:', error));
});