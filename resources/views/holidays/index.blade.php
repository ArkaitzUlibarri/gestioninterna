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

</style>
</head>
<body>

<div id="app">

    <span v-if="contract.end_date == null">  
        <div class="row">
            @include('holidays.card')

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
        year: moment().year(),

        //Data neccesary to see the view
        contract: <?php echo json_encode(Auth()->user()->contracts->first());?>,

        //Data from DB
        holidays: [],
        userCard: {},
    },

    mounted() {
        //this.fetchData();//BankHolidays and HolidaysRequested
        //this.userHolidays();//UserCard
    },

    computed:{

    },

    methods: {

        eventRender(event,view){
            var evStart = moment(view.intervalStart).subtract(1, 'days');
            var evEnd = moment(view.intervalEnd).subtract(1, 'days');

            if (!event.start.isAfter(evStart) || event.start.isAfter(evEnd)){ 
                return false; 
            }
        },

        viewRender(view){
            this.year = view.intervalStart.year();//Actualizar año al cambiar de vista  //app.year = $('#calendar').fullCalendar('getDate').year();
            $(".fc-other-month .fc-day-number").hide();//Ocultar next and previous month
            $('#calendar').fullCalendar('removeEvents');//Borrar eventos

            //Cargar datos
            this.fetchData();
            this.userHolidays();
        },

        dayClick(date,view){
            var eventTitle;
            var exist = false;
            var allEvents = [];
            var newEvent = {};

            //Validacion de la fecha - Respecto al mes que se esta visualizando
            if(view.intervalStart.format('MM') != date.format('MM')){
                console.log('Fuera de rango del mes');
                return;
            }

            //Validacion de la fecha - Respecto a hoy        
            if(date.format('YYYY MM DD') < moment().format('YYYY MM DD') ){
                console.log("Fecha menor que hoy");
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
                return;
            }

            //Obtener y recorrer eventos para ver si existe el mismo día//MISMO DIA Y TITULO => BORRAR
            allEvents = $('#calendar').fullCalendar('clientEvents');
            allEvents.forEach( (item) => {
                if( (this.setTitle("last_year") == item.title || this.setTitle("extras") == item.title || this.setTitle("current_year") == item.title) 
                    && date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.id != 0 ){
                    exist = true;
                    this.delete(item);
                }
            });
            
            if (exist == false) {
                newEvent = { 
                    title: eventTitle, 
                    start: date, 
                    allDay: true, 
                    color: this.setColor(eventTitle),
                };
                this.save(newEvent);
            }            
        },

        setColor(title) {
            title = title.toLowerCase();
            if (title == 'current_year') return 'mediumblue';
            if (title == 'last_year') return 'blue';
            if (title == 'extras') return 'purple';
            if (title == 'national') return 'red';
            if (title == 'regional') return 'darkred';
            if (title == 'local') return 'indianred';
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

        fetchData () {
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
                            color: vm.setColor(item.type),
                            allDay: true,
                            description: item.comments,
                            //borderColor: item.validated ? 'black': 'white',
                        };
                        vm.holidays.push(Event);
                        $('#calendar').fullCalendar( 'renderEvent', Event,true);
                    });

                })
                .catch(function (error) {
                   console.log(error.response)
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
                    vm.userCard = response.data;
                })
                .catch(function (error) {
                   console.log(error.response)
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
                        item['id'] = response.data;
                        item['title'] = vm.setTitle(item['title']);
                        $('#calendar').fullCalendar('renderEvent',item, true);
                        vm.userHolidays();
                        console.log("Guardado:"+ response.data);
                    }
                })
                .catch(function (error) {
                    console.log(error.response)
                }); 
        },

        delete(item){
            let vm = this;

            axios.delete('/api/calendar/' + item.id)
                .then(function (response) {             
                    if(response.data == true){
                        $('#calendar').fullCalendar('removeEvents',item.id);
                        vm.userHolidays();
                        console.log("Borrado:"+ item.id)
                    }
                })
                .catch(function (error) {
                    console.log(error.response)
                }); 
        },

    }
});

//JQUERY-FULLCALENDAR
$(document).ready(function() {
    $('#calendar').fullCalendar({
        theme:true,
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
        selectable:true,
        droppable: false,
        eventLimit: true, // allow "more" link when too many events
        weekNumbers: true,//Show weeknumbers

        //Rango de fechas que veremos
        validRange: function(nowDate) {
            return {
                start: nowDate.clone().add(-1, 'years'),
                end: nowDate.clone().add(1, 'years')
            };
        },

        //Evento al pinchar un día
        dayClick: function(date, jsEvent, view, resourceObj) {
            app.dayClick(date,view);         
         },

         //Evento que pinta la vista
         viewRender: function(view,element){
            app.viewRender(view);
         },

         //Evento que pinta el evento
         eventRender: function(event, element, view){
            app.eventRender(event,view);
        },

    });
});

</script>

</body>
</html>