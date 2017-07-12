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
        bankHolidays: []
        // User's data
        //user_id: '{!! Auth()->user()->id !!}',
        //role: '{!! Auth()->user()->primaryRole() !!}',

        // Filter options
        //filter: { year: moment().year(), month: moment().month(), week: moment().week() },

        //weekdaysShort : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    },

    mounted() {
        this.fetchUserData();
        this.fetchBankData();
    },

    computed:{

    },

    methods: {

        fetchUserData () {
            var vm = this;

            axios.get('api/calendar', {
                    params: {
                        user: vm.user,
                        year: vm.year,
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                    vm.holidays = response.data;
                })
                .catch(function (error) {
                   console.log(error.response.data)
                });
        },

        fetchBankData () {
            var vm = this;

            axios.get('api/calendar/bank', {
                    params: {
                        user: vm.user,
                        year: vm.year,
                    }
                })
                .then(function (response) {
                    console.log(response.data);
                    vm.bankHolidays = response.data;
                })
                .catch(function (error) {
                   console.log(error.response.data)
                });
        },
   
    }
});

//JQUERY-FULLCALENDAR
$(document).ready(function() {

    var totalHolidays = 22;

    function getColor(eventTitle){
        switch(eventTitle){
            case 'Holiday':
                return 'green';
                break;
            case 'Last Year Holiday':
                return 'blue';
                break;
            case 'Extra Holiday':
                return 'purple';
                break;
            default:
                return 'black';
        }
    }

    $('#calendar').fullCalendar({
        theme:true,
        header: {
            left:'prev,next today',
            center: 'title',
            right: 'month,listYear'
        },
        displayEventTime: false, // don't show the time column in list view
        defaultView: 'month',
        defaultDate: app.today,
        lang: 'es',
        firstDay: 1,//Monday
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        droppable: false,
        eventLimit: true, // allow "more" link when too many events
        weekNumbers: true,//Show weeknumbers

        dayClick: function(date, jsEvent, view, resourceObj) {

            var eventColor;
            var eventTitle = "Holiday";
            
            //Selecciona tantos días como vacaciones
            if (totalHolidays > 0) {

               eventColor = getColor(eventTitle);
                
                $('#calendar').fullCalendar('renderEvent', { 
                    title: eventTitle, 
                    start: date, 
                    allDay: true, 
                    color: eventColor,
                }, true );
                

                totalHolidays--;
                console.log(totalHolidays);
            }
           
         },

        events: [
            {
                id: 1,
                title: 'Holiday',
                start: '2017-07-05',
                color: 'green',// #257e4a
            },
            {
                id: 2,
                title: 'Last Year Holiday',
                start: '2017-07-11',
                color: 'blue',//#2886F1
            },
            {
                id: 3,
                title: 'Extra Holiday',
                start: '2017-07-27',
                color: 'purple',//#811AC1
            },
            {
                title: 'Bank Holiday',
                start: '2017-07-25',
                color: 'red',//#C11A1A
            },

        ]
    });
    
});

</script>

</body>
</html>
