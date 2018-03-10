<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $planes = [
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

		foreach ($planes as $plan) {
			DB::table('planes')->insert([
				'area' => $plan['area'],
				'name' => $plan['name'],
				'hours' => $plan['hours'],
			]);
		}

    }
}
