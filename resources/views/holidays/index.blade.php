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
    <div id='calendar'></div>

    <pre>
        @{{ $data.holidays }}
    </pre>
    
</div>
    
<script>

//VUE
const app = new Vue({
    el: '#app',

    data: {
        user: 4,
        year: 2017,
        today: new Date(),
        holidays: [],
        totalHolidays: 22,
    },

    mounted() {
        this.fetchData();
    },

    computed:{

    },

    methods: {

        setColor(title) {
            title = title.toLowerCase();
            if (title == 'current_year') return 'green';
            if (title == 'last_year') return 'blue';
            if (title == 'extras') return 'purple';
            if (title == 'national') return 'red';
            if (title == 'regional') return 'orange';
            if (title == 'local') return 'pink';
            return 'black';
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
                            title: item.type,
                            start: item.date,
                            color: vm.setColor(item.type),
                            allDay: true,
                        };
                        vm.holidays.push(Event);
                        $('#calendar').fullCalendar( 'renderEvent', Event);

                    });
                    /*
                    $('#calendar').fullCalendar({
                        events: vm.holidays,
                    });
                    */
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
            left:'prev,next today',
            center: 'title',
            right: 'month,listYear'
        },
        displayEventTime: true, // don't show the time column in list view
        defaultView: 'month',
        defaultDate: app.today,
        lang: 'es',
        firstDay: 1,//Monday
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        droppable: false,
        eventLimit: true, // allow "more" link when too many events
        weekNumbers: true,//Show weeknumbers

        //events: app.holidays,

        dayClick: function(date, jsEvent, view, resourceObj) {

            var eventColor;
            var eventTitle = "current_year";
            
            //Selecciona tantos días como vacaciones
            if (app.totalHolidays > 0) {

               eventColor = app.setColor(eventTitle);
                
                $('#calendar').fullCalendar('renderEvent', { 
                    title: eventTitle, 
                    start: date, 
                    allDay: true, 
                    color: eventColor,
                }, true );
                
                app.totalHolidays--;
            }
           
         }
         /*
         viewRender: function (view,element){
            $('#calendar').fullCalendar( 'renderEvent', Event);
         }
         */

    });
    
});

</script>

</body>
</html>