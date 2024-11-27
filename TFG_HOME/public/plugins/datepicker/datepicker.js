
$(function() {


            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '&#x3C;Ant',
                nextText: 'Sig&#x3E;',
                currentText: 'Hoy',
                monthNames: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
                monthNamesShort: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
                dayNames: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
                dayNamesShort: ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'],
                dayNamesMin: ['D', 'L', 'M', 'Mi', 'J', 'V', 'S'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            
            $.datepicker.setDefaults($.datepicker.regional['es']);

            $("#startDate").datepicker({
                onSelect: function (selectedDate) {
                    $("#endDate").datepicker("option", "minDate", selectedDate);
                }
            });

            $("#endDate").datepicker({
                onSelect: function (selectedDate) {
                    $("#startDate").datepicker("option", "maxDate", selectedDate);
                }
            });

            $("#startDateProg").datepicker({
                onSelect: function (selectedDate) {
                    $("#endDateProg").datepicker("option", "minDate", selectedDate);
                }
            });
        
            $("#endDateProg").datepicker({
                onSelect: function (selectedDate) {
                    $("#startDateProg").datepicker("option", "maxDate", selectedDate);
                }
            });
    

        var tituloElemento = document.querySelector('.content-header-title .title');


        tituloElemento.textContent = 'Creacion de rutas';


        $(".content").append($("#todoitem"))
        })

