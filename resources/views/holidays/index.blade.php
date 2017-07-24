<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link rel='stylesheet' href='https://fullcalendar.io/js/fullcalendar-3.4.0/lib/cupertino/jquery-ui.min.css' />
<link href='https://fullcalendar.io/js/fullcalendar-3.4.0/fullcalendar.min.css' rel='stylesheet' />
<link href='https://fullcalendar.io/js/fullcalendar-3.4.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='https://fullcalendar.io/js/fullcalendar-3.4.0/lib/moment.min.js'></script>
<script src='https://fullcalendar.io/js/fullcalendar-3.4.0/lib/jquery.min.js'></script>
<script src='https://fullcalendar.io/js/fullcalendar-3.4.0/fullcalendar.min.js'></script>

<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css' />

<style>

    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 900px;
        margin: 0 auto;
    }

    .table-borderless > tbody > tr > td,
    .table-borderless > tbody > tr > th,
    .table-borderless > tfoot > tr > td,
    .table-borderless > tfoot > tr > th,
    .table-borderless > thead > tr > td,
    .table-borderless > thead > tr > th {
        border: none;
    }

    .cuadrado{
        width: 15px; 
        height: 15px; 
        display: inline-block;
        opacity: .3;
    }

    .yellow{
        background-color: yellow;
    }

    .blue{
        background-color: blue;
    }

    .red{
        background-color: red;
    }

    .black{
        background-color: black;
    }

    .validated{
        border-color: black;
        border-style: solid;
        border-width: 3px;
    }

</style>
</head>
<body>

<div id="app">

    <span v-if="contract.end_date == null">  
        <div class="row">
            @include('holidays.card')
            @include('holidays.key')
            <div id='calendar' class="col-sm-10"></div>
        </div>
    </span>

    <span v-else>
        <div class="panel panel-danger">
              <div class="panel-heading">
                    <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Error
              </div>
              <div class="panel-body">
                    User without an active contract
              </div>
        </div>
    </span>

</div>
    
<script>

//VUE
const app = new Vue({
    el: '#app',

    data: {
        //Data to do requests
        user: '{!! Auth()->user()->id !!}',
        year: 0,

        //Data neccesary to see the view
        contract: <?php echo json_encode(Auth()->user()->contracts->first());?>,

        //Data from DB
        holidays: [],
        userCard: {},
    },

    methods: {
        
        viewRender(view, element){
            $(".fc-other-month .fc-day-number").hide();//Ocultar next and previous month
            this.year = view.intervalStart.year();//Actualizar año al cambiar de vista  //app.year = $('#calendar').fullCalendar('getDate').year();
            $('#calendar').fullCalendar('removeEvents');//Borrar eventos

            this.fetchData(view.name);//BankHolidays and HolidaysRequested
            this.userHolidays();//UserCard         
        },

        dayRender(date,cell){
        },

        eventRender(event, element, view){

            var evStart = moment(view.intervalStart).subtract(1, 'days');
            var evEnd = moment(view.intervalEnd).subtract(1, 'days');
            let rendering;
            
            //----------------------------------------------------
            var eventDate = moment(event.start).format('YYYY-MM-DD');
            let day = '<span class="pull-right">'+ moment(event.start).format('DD') + '</span> ';
            let title = '<div style="position:relative;margin-top:20%;text-align:center;font-size:12px;">' + event.title + '</div>';
            let icon = '<div style="position:relative;left:25%;" class="glyphicon glyphicon-ok btn-lg" aria-hidden="true"></div>';

            //Si tiene una clase CSS asociada - ESTÁ VALIDADO
            if(event.className[0]){
                //$("td[data-date='"+eventDate+"']").addClass(event.className[0]);
                $("td[data-date='"+eventDate+"']").html(day + title + icon);
            }else{
                $("td[data-date='"+eventDate+"']").html(day + title);
            }
            //----------------------------------------------------
        
            //Cambio de renderización
            rendering = this.getRendering(view.name);//Tipo de renderizacion en función de la vista
            event.rendering = rendering;
            
            //Cancelar renderización del evento
            if (!event.start.isAfter(evStart) || event.start.isAfter(evEnd)){ 
                return false; 
            }  
        },

        dayClick(date, jsEvent, view, resourceObj){
            var eventTitle;
            var exist = false;
            var allEvents = [];
            var newEvent = {};

            //Validacion de la fecha - Respecto al mes que se esta visualizando
            if(view.intervalStart.format('MM') != date.format('MM')){
                console.log('Fuera de rango del mes');
                toastr.error("Fuera de rango del mes");
                return;
            }

            //Validacion de la fecha - Respecto a hoy        
            if(date.format('YYYY MM DD') <= moment().format('YYYY MM DD') ){
                console.log("Fecha menor o igual que hoy");
                toastr.error("Fecha menor o igual que hoy");
                return;
            }
            
            //TIPO DE VACACIONES
            if(parseInt(date.format('MM')) < 4 && this.userCard.last_year - this.userCard.used_last_year > 0){
                eventTitle = "last_year";
            }
            else if(this.userCard.extras - this.userCard.used_extras > 0){
                eventTitle = "extras";
            }
            else if(this.userCard.current_year - this.userCard.used_current_year > 0){
                eventTitle = "current_year";
            }
            else{
                console.log("No te quedan días de vacaciones");
                toastr.error("No te quedan días de vacaciones");
                exist = true;
            }

            //Obtener y recorrer eventos para ver si existe el mismo día//MISMO DIA Y TITULO => BORRAR
            allEvents = $('#calendar').fullCalendar('clientEvents');
            allEvents.forEach( (item) => {

               if(date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.className == 'validated'){
                    exist = true;
                    console.log("Día de vacaciones validado");
                    toastr.warning("Día de vacaciones validado");
                    return;
               }
                else if( date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.id != 0 ){
                    exist = true;//No festivos
                    this.delete(item);
                }
                else if(date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.id == 0 ){
                    exist = true;//Festivos
                    console.log("No se puede solicitar vacaciones en un día festivo");
                    toastr.error("No se puede solicitar vacaciones en un día festivo");
                }

            });
            
            //Guardar día de vacaciones
            if (exist == false) {
                newEvent = { 
                    title: eventTitle, 
                    start: date, 
                    allDay: true, 
                    color: this.setColor(eventTitle),
                    description: null,
                    rendering: this.getRendering(view.name),
                };
                this.save(newEvent);
            }            
        },

        getRendering(viewName){
            if(viewName == 'month'){
                return "background";
            }
            return "";
        },

        setColor(title) {
            title = title.toLowerCase();
            if (title == 'current_year') return 'blue';
            if (title == 'last_year') return 'blue';
            if (title == 'extras') return 'blue';
            if (title == 'national') return 'red';
            if (title == 'regional') return 'red';
            if (title == 'local') return 'red';
            return 'black';
        },

        setTitle(title){
            title = title.toLowerCase();
            if (title == 'current_year') return 'HOLIDAYS';
            if (title == 'last_year') return 'LAST YEAR HOLIDAYS';
            if (title == 'extras') return 'EXTRA HOLIDAYS';
            if (title == 'adjustment') return '3DB ADJUSTMENT';
            return title.toUpperCase();
        },

        fetchData (viewName) {
            var vm = this;
            vm.holidays = [];

            axios.get('api/calendar', {
                    params: {
                        user: vm.user,
                        year: vm.year,
                    }
                })
                .then(function (response) {

                    response.data.forEach( (item) => {
                        let Event = {
                            id: item.id == null ? 0 : item.id,
                            title: vm.setTitle(item.name == null ? item.type : item.name),
                            start: item.date,
                            allDay: true,
                            color: vm.setColor(item.type),
                            description: item.comments,
                            rendering: vm.getRendering(viewName),    
                            className:item.validated ? 'validated': null               
                        };
                        vm.holidays.push(Event);
                        $('#calendar').fullCalendar('renderEvent', Event, true);//Pintar eventos

                    });

                })
                .catch(function (error) {
                   console.log(error.response);
                   vm.showErrors(error.response.data)
                });                
        },

        userHolidays(){
            var vm = this;
            vm.userCard = {};
      
            axios.get('api/calendar/userHolidays', {
                    params: {
                        user: vm.user,
                        year: vm.year,
                    }
                })
                .then(function (response) {
                    vm.userCard = response.data;//Datos de los contadores de vacaciones del usuario
                })
                .catch(function (error) {
                   console.log(error.response)
                   vm.showErrors(error.response.data)
                });                
        },

        save(item){
            let vm = this;

            let holiday = {
                user_id: this.user,
                type: item.title,
                date: item.start,
                comments: item.description,
                validated: false
            };

            axios.post('/api/calendar', holiday)
                .then(function (response) {         
                    if(response.data != "Error"){
                        item['id'] = response.data;//Actualizar id de inserción de la BD
                        item['title'] = vm.setTitle(item['title']);//Actualizar titulo
                        $('#calendar').fullCalendar('renderEvent', item, true);//Pintar evento
                        vm.userHolidays();//Recargar contadores
                        console.log("Guardado:"+ response.data);
                        toastr.success("Guardado:"+ response.data)
                    }
                })
                .catch(function (error) {
                    console.log(error.response)
                    vm.showErrors(error.response.data)
                }); 
        },

        delete(item){
            let vm = this;
            let day = '<span class="pull-right">'+ item.start.format('DD') + '</span> ';

            axios.delete('/api/calendar/' + item.id)
                .then(function (response) {             
                    if(response.data == true){
                        $('#calendar').fullCalendar('removeEvents',item.id);//Borrar evento
                        $("td[data-date='"+item.start.format('YYYY-MM-DD')+"']").html(day);//Borrar titulo
                        vm.userHolidays();//Recargar contador
                        console.log("Borrado:"+ item.id)
                        toastr.success("Borrado:"+ item.id)
                    }
                })
                .catch(function (error) {
                    console.log(error.response)
                    vm.showErrors(error.response.data)
                }); 
        },

        showErrors(errors) {
            if(Array.isArray(errors)) {
                errors.forEach( (error) => {
                    toastr.error(error);
                })
            }
            else {
                toastr.error(errors);
            }
        },

    }
});

//JQUERY-FULLCALENDAR
$(document).ready(function() {
    $('#calendar').fullCalendar({
        theme: true,
        header: {
            left:'prevYear,prev,today,next,nextYear',
            center: 'title',
            right: 'month,listYear'
        },
        displayEventTime: true, // don't show the time column in list view
        defaultView: 'month',
        lang: 'es',
        firstDay: 1,//Monday
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        selectable: true,
        droppable: false,
        eventLimit: true, // allow "more" link when too many events
        weekNumbers: true,//Show weeknumbers

        //Rango de fechas que veremos
        validRange: function(nowDate) {
            return {
                start: nowDate.clone().add(-1, 'years').year()+'-01-01',//'2017-01-01'//nowDate.clone().add(-1, 'years')
                end: nowDate.clone().add(1, 'years').year()+'-04-01',//'2018-04-01'//nowDate.clone().add(1, 'years')
            };
        },

        //Evento que pinta la vista
        viewRender: function(view, element){
            app.viewRender(view, element);
         },

        //Modifica la celda del día
        dayRender: function (date, cell) {
            app.dayRender(date,cell);
        },

        //Evento que pinta el evento
        eventRender: function(event, element, view) {
            app.eventRender(event, element, view);
        },

        //Evento al pinchar un día
        dayClick: function(date, jsEvent, view, resourceObj) {
            app.dayClick(date, jsEvent, view, resourceObj);         
         },
        
    });
});

</script>

</body>
</html>