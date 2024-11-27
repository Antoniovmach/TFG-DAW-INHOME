$(function () {
    var jsonArrayProgramacion = [];
    var crearRutaButton = $('#crearRutaButton');
    var crearRutayTourButton = $('#crearRutayToursButton');

    // crearRutaButton.on('click', function () {

    //     console.log(jsonArrayProgramacion);
    //     var latitud = $('#latitudIni').text();
    //     var longitud = $('#longitudIni').text();

    //     fecha_ini = $('#startDate').val()
    //     fecha_fin = $('#endDate').val()

    //     console.log($('#tituloruta').val(), $('.richText-editor').html());

    //     var titulo = $('#tituloruta').val()
    //     descripcion = $('.richText-editor').html()
    //     var punto_inicio = {
    //         latitud: latitud,
    //         longitud: longitud
    //     };
    //     foto = "foto"
    //     var fecha_ini_formateada = new Date(fecha_ini).toLocaleDateString('en-GB', {
    //         day: '2-digit',
    //         month: '2-digit',
    //         year: 'numeric'
    //     });
    //     var fecha_fin_formateada = new Date(fecha_fin).toLocaleDateString('en-GB', {
    //         day: '2-digit',
    //         month: '2-digit',
    //         year: 'numeric'
    //     });

    //     aforo = $('#afororuta').val()




    //     var programacion = {
    //         jsonArrayProgramacion
    //     };

    //     var listaItems = $('#sortable1 li');
    //     var listaItemsArray = [];

    //     listaItems.each(function () {
    //         listaItemsArray.push($(this).attr('id'));
    //     });

    //     console.log(listaItemsArray);



    //     var datosPost = {
    //         "titulo": titulo,
    //         "descripcion": descripcion,
    //         "foto": foto,
    //         "punto_inicio": punto_inicio,
    //         "fecha_ini": fecha_ini_formateada,
    //         "fecha_fin": fecha_fin_formateada,
    //         "aforo": aforo,
    //         "programacion": programacion,
    //         "listaItems": listaItemsArray,

    //     };


    //     $.ajax({
    //         url: '/ruta/crear',
    //         type: 'POST',
    //         contentType: 'application/json',
    //         data: JSON.stringify(datosPost),
    //         dataType: 'json',
    //         success: function (data) {
    //             console.log('Respuesta del servidor:', data);
    //             alert("ruta creada correctamente")
    //         },
    //         error: function (error) {
    //             console.error('Error en la solicitud:', error.responseText);
    //             alert("error al crear la ruta consulte la consola")
    //         }
    //     });
    // });



    crearRutaButton.on('click', function () {

       
        var latitud = $('#latitudIni').text();
        var longitud = $('#longitudIni').text();

        fecha_ini = $('#startDate').val()
        fecha_fin = $('#endDate').val()

        console.log($('#tituloruta').val(), $('.richText-editor').html());

        var titulo = $('#tituloruta').val()
        descripcion = $('.richText-editor').html()
        var punto_inicio = {
            latitud: latitud,
            longitud: longitud
        };
        // foto = "foto"
        // foto="";
        foto = $("input[type=file]")[0].files[0];
        var fecha_ini_formateada = new Date(fecha_ini).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        var fecha_fin_formateada = new Date(fecha_fin).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        aforo = $('#afororuta').val()




        var programacion = {
            jsonArrayProgramacion
        };

        var listaItems = $('#sortable1 li');
        var listaItemsArray = [];

        listaItems.each(function () {
            listaItemsArray.push($(this).attr('id'));
        });


     




        if (titulo !== ""){

            if (aforo !== "" && aforo > 0){

                if (fecha_ini !== "" && fecha_fin !== ""){

                    if (foto !== undefined){
           
                        var formdata = new FormData()
                        formdata.append("titulo", titulo)
                        formdata.append("descripcion", descripcion)
                        formdata.append("foto", foto, foto.name)
                        formdata.append("punto_inicio", JSON.stringify(punto_inicio))
                        formdata.append("fecha_ini", fecha_ini)
                        formdata.append("fecha_fin", fecha_fin)
                        formdata.append("aforo", aforo)
                        formdata.append("programacion", JSON.stringify(programacion))
                        formdata.append("listaItems", JSON.stringify(listaItemsArray));
                        // formdata.append("listaItems" ,JSON.stringify(listaItems))
      

        $.ajax({
            url: '/ruta/crear',
            type: 'POST',
            contentType: 'application/json',
            data: formdata,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log('Respuesta del servidor:', data);
                alert("ruta creada correctamente")



                var listaItems = $('#sortable1 li');
                var listaItemsArray = [];

                listaItems.each(function () {
                    listaItemsArray.push($(this).attr('id'));
                });

                console.log(listaItemsArray);



                var datosPost = {
                 
                    "id": data.message,
                    "listaItems": listaItemsArray,

                };


                $.ajax({
                    url: '/ruta_item/crear',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(datosPost),
                    dataType: 'json',
                    success: function (data) {
                        console.log('Respuesta del servidor:', data);
                        alert("ruta creada correctamente")
                    },
                    error: function (error) {
                        console.error('Error en la solicitud:', error.responseText);
                        alert("error al crear la ruta consulte la consola")
                    }
                });
                //-------------





            },
            error: function (error) {
                console.error('Error en la solicitud:', error.responseText);
                alert("error al crear la ruta consulte la consola")
            }
        });


    }else{
        alert("Debe incluirse una foto")
    }

    }else{
        alert("La fecha debe de incluirse")
    }

    }else{
        alert("el aforo debe ser mayor a 0")
    }

    }else{
        alert("El titulo no puede ser un campo vacio")}
    });


    //----
   




    crearRutayTourButton.on('click', function () {
        var latitud = $('#latitudIni').text();
        var longitud = $('#longitudIni').text();
    
        fecha_ini = $('#startDate').val()
        fecha_fin = $('#endDate').val()
    
        console.log($('#tituloruta').val(), $('.richText-editor').html());
    
        var titulo = $('#tituloruta').val()
        descripcion = $('.richText-editor').html()
        var punto_inicio = {
            latitud: latitud,
            longitud: longitud
        };
    
        foto = $("input[type=file]")[0].files[0];
        var fecha_ini_formateada = new Date(fecha_ini).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        var fecha_fin_formateada = new Date(fecha_fin).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    
        aforo = $('#afororuta').val()


        


    
        var programacion = {
            jsonArrayProgramacion: jsonArrayProgramacion 
        };
    
        var listaItems = $('#sortable1 li');
        var listaItemsArray = [];
    
        listaItems.each(function () {
            listaItemsArray.push($(this).attr('id'));
        });
    
        var formdata = new FormData()
        formdata.append("titulo", titulo)
        formdata.append("descripcion", descripcion)
        formdata.append("foto", foto, foto.name)
        formdata.append("punto_inicio", JSON.stringify(punto_inicio))
        formdata.append("fecha_ini", fecha_ini)
        formdata.append("fecha_fin", fecha_fin)
        formdata.append("aforo", aforo)
        formdata.append("programacion", JSON.stringify(programacion))
        formdata.append("listaItems", JSON.stringify(listaItemsArray));
    
        $.ajax({
            url: '/ruta/crear',
            type: 'POST',
            contentType: false,
            processData: false,
            data: formdata,
            success: function (data) {
                console.log('Respuesta del servidor:', data);
                alert("ruta creada correctamente")
    
                var listaItems = $('#sortable1 li');
                var listaItemsArray = [];
    
                listaItems.each(function () {
                    listaItemsArray.push($(this).attr('id'));
                });
    
                var datosPost = {
                    "id": data.message,
                    "listaItems": listaItemsArray,
                };
    
                $.ajax({
                    url: '/ruta_item/crear',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(datosPost),
                    dataType: 'json',
                    success: function (data) {
                        console.log('Respuesta del servidor:', data);
                        alert("ruta creada correctamente")
                        var datosgenerar = {
                            "id": data.message,
                            jsonArrayProgramacion: jsonArrayProgramacion 
                        };
    
                        console.log(datosgenerar);
    
                        $.ajax({
                            url: '/tour/crear',
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(datosgenerar),
                            dataType: 'json',
                            success: function (data) {
                                console.log('Respuesta del servidor:', data);
                                console.log(data.message);
    
                                alert("programacion creada correctamente")
                            },
                            error: function (error) {
                                console.error('Error en la solicitud:', error.responseText);
                                alert("error al crear la ruta consulte la consola")
                            }
                        });
                    },
                    error: function (error) {
                        console.error('Error en la solicitud:', error.responseText);
                        alert("error al crear la ruta consulte la consola")
                    }
                });
            },
            error: function (error) {
                console.error('Error en la solicitud:', error.responseText);
                alert("error al crear la ruta consulte la consola")
            }
        });
    });



    var btnAgregar = $('#btnAgregar');
    i = 0

    btnAgregar.on('click', function () {
        i = i + 1
        validadordiasemana = 0


        var diasdelasemana = '';

        if ($('#btn-lunes').is(':checked')) {
            diasdelasemana += $('#btn-lunes + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-martes').is(':checked')) {
            diasdelasemana += $('#btn-martes + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-miercoles').is(':checked')) {
            diasdelasemana += $('#btn-miercoles + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-jueves').is(':checked')) {
            diasdelasemana += $('#btn-jueves + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-viernes').is(':checked')) {
            diasdelasemana += $('#btn-viernes + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-sabado').is(':checked')) {
            diasdelasemana += $('#btn-sabado + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }
        if ($('#btn-domingo').is(':checked')) {
            diasdelasemana += $('#btn-domingo + label').text() + ',';
            validadordiasemana = validadordiasemana + 1;
        }

        // Elimina la última coma si hay días seleccionados
        if (diasdelasemana.length > 0) {
            diasdelasemana = diasdelasemana.slice(0, -1);
        }



        nombreguia = $('#UsuariosSelect option:selected').text();
        idguia = $('#UsuariosSelect').val()

        startDateProg = $('#startDateProg').val()
        endDateProg = $('#endDateProg').val()

        precio = $('#precioinput').val()

        horaProg = $('#horaProg').val()
        minutoProg = $('#minutoProg').val()

        var numeroFilas = $("#tablaprog tbody tr").length;



        console.log(nombreguia + " " + idguia)
        console.log(diasdelasemana)
        console.log(startDateProg + " - " + endDateProg)
        console.log(precio)
        console.log(horaProg + ":" + minutoProg)




        var nuevaFila = '<tr id="fila' + i + '">' +
            '<td id="diasSemana' + i + '" class="diasSemana">' + diasdelasemana + '</td>' +
            '<td id="temporadaInicio' + i + '" class="temporadaIni">' + startDateProg + '</td>' +
            '<td id="temporadaFinal' + i + '" class="temporadaFin">' + endDateProg + '</td>' +
            '<td id="precio' + i + '" class="precio">' + precio + '</td>' +
            '<td><button class="btn btn-danger btnEliminar" data-indice="' + i + '">Eliminar</button></td>' +
            '</tr>';

        if (validadordiasemana != 0) {
            if (startDateProg !== "" && endDateProg !== "") {
              
                    $('#bodydias').append(nuevaFila);



                    console.log("Número de filas: " + numeroFilas);



                    jsonArrayProgramacion = [];
                    for (var j = 0; j < numeroFilas + 1; j++) {
                        var filaActual = $("#tablaprog tbody tr").eq(j);

                       
                        var diassemanaSeleccionada = filaActual.find("td.diasSemana").text();
                        var arrayDiasSemana = filaActual.find("td.diasSemana").text();
                        // var arrayDiasSemana = diassemanaSeleccionada.split(',');
                        var tiniSeleccionada = filaActual.find("td.temporadaIni").text();
                        var tfinSeleccionada = filaActual.find("td.temporadaFin").text();


                        var jsonData = {
                            diasSemana: arrayDiasSemana,
                            temporadaIni: tiniSeleccionada,
                            temporadaFin: tfinSeleccionada
                        };


                        jsonArrayProgramacion.push(jsonData);



                    }

                    console.log(jsonArrayProgramacion);



               
            } else {
                alert("Debe seleccionar una fecha de inicio y fin");
            }
        } else {
            alert("Debe seleccionar por lo menos un día de la semana");
        }


        $(document).on('click', '.btnEliminar', function () {
            var indice = $(this).data('indice');
            $('#fila' + indice).remove();
        });


    });



    // programacion


});