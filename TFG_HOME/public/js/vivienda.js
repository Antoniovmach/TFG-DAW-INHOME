// document.addEventListener("DOMContentLoaded", function() {
//     fetch('/api/viviendas')
//         .then(response => response.json())
//         .then(data => {
//             const container = document.querySelector('.section .container .row');
//             container.innerHTML = '';

//             data.forEach(vivienda => {
//                 const viviendaElement = document.createElement('div');
//                 viviendaElement.className = 'col-md-6 wow-outer';

//                 const fotos = vivienda.vivienda_fotos;
//                 const imgSrc = fotos.length > 0 ? fotos[0].foto_url : 'default.jpg';

//                 viviendaElement.innerHTML = `
//                     <article class="post-modern wow slideInLeft">
//                         <a class="post-modern-media" href="#">
//                             <img src="${imgSrc}" alt="${vivienda.titulo}" width="571" height="353" />
//                         </a>
//                     </article>
//                     <h4 class="post-modern-title">
//                         <a class="post-modern-title" href="#">${vivienda.titulo}</a>
//                     </h4>
//                     <ul class="post-modern-meta">
//                         <li><a class="button-winona" href="#">${vivienda.precio}/mon</a></li>
//                         <li>${vivienda.superficie} Sq. Ft.</li>
//                         <li>${vivienda.habitaciones} Bedrooms</li>
//                     </ul>
//                     <p>${vivienda.descripcion}</p>
//                 `;

//                 container.appendChild(viviendaElement);
//             });

            
//             new WOW().init();
//         })
//         .catch(error => console.error('Error viviendas:', error));
// });
