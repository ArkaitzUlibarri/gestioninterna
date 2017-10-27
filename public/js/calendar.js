webpackJsonp([10],{

/***/ 173:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(174);


/***/ }),

/***/ 174:
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {
/**
 * Registro los componentes necesarios.
 */
//

var app = new Vue({
    el: '#app',

    data: {
        url: url,
        //Data to do requests
        user: id,
        calendarYear: 0,

        calendarMonth: 0,
        actualYear: moment().year(),

        //Data neccesary to see the view
        contract: user_contract,

        //Data from DB
        holidays: [],
        userCard: {}
    },

    methods: {
        viewRender: function viewRender(view, element) {
            $(".fc-other-month .fc-day-number").hide(); //Ocultar next and previous month
            this.calendarYear = view.intervalStart.year(); //Actualizar año al cambiar de vista  //this.calendarYear = $('#calendar').fullCalendar('getDate').year();
            this.calendarMonth = $('#calendar').fullCalendar('getDate').month() + 1;
            $('#calendar').fullCalendar('removeEvents'); //Borrar eventos

            this.fetchData(view.name); //BankHolidays and HolidaysRequested
            this.userHolidays(); //UserCard         
        },
        dayRender: function dayRender(date, cell) {},
        eventRender: function eventRender(event, element, view) {

            var evStart = moment(view.intervalStart).subtract(1, 'days');
            var evEnd = moment(view.intervalEnd).subtract(1, 'days');
            var rendering = void 0;

            //----------------------------------------------------
            var eventDate = moment(event.start).format('YYYY-MM-DD');
            var day = '<span class="pull-right">' + moment(event.start).format('DD') + '</span> ';
            var title = '<div style="position:relative;margin-top:20%;text-align:center;font-size:12px;">' + event.title + '</div>';
            var icon = '<div title="Validated" style="position:relative;left:25%;" class="glyphicon glyphicon-ok btn-lg" aria-hidden="true"></div>';

            //Si tiene una clase CSS asociada - ESTÁ VALIDADO
            if (event.className[0]) {
                //$("td[data-date='"+eventDate+"']").addClass(event.className[0]);
                $("td[data-date='" + eventDate + "']").html(day + title + icon);
            } else {
                $("td[data-date='" + eventDate + "']").html(day + title);
            }
            //----------------------------------------------------

            //Cambio de renderización
            rendering = this.getRendering(view.name); //Tipo de renderizacion en función de la vista
            event.rendering = rendering;

            //Cancelar renderización del evento
            if (!event.start.isAfter(evStart) || event.start.isAfter(evEnd)) {
                return false;
            }
        },
        dayClick: function dayClick(date, jsEvent, view, resourceObj) {
            var _this = this;

            var eventTitle;
            var exist = false;
            var allEvents = [];
            var newEvent = {};

            //Validacion de la fecha - Respecto al mes que se esta visualizando
            if (view.intervalStart.format('MM') != date.format('MM')) {
                //console.log('Fuera de rango del mes');
                toastr.error("Fuera de rango del mes");
                return;
            }

            //Validacion de la fecha - Respecto a hoy        
            if (date.format('YYYY MM DD') <= moment().format('YYYY MM DD')) {
                //console.log("Fecha menor o igual que hoy");
                toastr.error("Fecha menor o igual que hoy");
                return;
            }

            //TIPO DE VACACIONES
            if (parseInt(date.format('YYYY')) > parseInt(moment().format('YYYY'))) {
                eventTitle = "next_year";
            } else if (parseInt(date.format('MM')) < 4 && this.userCard.last_year - this.userCard.used_last_year > 0) {
                eventTitle = "last_year";
            } else if (this.userCard.extras - this.userCard.used_extras > 0) {
                eventTitle = "extras";
            } else if (this.userCard.current_year - this.userCard.used_current_year > 0) {
                eventTitle = "current_year";
            } else {
                //console.log("No te quedan días de vacaciones");
                toastr.error("No te quedan días de vacaciones");
                exist = true;
            }

            //Obtener y recorrer eventos para ver si existe el mismo día//MISMO DIA Y TITULO => BORRAR
            allEvents = $('#calendar').fullCalendar('clientEvents');
            allEvents.forEach(function (item) {

                if (date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.className == 'validated') {
                    exist = true;
                    //console.log("Día de vacaciones validado");
                    toastr.warning("Día de vacaciones validado");
                    return;
                } else if (date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.id != 0) {
                    exist = true; //No festivos
                    _this.delete(item);
                } else if (date.format('YYYY MM DD') == item.start.format('YYYY MM DD') && item.id == 0) {
                    exist = true; //Festivos
                    //console.log("No se puede solicitar vacaciones en un día festivo");
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
                    rendering: this.getRendering(view.name)
                };
                this.save(newEvent);
            }
        },
        getRendering: function getRendering(viewName) {
            if (viewName == 'month') {
                return "background";
            }
            return "";
        },
        setColor: function setColor(title) {
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
        setTitle: function setTitle(title) {
            title = title.toLowerCase();
            if (title == 'current_year') return 'HOLIDAYS';
            if (title == 'last_year') return 'LAST YEAR HOLIDAYS';
            if (title == 'next_year') return 'NEXT YEAR HOLIDAYS';
            if (title == 'extras') return 'EXTRA HOLIDAYS';
            if (title == 'adjustment') return '3DB ADJUSTMENT';
            return title.toUpperCase();
        },
        fetchData: function fetchData(viewName) {
            var vm = this;
            vm.holidays = [];

            axios.get(vm.url + '/api/calendar', {
                params: {
                    user: vm.user,
                    year: vm.calendarYear
                }
            }).then(function (response) {

                response.data.forEach(function (item) {
                    var Event = {
                        id: item.id == null ? 0 : item.id,
                        title: vm.setTitle(item.name == null ? item.type : item.name),
                        start: item.date,
                        allDay: true,
                        color: vm.setColor(item.type),
                        description: item.comments,
                        rendering: vm.getRendering(viewName),
                        className: item.validated ? 'validated' : null
                    };
                    vm.holidays.push(Event);
                    $('#calendar').fullCalendar('renderEvent', Event, true); //Pintar eventos
                });
            }).catch(function (error) {
                //console.log(error.response);
                vm.showErrors(error.response.data);
            });
        },
        userHolidays: function userHolidays() {
            var vm = this;
            var fetchYear;
            vm.userCard = {};

            axios.get(vm.url + '/api/calendar/userHolidays', {
                params: {
                    user: vm.user,
                    year: vm.calendarYear > vm.actualYear ? vm.actualYear : vm.calendarYear
                }
            }).then(function (response) {
                vm.userCard = response.data; //Datos de los contadores de vacaciones del usuario
            }).catch(function (error) {
                //console.log(error.response)
                vm.showErrors(error.response.data);
            });
        },
        save: function save(item) {
            var vm = this;

            var holiday = {
                user_id: this.user,
                type: item.title,
                date: item.start,
                comments: item.description,
                validated: false
            };

            axios.post(vm.url + '/api/calendar', holiday).then(function (response) {

                item['id'] = response.data; //Actualizar id de inserción de la BD
                item['title'] = vm.setTitle(item['title']); //Actualizar titulo
                $('#calendar').fullCalendar('renderEvent', item, true); //Pintar evento
                vm.userHolidays(); //Recargar contadores
                //console.log("Guardado:"+ response.data);
                //toastr.success("Guardado:"+ response.data)
                toastr.success("SAVED");
            }).catch(function (error) {
                //console.log(error.response)
                vm.showErrors(error.response.data);
            });
        },
        delete: function _delete(item) {
            var vm = this;
            var day = '<span class="pull-right">' + item.start.format('DD') + '</span> ';

            axios.delete(vm.url + '/api/calendar/' + item.id).then(function (response) {
                if (response.data == true) {
                    $('#calendar').fullCalendar('removeEvents', item.id); //Borrar evento
                    $("td[data-date='" + item.start.format('YYYY-MM-DD') + "']").html(day); //Borrar titulo
                    vm.userHolidays(); //Recargar contador
                    //console.log("Borrado:"+ item.id)
                    //toastr.success("Borrado:"+ item.id)
                    toastr.success("DELETED");
                }
            }).catch(function (error) {
                //console.log(error.response)
                vm.showErrors(error.response.data);
            });
        },
        showErrors: function showErrors(errors) {
            if (Array.isArray(errors)) {
                errors.forEach(function (error) {
                    toastr.error(error);
                });
            } else {
                toastr.error(errors);
            }
        }
    }
});

//JQUERY-FULLCALENDAR
$(document).ready(function () {
    $('#calendar').fullCalendar({
        theme: false,
        header: {
            left: '',
            center: 'title',
            right: ''
        },
        footer: {
            left: 'prevYear,prev',
            center: 'today,month,listYear',
            right: 'next,nextYear'
        },
        displayEventTime: true, // don't show the time column in list view
        defaultView: 'month', //Vista por defecto la de mes
        lang: 'es',
        firstDay: 1, //Monday
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        selectable: true,
        droppable: false, //Poder soltar eventos en los días
        eventLimit: true, // allow "more" link when too many events
        weekNumbers: true, //Show weeknumbers

        //Rango de fechas que veremos
        validRange: function validRange(nowDate) {
            return {
                start: nowDate.clone().add(-1, 'years').year() + '-01-01', //'2017-01-01'//nowDate.clone().add(-1, 'years') // 1 Enero este año
                end: nowDate.clone().add(1, 'years').year() + '-04-01' //'2018-04-01'//nowDate.clone().add(1, 'years') //1 Abril año siguiente
            };
        },

        //Evento que pinta la vista
        viewRender: function viewRender(view, element) {
            app.viewRender(view, element);
        },

        //Modifica la celda del día
        dayRender: function dayRender(date, cell) {
            app.dayRender(date, cell);
        },

        //Evento que pinta el evento
        eventRender: function eventRender(event, element, view) {
            app.eventRender(event, element, view);
        },

        //Evento al pinchar un día
        dayClick: function dayClick(date, jsEvent, view, resourceObj) {
            app.dayClick(date, jsEvent, view, resourceObj);
        }

    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ })

},[173]);