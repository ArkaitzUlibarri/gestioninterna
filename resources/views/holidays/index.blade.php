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

</style>
</head>
<body>

<div id="app">

    @include('holidays.card')
    <div id='calendar'></div>
    
    <!--
    <pre>
        @{{ $data }}
    </pre>
    -->
</div>
    
<script>

//VUE
const app = new Vue({
    el: '#app',

    data: {
        //Data to do requests
        user: '{!! Auth()->user()->id !!}',
        year: moment().year(),

        //Data from DB
        holidays: [],
        userCard: {},
    },

    mounted() {
        this.fetchData();//BankHolidays and HolidaysRequested
        this.userHolidays();//UserCard
    },

    computed:{

    },

    methods: {

        dayClick(date){
            var eventTitle = "current_year";
            var exist = false;
            var allEvents = [];
            var newEvent = {};
            var newId;

            //Obtener y recorrer eventos para ver si existe el mismo día//MISMO DIA Y TITULO => BORRAR
            allEvents = $('#calendar').fullCalendar('clientEvents');
            allEvents.forEach( (item) => {
                if(this.setTitle(eventTitle) == item.title && date.format('YYYY MM DD') == item.start.format('YYYY MM DD')){
                    exist = true;
                    this.delete(item);
                }
            });
            
            if ((this.userCard.total - this.userCard.used_total) > 0 && exist == false) {
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
            return 'orange';
        },

        setTitle(title){
            title = title.toLowerCase();
            if (title == 'current_year') return 'VACACIONES';
            if (title == 'last_year') return 'AÑO ANTERIOR';
            if (title == 'adjustment') return 'AJUSTE 3DB';
            return title.toUpperCase();
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
                    console.log(response.data)
                    if(response.data == true){
                        $('#calendar').fullCalendar('removeEvents',item.id);
                        vm.userHolidays();
                    }
                })
                .catch(function (error) {
                    console.log(error.response)
                }); 
        },

        fetchData () {
            var vm = this;
            var count = 1;
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

        dayClick: function(date, jsEvent, view, resourceObj) {
            app.dayClick(date);          
         },

    });

    //app.year = $('#calendar').fullCalendar('getDate').year();
});

</script>

</body>
</html>