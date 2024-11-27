
      document.addEventListener('DOMContentLoaded', function () {
        var select = document.getElementById('select-categorias');
        var tabla = document.getElementById('tabla-categorias');
        var categoriasAgregadas = []; 

        // Obtener categorías del endpoint
        fetch('http://localhost:8000/categoria')
          .then(response => response.json())
          .then(data => {
            console.log(data); // Imprimir los datos en la consola del navegador
            data.forEach(categoria => {
              var option = document.createElement('option');
              option.value = categoria.id;
              option.text = categoria.nombre;
              select.add(option);
            });
         
            select.id = data[0].id; // Asigna el primer ID de categoría obtenido del endpoint
          })
          .catch(error => {
            console.error('Error al obtener las categorías:', error);
          });

        document.getElementById('agregar-categoria').addEventListener('click', function () {
          var categoriaSeleccionada = select.value;
          var categoriaNombre = select.options[select.selectedIndex].text;
          if (categoriaSeleccionada && categoriasAgregadas.indexOf(categoriaSeleccionada) === -1) {
            var row = tabla.insertRow();
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.innerHTML = categoriaNombre;
            cell2.innerHTML = '<button class="btn btn-danger eliminar-categoria">Eliminar</button>';
            cell1.id = categoriaSeleccionada; 
            categoriasAgregadas.push(categoriaSeleccionada); // Agregar categoría al registro

            // Agrega el evento de clic al botón eliminar
            cell2.querySelector('.eliminar-categoria').addEventListener('click', function () {
              row.remove();
              categoriasAgregadas.splice(categoriasAgregadas.indexOf(categoriaSeleccionada),
                1); // Eliminar categoría del registro
            });
          }
        });
      });
  
