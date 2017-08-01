
/**
 * Registro los componentes necesarios.
 */
//

const app = new Vue({
    el: '#app',

    data: {
        url: url,
        //Data to do requests
        user: id,
        calendarYear: 0,

        calendarMonth: 0,
        actualYear: moment().year(),

        //Data neccesary to see the view
        contract: user_contract ,

        //Data from DB
        holidays: [],
        userCard: {},
    },

    methods: {

    	viewRender(view, element){
            $(".fc-other-month .fc-day-number").hide();//Ocultar next and previous month
            this.calendarYear = view.intervalStart.year();//Actualizar año al cambiar de vista  //this.calendarYear = $('#calendar').fullCalendar('getDate').year();
            this.calendarMonth = $('#calendar').fullCalendar('getDate').month() + 1;
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
            let icon = '<div title="Validated" style="position:relative;left:25%;" class="glyphicon glyphicon-ok btn-lg" aria-hidden="true"></div>';

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
            if(parseInt(date.format('YYYY')) > parseInt(moment().format('YYYY')) ){
                eventTitle = "next_year";
            }
            else if(parseInt(date.format('MM')) < 4 && this.userCard.last_year - this.userCard.used_last_year > 0){
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
            if (title == 'next_year') return 'blue';
            if (title == 'extras') return 'blue';
            if (title == 'national') return 'red';
            if (title == 'regional') return 'red';
            if (title == 'local') return 'red';
            if (title == 'others') return 'red';
            return 'black';
        },

        setTitle(title){
            title = title.toLowerCase();
            if (title == 'current_year') return 'HOLIDAYS';
            if (title == 'last_year') return 'LAST YEAR HOLIDAYS';
            if (title == 'next_year') return 'NEXT YEAR HOLIDAYS';
            if (title == 'extras') return 'EXTRA HOLIDAYS';
            if (title == 'adjustment') return '3DB ADJUSTMENT';
            return title.toUpperCase();
        },

        fetchData (viewName) {
            var vm = this;
            vm.holidays = [];

            axios.get(vm.url + '/api/calendar', {
                    params: {
                        user: vm.user,
                        year: vm.calendarYear,
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
            var fetchYear;
            vm.userCard = {};
      
            axios.get(vm.url + '/api/calendar/userHolidays', {
                    params: {
                        user: vm.user,
                        year: vm.calendarYear > vm.actualYear ? vm.actualYear : vm.calendarYear,
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

            axios.post(vm.url + '/api/calendar', holiday)
                .then(function (response) {         
                    
                    item['id'] = response.data;//Actualizar id de inserción de la BD
                    item['title'] = vm.setTitle(item['title']);//Actualizar titulo
                    $('#calendar').fullCalendar('renderEvent', item, true);//Pintar evento
                    vm.userHolidays();//Recargar contadores
                    console.log("Guardado:"+ response.data);
                    toastr.success("Guardado:"+ response.data)
                    
                })
                .catch(function (error) {
                    console.log(error.response)
                    vm.showErrors(error.response.data)
                }); 
        },

        delete(item){
            let vm = this;
            let day = '<span class="pull-right">'+ item.start.format('DD') + '</span> ';

            axios.delete(vm.url + '/api/calendar/' + item.id)
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
        theme: false,
        header: {
            left:'',
            center: 'title',
            right:''
        },
        footer:{          
            left:'prevYear,prev',
            center: 'today,month,listYear', 
            right:'next,nextYear'                
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