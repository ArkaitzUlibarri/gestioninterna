<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		/*
		$absences=[
			['code' =>'m','group'=>'baja','name'=>'medico o baja medica'],
			['code' =>'b','group'=>'permiso','name'=>'boda'],
			['code' =>'p','group'=>'permiso','name'=>'dia previo a examen'],
			['code' =>'e','group'=>'permiso','name'=>'examen'],
			['code' =>'mud','group'=>'permiso','name'=>'mudanza'],	
			['code' =>'f','group'=>'permiso','name'=>'permiso por asunto familiar'],
			['code' =>'o','group'=>'otros','name'=>'otros'],
			['code' =>'pend','group'=>'otros','name'=>'pendiente de reportar'],
			['code' =>'v','group'=>'vacaciones','name'=>'vacaciones'],
		];	
		*/
	
		$absences=[
			['code' =>'m','group'=>'sick leave','name'=>'medical or sick leave'],
			['code' =>'w','group'=>'permission','name'=>'wedding'],
			['code' =>'ee','group'=>'permission','name'=>'exam eve'],
			['code' =>'e','group'=>'permission','name'=>'exam'],
			['code' =>'r','group'=>'permission','name'=>'removal'],	
			['code' =>'f','group'=>'permission','name'=>'family bussiness leave'],
			['code' =>'o','group'=>'others','name'=>'others'],
			['code' =>'p','group'=>'others','name'=>'pending to report'],
			['code' =>'h','group'=>'holidays','name'=>'holidays'],
		];	

		$bank_holidays=[

			 /**
			 * Fiestas nacionales
			 */    
			 ['date' => Carbon::createFromDate(null, 1, 6),'code_id'=>1],//6 de Enero: Epifanía del Señor
			 ['date' => Carbon::createFromDate(2017, 4, 14),'code_id'=>1],//14 de Abril: Viernes Santo 
			 ['date' => Carbon::createFromDate(null, 5, 1),'code_id'=>1],//1 de Mayo: Fiesta del Trabajo    
			 ['date' => Carbon::createFromDate(null, 8, 15),'code_id'=>1],//15 de Agosto: Asunción de la Virgen 
			 ['date' => Carbon::createFromDate(null, 10, 12),'code_id'=>1],//12 de Octubre: Fiesta Nacional de España 
			 ['date' => Carbon::createFromDate(null, 11, 1),'code_id'=>1],//1 de Noviembre: Día de todos Los Santos
			 ['date' => Carbon::createFromDate(null, 12, 6),'code_id'=>1],//6 de Diciembre: Día de la Constitución Española
			 ['date' => Carbon::createFromDate(null, 12, 8),'code_id'=>1],//8 de Diciembre: Inmaculada Concepción
			 ['date' => Carbon::createFromDate(null, 12, 25),'code_id'=>1],//25 de Diciembre: Natividad del Señor
			 
			 /**
			 * Fiestas de las CCAA
			 */
			 ['date' => Carbon::createFromDate(2017, 4, 13),'code_id'=>2],//13 de Abril: Jueves Santo (en toda España excepto en Cataluña)
			 ['date' => Carbon::createFromDate(2017, 4, 17),'code_id'=>2],//17 de Abril: Lunes de Pascua
			 ['date' => Carbon::createFromDate(null, 7, 25),'code_id'=>2],//25 de Julio: Santiago Apostol
			 
			 ['date' => Carbon::createFromDate(2017, 3, 20),'code_id'=>3],//20 de Marzo: Lunes siguiente a San José
			 ['date' => Carbon::createFromDate(2017, 4, 13),'code_id'=>3],//13 de Abril: Jueves Santo (en toda España excepto en Cataluña)
			 ['date' => Carbon::createFromDate(null, 5, 2),'code_id'=>3],//2 de Mayo: Día de la Comunidad de Madrid
			 
			 ['date' => Carbon::createFromDate(2017, 4, 17),'code_id'=>4],//17 de Abril: Lunes de Pascua
			 ['date' => Carbon::createFromDate(null, 6, 24),'code_id'=>4],//24 de Junio:San Juan
			 ['date' => Carbon::createFromDate(null, 9, 11),'code_id'=>4],//11 de Septiembre:Fiesta Nacional de Cataluña
			 ['date' => Carbon::createFromDate(null, 12, 6),'code_id'=>4],//26 de Diciembre: San Esteban
			 
			 /**
			 * Fiestas de las Localidades
			 */
			 ['date' => Carbon::createFromDate(2017, 7, 31),'code_id'=>5],//31 de Julio:San Ignacio de Loyola
			 ['date' => Carbon::createFromDate(2017, 8, 25),'code_id'=>5],//25 de Agosto:Viernes de la semana grande
			 
			 ['date' => Carbon::createFromDate(2017, 5, 15),'code_id'=>6],//15 de Mayo:San Isidro
			 ['date' => Carbon::createFromDate(2017, 11, 9),'code_id'=>6],//9 de Noviembre:La Almudena
			 
			 ['date' => Carbon::createFromDate(2017, 6, 5),'code_id'=>7],//5 de Junio:Pascua Granada
			 ['date' => Carbon::createFromDate(2017, 11, 25),'code_id'=>7],//9 de Noviembre:La Merced
			 
			 /**
			 * Ajuste Calendario 3dB
			 */
			 ['date' => Carbon::createFromDate(2017, 10, 13),'code_id'=>8],//13 de Octubre
			 ['date' => Carbon::createFromDate(2017, 12, 7),'code_id'=>8],//7 de Diciembre
		];

		$bank_holidays_codes=[
			['type' =>'national','name'=>'España','code'=>'ne'],
			['type' =>'regional','name'=>'País vasco','code'=>'pv'],
			['type' =>'regional','name'=>'Comunidad de Madrid','code'=>'cmad'],
			['type' =>'regional','name'=>'Cataluña','code'=>'cat'],
			['type' =>'local','name'=>'Bilbao','code'=>'bi'],
			['type' =>'local','name'=>'Madrid','code'=>'mad'],
			['type' =>'local','name'=>'Barcelona','code'=>'ba'],
			['type' =>'others','name'=>'adjustment','code'=>'adjustment'],
		];
	   
		$categories = [
			['name' =>'DI', 'code' => 'di', 'description' => 'Director'],
			['name' =>'RP Junior', 'code' => 'rp', 'description' => 'Responsable de Proyecto'],
			['name' =>'RP Senior', 'code' => 'rp', 'description' => 'Responsable de Proyecto'],
			['name' =>'rg junior', 'code' => 'rg', 'description' => 'Responsable de Grupo'],
			['name' =>'rg intermediate', 'code' => 'rg', 'description' => 'Responsable de Grupo'],
			['name' =>'rg senior', 'code' => 'rg', 'description' => 'Responsable de Grupo'],
			['name' =>'lt', 'code' => 'lt', 'description' => 'Lider Tecnico'],
			['name' =>'ip junior', 'code' => 'ip', 'description' => 'Ingeniero de Proyecto'],
			['name' =>'ip intermediate', 'code' => 'ip', 'description' => 'Ingeniero de Proyecto'],
			['name' =>'ip senior', 'code' => 'ip', 'description' => 'Ingeniero de Proyecto'],
			['name' =>'itp junior', 'code' => 'itp', 'description' => 'Ingeniero Tecnico de Proyecto'],
			['name' =>'itp intermediate', 'code' => 'itp', 'description' => 'Ingeniero Tecnico de Proyecto'],
			['name' =>'ip senior', 'code' => 'itp', 'description' => 'Ingeniero Tecnico de Proyecto'],
			['name' =>'ds junior', 'code' => 'ds', 'description' => 'Desarrollador de Software'],
			['name' =>'ds experienced', 'code' => 'ds', 'description' => 'Desarrollador de Software'],
			['name' =>'tp junior', 'code' => 'tp', 'description' => 'Tecnico de Proyecto'],
			['name' =>'tp experienced', 'code' => 'tp', 'description' => 'Tecnico de Proyecto'],
			['name' =>'at junior', 'code' => 'at', 'description' => 'Auxiliar Tecnico'],
			['name' =>'tp experienced', 'code' => 'at', 'description' => 'Auxiliar Tecnico'],
			['name' =>'ad junior', 'code' => 'ad', 'description' => 'Administrativo'],
			['name' =>'ad experienced', 'code' => 'ad', 'description' => 'Administrativo'],
			['name' =>'bc', 'code' => 'bc', 'description' => 'Becario'],
		];

		$contract_types=[
			['code' =>'100','name'=>'Indefinido','holidays'=>22],
			['code' =>'401','name'=>'Obra o servicio','holidays'=>22],
			['code' =>'402','name'=>'Eventual (circunstancias de la producción)','holidays'=>22],
			['code' =>'420','name'=>'Prácticas','holidays'=>22],
			['code' =>'bl','name'=>'Beca lanbide','holidays'=>0],   
			['code' =>'bce','name'=>'Beca cooperación educativa','holidays'=>15],
			['code' =>'fp','name'=>'Prácticas formación profesional','holidays'=>0],
		];  

		$customers=[
			['name'=>'3db'],
			['name'=>'alcatel'],
			['name'=>'berrio-otxoa ikastetxea'],
			['name'=>'elecnor'],
			['name'=>'elisa'],
			['name'=>'ems2'],
			['name'=>'ericsson'],
			['name'=>'euskaltel'],
			['name'=>'everis'],
			['name'=>'gobierno vasco'],
			['name'=>'mercedes-benz'],
			['name'=>'varios'],
			['name'=>'yoigo'],
		];

		/*
		$groups=[
			['name' => '-'],
			['name' => 'Gestión'],
			['name' => 'Tunning'],
			['name' => 'Diseño'],
			['name' => 'OPT'],
			['name' => 'RSSI']
		];
		*/
	
		/*
		$planes=[
			['area'=>'diseño','name'=>'diseño de emplazamiento','hours'=>8],
			['area'=>'diseño','name'=>'diseño de rbs600','hours'=>8],
			['area'=>'diseño','name'=>'diseño de lld en orange spain','hours'=>8],
			['area'=>'diseño','name'=>'diseño de lld en vodafone spain','hours'=>8],
			
			['area'=>'1 - 2g ran','name'=>'introduccion al gsm','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'diseño de red 2g','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'modo libre y logica de asignacion de canal y celda en 2g nivel basico','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'modo libre y logica de asignacion de canal y celda en 2g nivel avanzado','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'funcionalidades avanzadas de logica de asignacion','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'funcionalidades de gestion del interfaz aire','hours'=>10],
			['area'=>'1 - 2g ran','name'=>'introduccion a la parametrizacion 2g','hours'=>10],
			['area'=>'1 - 2g ran','name'=>'introduccion a gprs/egprs','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'gprs-edge avanzado','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'estadisticos 2g','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'npi para 2g','hours'=>10],
			['area'=>'1 - 2g ran','name'=>'hua modo libre y logica de asignación de canal y celda en 2g','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'hua funcionalidades avanzadas de la logica de asignación 2g','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'hua funcionalidades de gestion del interfaz aire 2g','hours'=>10],
			['area'=>'1 - 2g ran','name'=>'hua introduccion a la parametrizacion 2g','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'hua gprs-edge avanzado','hours'=>12],
			['area'=>'1 - 2g ran','name'=>'hua estadisticos 2g','hours'=>8],
			['area'=>'1 - 2g ran','name'=>'funcionalidades 2g en redes zte','hours'=>12],
			
			['area'=>'2 - 3g ran','name'=>'introduccion a wcdma','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'diseño de red 3g','hours'=>16],
			['area'=>'2 - 3g ran','name'=>'funcionalidades basicas de 3g, nivel 1','hours'=>16],
			['area'=>'2 - 3g ran','name'=>'funcionalidades basicas de 3g, nivel 2','hours'=>16],
			['area'=>'2 - 3g ran','name'=>'funcionalidades avanzadas 3g','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'hsdpa basico','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'eul basico','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'hspa avanzado','hours'=>12],
			['area'=>'2 - 3g ran','name'=>'introduccion a la parametrizacion 3g','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'estadisticos 3g','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'gestion global de la carga entre carriers y sistemas, regulacion','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'qos, arp, spi e influencia de la capa de trasporte','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'mejora de la accesibilidad y la retención 3g','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'mejora de la cobertura, capacidad y thorughput 3g','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'mejora de vecindades','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'monitorización de la carga y planificación de ampliaciones','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'hua funcionalidades basicas de 3g','hours'=>16],
			['area'=>'2 - 3g ran','name'=>'hua funcionalidades avanzadas de 3g','hours'=>12],
			['area'=>'2 - 3g ran','name'=>'hua hsdpa & eul','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'hua hspa funcionalidades avanzadas de gestion y de hspa+','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'hua introduccion a la parametrizacion 3g','hours'=>12],
			['area'=>'2 - 3g ran','name'=>'hua estadisticos 3g','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'zte funcionalidades basicas de 3g','hours'=>12],
			['area'=>'2 - 3g ran','name'=>'zte funcionalidades avanzadas de 3g','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'zte hsdpa & eul','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'zte hspa+','hours'=>8],
			['area'=>'2 - 3g ran','name'=>'zte introduccion a la parametrizacion 3g','hours'=>10],
			['area'=>'2 - 3g ran','name'=>'zte estadisticos 3g','hours'=>8],
			
			['area'=>'3 - lte ran','name'=>'introduccion a lte','hours'=>12],
			['area'=>'3 - lte ran','name'=>'enodeb','hours'=>8],
			['area'=>'3 - lte ran','name'=>'dimensionado lte','hours'=>16],
			['area'=>'3 - lte ran','name'=>'acceso radio lte','hours'=>8],
			['area'=>'3 - lte ran','name'=>'servicios y gestion de la conexion lte','hours'=>8],
			['area'=>'3 - lte ran','name'=>'movilidad en lte y lte-3gpp','hours'=>12],
			['area'=>'3 - lte ran','name'=>'funcionalidades para mejorar la eficiencia lte','hours'=>10],
			['area'=>'3 - lte ran','name'=>'configuracion e inspeccion de la red lte','hours'=>12],
			['area'=>'3 - lte ran','name'=>'indicadores de funcionamiento  de la red lte','hours'=>8],
			['area'=>'3 - lte ran','name'=>'red de transporte lte','hours'=>8],
			['area'=>'3 - lte ran','name'=>'hua modo inactivo, acceso y procesos de la conexion lte','hours'=>10],
			['area'=>'3 - lte ran','name'=>'hua movilidad en lte y lte-3gpp','hours'=>10],
			['area'=>'3 - lte ran','name'=>'hua agregacion de portadoras, equilibrio de carga, mimo y ssrr avanzados','hours'=>10],
			['area'=>'3 - lte ran','name'=>'hua configuracion e inspeccion de la red lte','hours'=>10],
			['area'=>'3 - lte ran','name'=>'hua indicadores de funcionamiento  de la red lte','hours'=>8],
			['area'=>'3 - lte ran','name'=>'funcionalidades lte en redes zte','hours'=>12],
			
			['area'=>'4 - oss & oss tools','name'=>'introduccion al oss-rc','hours'=>8],
			['area'=>'4 - oss & oss tools','name'=>'funcionalidades avanzadas del explorador de red del oss','hours'=>12],
			['area'=>'4 - oss & oss tools','name'=>'funcionalidades del explorador común del oss','hours'=>20],
			['area'=>'4 - oss & oss tools','name'=>'optimizacion en base a edos, era','hours'=>8],
			
			['area'=>'6 tems','name'=>'introduccion a tems','hours'=>8],
			
			['area'=>'8 - transmision y transporte ran','name'=>'introduccion a la red de transmision y transporte','hours'=>10],
			['area'=>'8 - transmision y transporte ran','name'=>'red de transporte 2g y 3g. transicion de tdm/atm a ip','hours'=>12],
			['area'=>'8 - transmision y transporte ran','name'=>'diseño de red de transmision ran','hours'=>12],
			['area'=>'8 - transmision y transporte ran','name'=>'diseño de radioenlaces y equipamiento asociado, iqlink','hours'=>12],
			['area'=>'8 - transmision y transporte ran','name'=>'diseño de red de transporte 2g y 3g, legacy y full-ip','hours'=>12],
			
			['area'=>'cursos para fps','name'=>'redes moviles para tp/at i fundamentos de redes moviles, diseño y redes 2g y 3g','hours'=>10],
			['area'=>'cursos para fps','name'=>'redes moviles para tp/at ii, gestores, integracion y operacion','hours'=>10],
			['area'=>'cursos para fps','name'=>'redes moviles para tp/at iii, gestion de red 2g','hours'=>8],
			['area'=>'cursos para fps','name'=>'redes moviles para tp/at iv, gestion de red 3g','hours'=>8],
			['area'=>'cursos para fps','name'=>'redes moviles para tp/at v, gestion de redes 4g','hours'=>8],
			
			['area'=>'management & consultative skills','name'=>'ow executive - liderazgo y direccion de personas','hours'=>40],
		];
		*/

		foreach ($bank_holidays_codes as $bank_holiday_code) {
			DB::table('bank_holidays_codes')->insert([
				'type' => $bank_holiday_code['type'],
				'name' => $bank_holiday_code['name'],
				'code' => $bank_holiday_code['code'],
			]);
		}

		foreach ($bank_holidays as $bank_holiday) {
			DB::table('bank_holidays')->insert([
				'date' => $bank_holiday['date'],
				'code_id' => $bank_holiday['code_id'],
			]);
		}
/*
		foreach ($planes as $plan) {
			DB::table('planes')->insert([
				'area' => $plan['area'],
				'name' => $plan['name'],
				'hours' => $plan['hours'],
			]);
		}
*/
		foreach ($absences as $absence) {
			DB::table('absences')->insert([
				'code' => $absence['code'],
				'group' => $absence['group'],
				'name' => $absence['name'],
			]);
		}

		foreach ($contract_types as $type) {
			DB::table('contract_types')->insert([
				'code' => $type['code'],
				'name' => $type['name'],
				'holidays' => $type['holidays'],
			]);
		}

		foreach ($customers as $customer) {
			DB::table('customers')->insert([
				'name' => $customer['name'],
			]);
		}
		/*
		foreach ($groups as $group) {
			DB::table('groups')->insert([
				'name' => $group['name'],
			]);
		}
		*/

		foreach ($categories as $category) {
			DB::table('categories')->insert([
				'name'        => $category['name'],
				'code'        => $category['code'],
				'description' => $category['description'],
			]);
		}
	}
}
