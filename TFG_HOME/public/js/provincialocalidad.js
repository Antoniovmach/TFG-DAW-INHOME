$(function () {
    

let todasLasLocalidades = [];


function getProvincias() {
    fetch('/Provincia')
        .then(response => response.json())
        .then(data => {
         
            displayProvincias(data);
        })
        .catch(error => {
            console.error('Error al obtener las Provincias:', error);
        });
}

function displayProvincias(Provincias) {
    const ProvinciasSelect = document.getElementById('ProvinciasSelect');



    const optionvacio = document.createElement('option');
    optionvacio.value = "-2";
    optionvacio.textContent = "";
    ProvinciasSelect.appendChild(optionvacio);
    
   
    // const optiontodos = document.createElement('option');
    // optiontodos.value = "-1";
    // optiontodos.textContent = "Todos los item";
    // ProvinciasSelect.appendChild(optiontodos);
    

    Provincias.forEach(Provincia => {
        const option = document.createElement('option');
        option.value = Provincia.id;
        option.textContent = Provincia.nombre;
      
        ProvinciasSelect.appendChild(option);
    });

    getTodasLasLocalidades();
}

function getTodasLasLocalidades() {
    fetch('/Localidad')
        .then(response => response.json())
        .then(data => {

            todasLasLocalidades = data;


            getLocalidades();
        })
        .catch(error => {
            console.error('Error al obtener todas las Localidades:', error);
        });
}


function getLocalidades() {

    const ProvinciasSelect = document.getElementById('ProvinciasSelect');
    const ProvinciaId = ProvinciasSelect.value;


    const LocalidadesFiltradas = todasLasLocalidades.filter(Localidad => Localidad.Provincia_id == ProvinciaId);


    displayLocalidades(LocalidadesFiltradas);
}


function displayLocalidades(Localidades) {
    const LocalidadesSelect = document.getElementById('LocalidadesSelect');
    LocalidadesSelect.innerHTML = ''; 

    if (ProvinciasSelect.value !=-1 ) {
        // alert(ProvinciasSelect.value)
        const optiontodos = document.createElement('option');
        optiontodos.value = "0";
        optiontodos.textContent = ""
        LocalidadesSelect.appendChild(optiontodos);
     
    }
    

    Localidades.forEach(Localidad => {
        const option = document.createElement('option');
        option.value = Localidad.id;
        option.textContent = Localidad.nombre;
        LocalidadesSelect.appendChild(option);
    });
}


function filtrarLocalidadesPorProvincia() {
   
    getLocalidades();
}

$("#ProvinciasSelect").on("change",function () {

    filtrarLocalidadesPorProvincia()
    
})



getProvincias();


})