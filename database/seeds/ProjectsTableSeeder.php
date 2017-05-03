<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
	use SeederHelpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*
        $projects=[
			['name' => 'BSS Sharing','description'=>'Proyecto compartición ORA-Vfne sobre red ORA','customer_id'=>  7  ,'start_date'=>'2012-12-01','end_date'=>'2013-08-01'],
			['name' => 'Cobersites','description'=>'Gestión quejas de cliente para ORA dentro RAN Renewal','customer_id'=>  7  ,'start_date'=>'2012-01-01','end_date'=>'2012-11-01'],
			['name' => 'Cobersites OSP Quejas Madrid','description'=>'Gestión quejas de cliente para ORA dentro de un grupo de calidad para Madrid montando por Ericsson a raiz del plan Asfalto (plan acelerado de Swap en RR en verano)','customer_id'=>  7  ,'start_date'=>'2012-11-01','end_date'=>'2013-09-01'],
			['name' => 'Drive Test Orange','description'=>'Drive test por FOAs y proyectos varios en Z5 solicitados durante el transcurso del RR','customer_id'=>  7  ,'start_date'=>'2011-10-01','end_date'=>'2012-05-01'],
			['name' => 'Gerencia','description'=>'Gerencia 3dB','customer_id'=>  1  ,'start_date'=>'2005-03-01','end_date'=> null ],
			['name' => 'Gestcom','description'=>'Intento de apertura de negocio de reducción de costes en Facturas de Telecomunicaciones (Tel movil sobre todo) y consultoría en Sistemas de Teleco','customer_id'=>  12,'start_date'=>'2012-01-01','end_date'=>'2013-11-01'],
			['name' => 'Legacy Rehoming','description'=>'Rehoming TX','customer_id'=>  7  ,'start_date'=>'2011-10-01','end_date'=>'2013-12-01'],
			['name' => 'Medidas WIFI Gob Vasco 1','description'=>'Medidas de nivel de señal Wifi en escuelas para el Gobierno Vasco','customer_id'=>  10,'start_date'=>'2012-03-01','end_date'=>'2013-03-01'],
			['name' => 'MIND Campo','description'=>'Trabajos de campo asociados al proyecto MIND (lineas de vista, replanteos y auditorías)','customer_id'=>  2  ,'start_date'=>'2011-12-01','end_date'=>'2012-12-01'],
			['name' => 'MIND Ingenieria','description'=>'Renovación de la red de TX de ORA con equipos Alcatel-Lucent','customer_id'=>  2  ,'start_date'=>'2011-12-01','end_date'=> null ],
			['name' => 'NQA 1','description'=>'Generación y carga de vecinas en el proyecto RAN Renewal de Orange','customer_id'=>  7  ,'start_date'=>'2012-05-01','end_date'=>'2013-08-01'],
			['name' => 'NQA-CARGA','description'=>'La carga de ficheros estuvo separada durante unos meses, pero luego se unificó por lo que se considera todo incluido en NQA','customer_id'=>  7  ,'start_date'=>'2012-05-01','end_date'=>'2013-08-01'],
			['name' => 'OPT VFNE U900=>F3','description'=>'OPT U900 Vfne en Vigo y Coruña','customer_id'=>  7  ,'start_date'=>'2013-01-01','end_date'=>'2013-03-01'],
			['name' => 'RAN RENEWAL','description'=>'Proyecto de renovación de red radio para ORA','customer_id'=>  7  ,'start_date'=>'2011-01-01','end_date'=>'2016-01-01'],
			['name' => 'RAN RENEWAL-AMPLIACIONES','description'=>'Ampliaciones en red radio ORA surgido del proyecto RR, y atendido luego desde proyecto RAN BAU (Business as usual)','customer_id'=>  7  ,'start_date'=>'2013-01-01','end_date'=>'2013-09-01'],
			['name' => 'Refarming BCN','description'=>'1 Consultor en Barcelona trabajando para TFCA','customer_id'=>  7  ,'start_date'=>'2011-02-01','end_date'=>'2015-06-01'],
			['name' => 'Refarming Orange BIO','description'=>'Refarming 900 ORA toda España.Siemens Nokia y Eric','customer_id'=>  7  ,'start_date'=>'2012-01-01','end_date'=>'2012-09-01'],
			['name' => 'Refarming Vodafone','description'=>'Refarming Vfne Madrid y Barcelona','customer_id'=>  7  ,'start_date'=>'2012-10-01','end_date'=>'2012-11-01'],
			['name' => 'RR-OPT','description'=>'Proyecto de OPT de clusters de ORA incluido en RAN Renewal para aceptación','customer_id'=>  7  ,'start_date'=>'2012-05-01','end_date'=>'2013-12-01'],
			['name' => 'RR-REHOMING','description'=>'Rehoming de nodos a diferentes BSC/RNC de ORA, redefinición de vecinas','customer_id'=>  7  ,'start_date'=>'2012-09-01','end_date'=>'2013-09-01'],
			['name' => 'Soporte Calidad TME','description'=>'Consultoría para evaluación de funcionalidades','customer_id'=>  7  ,'start_date'=>'2012-01-01','end_date'=>'2012-01-01'],
			['name' => 'T1','description'=>'Renovación de la red de TX de ORA','customer_id'=>  7  ,'start_date'=>'2009-09-01','end_date'=>'2013-03-01'],
			['name' => 'T1 2','description'=> '' ,'customer_id'=>  7  ,'start_date'=>'2010-11-01','end_date'=>'2013-03-01'],
			['name' => 'Yoigo BCN','description'=>'Consultores en oficina de Ericsson Barcelona dando soporte al proyecto de Yoigo','customer_id'=>  7  ,'start_date'=>'2010-11-01','end_date'=>'2012-12-01'],
			['name' => 'Yoigo BIO','description'=>'1 persona en oficina de Ericsson Bilbao dando soporte al proyecto de Yoigo','customer_id'=>  7  ,'start_date'=>'2011-05-01','end_date'=>'2012-12-01'],
			['name' => 'REHOMING AUDIT','description'=>'Auditoría de vecinas de Rehoming solicitada por Ericsson a raiz del proyecto RR-Rehoming','customer_id'=>  7  ,'start_date'=>'2012-11-01','end_date'=>'2012-11-01'],
			['name' => 'FIRA OPT','description'=>'1 persona en oficina de Ericsson en Barcelona prestando ayuda para la preparacion de la FIRA Tel Movil en Feb 2013 para ORA','customer_id'=>  7  ,'start_date'=>'2013-02-01','end_date'=>'2013-02-01'],
			['name' => 'ITK','description'=>'Instalación y mantenimiento de ITK para Ericsson en diversos operadores','customer_id'=>  7  ,'start_date'=>'2013-01-01','end_date'=> null ],
			['name' => 'Q=>A LTE','description'=>'Monitorización 2G y 3G de nodos en cambio de SSRR e introducción de LTE para ORA','customer_id'=>  7  ,'start_date'=>'2013-05-01','end_date'=>'2014-08-01'],
			['name' => '2STEP U900','description'=>'Introducción de U900 posterior al Swap en red ORA dentro del proyecto RAN Renewal','customer_id'=>  7  ,'start_date'=>'2013-01-01','end_date'=>'2013-03-01'],
			['name' => 'WATANIYA SWAP','description'=>'NQA en proyecto de Swap de Wataniya en Kuwait','customer_id'=>  7  ,'start_date'=>'2013-05-01','end_date'=>'2014-02-01'],
			['name' => 'Rehoming sin vecinas en GAL1027','description'=>'Recuperación de Rehoming con problemas no ejecutado por 3dB para ORA','customer_id'=>  7  ,'start_date'=>'2013-06-01','end_date'=>'2013-06-01'],
			['name' => 'OSP Soporte OPT Madrid','description'=>'Soporte a OPT análisis de quejas y rediccionamiento ORA','customer_id'=>  7  ,'start_date'=>'2013-01-01','end_date'=>'2013-03-01'],
			['name' => 'ACTUAL NOMENCLATURAS OSS','description'=>'Actualización de la nomenclatura de definiciones de celdas (external) en los OSS de Orange por cambio de release en OSS y utilización de caracteres prohibidos en los nombres','customer_id'=>  7  ,'start_date'=>'2013-02-01','end_date'=>'2013-02-01'],
			['name' => 'EMS2 - MATERIAL','description'=>'Venta de material de oficina a EMS2','customer_id'=>  6  ,'start_date'=>'2012-01-01','end_date'=>'2012-01-01'],
			['name' => 'EMS2 - INGENIERIA','description'=>'Soporte a EMS2 de ingeniería en el inicio y desarrollo de su actividad','customer_id'=>  6  ,'start_date'=>'2011-02-01','end_date'=>'2011-12-01'],
			['name' => 'EMS2 - PLAN NEGOCIO','description'=>'Realización del plan de negocio de EMS2','customer_id'=>  6  ,'start_date'=>'2011-02-01','end_date'=>'2011-02-01'],
			['name' => 'REFARMING TFCA BIO','description'=>'Refarming 900 y 1800 Tfca Madrid y Barcelona','customer_id'=>  7  ,'start_date'=>'2011-10-01','end_date'=>'2011-12-01'],
			['name' => 'REFARMING RR ORANGE BIO','description'=>'Aceptación de refarming de 1800 ORA toda España','customer_id'=>  7  ,'start_date'=>'2011-10-01','end_date'=>'2011-10-01'],
			['name' => 'REFARMING BIO','description'=>'Ajuste frec tras refarming Tfca Barcelona','customer_id'=>  7  ,'start_date'=>'2011-09-01','end_date'=>'2011-09-01'],
			['name' => 'SOPORTE REF 900 ORANGE BIO','description'=>'Refarming 900 ORA','customer_id'=>  7  ,'start_date'=>'2012-01-01','end_date'=>'2012-01-01'],
			['name' => 'SOPORTE REF TFC BCN BIO','description'=>'Refarming Tfca BCN','customer_id'=>  7  ,'start_date'=>'2012-02-01','end_date'=>'2012-02-01'],
			['name' => 'RREN Soporte On call','description'=>'Servicio de Soporte on Call solicitado puntualmente desde el proyecto RR','customer_id'=>  7  ,'start_date'=>'2012-06-01','end_date'=>'2012-06-01'],
			['name' => 'RREN Rehoming of RBS from MAD06R02 to MAD06R03','description'=>'Rehoming puntual ORA','customer_id'=>  7  ,'start_date'=>'2012-08-01','end_date'=>'2012-08-01'],
			['name' => 'Venta de PEUGEOT 307','description'=>'Venta de coche','customer_id'=>  1  ,'start_date'=>'2013-02-01','end_date'=>'2013-02-01'],
			['name' => 'LDELTA','description'=>'Proyecto de coordinación y monitorización de red LTE de Yoigo (coordinación India incluido)','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=>'2016-01-01'],
			['name' => 'DAKOTA','description'=>'Proyecto de coordinación y monitorización de red LTE de ORA (coordinación India incluido)','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=> null ],
			['name' => 'VFE RR','description'=>'Proyecto de coordinación y monitorización de red LTE de VFN (coordinación India, LCC incluido)','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=>'2016-03-01'],
			['name' => 'OSP Refarm 1800 valla','description'=>'Refarming 1800 para ORA en Valladolid para dejar sitio a LTE en esa banda','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=>'2013-08-01'],
			['name' => 'OSP PVA freq swap','description'=>'Refarming de portadora U2100 en PVA para ORA','customer_id'=>  7  ,'start_date'=>'2013-07-01','end_date'=>'2014-01-01'],
			['name' => 'Sistemas Informáticos','description'=>'Gestión de equipos informáticos y red en 3dB consult','customer_id'=>  1  ,'start_date'=>'2005-03-01','end_date'=> null ],
			['name' => 'Formación','description'=>'Formación interna','customer_id'=>  1  ,'start_date'=>'2005-03-01','end_date'=>'2016-12-01'],
			['name' => 'Vacaciones','description'=>'Vacaciones','customer_id'=>  1  ,'start_date'=>'2005-03-01','end_date'=> null ],
			['name' => 'RREN Swap Macro a Micro','description'=>'Cambio de Bastidor de Macro a Micro en 5 estaciones ORA relacionado con RAN Renewal','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=>'2013-09-01'],
			['name' => 'OSP Refarm 1800 Vigo','description'=>'Refarming 1800 para ORA en Vigo para dejar sitio a LTE en esa banda','customer_id'=>  7  ,'start_date'=>'2013-09-01','end_date'=>'2013-09-01'],
			['name' => 'Venta de CITROEN C4','description'=>'Venta de coche','customer_id'=>  1  ,'start_date'=>'2013-08-01','end_date'=>'2013-08-01'],
			['name' => 'VFE AIRWAYS','description'=>'Monitorización 2G y 3G de nodos en cambio de SSRR e introducción de LTE para VFE','customer_id'=>  7  ,'start_date'=>'2013-08-01','end_date'=>'2014-04-01'],
			['name' => 'Medidas WIFI Gob Vasco 2','description'=>'Medidas de nivel de señal Wifi en escuelas para el Gobierno Vasco','customer_id'=>  10,'start_date'=>'2013-09-01','end_date'=>'2013-09-01'],
			['name' => 'Ericsson EON','description'=>'Elaboracíon de plan director de telecomunicaciones para EON Cantabria','customer_id'=>  7  ,'start_date'=>'2013-10-01','end_date'=>'2013-12-01'],
			['name' => 'OSP Refarm MAD-BCN-PVA','description'=>'Refarming en Madrid, Barcelona y PVA en DCS para hacer hueco a LTE','customer_id'=> 1  ,'start_date'=>'2013-10-01','end_date'=>'2013-12-01'],
			['name' => 'RAN BAU','description'=>'Ampliaciones en red radio ORA surgido del proyecto RR, y atendido luego desde proyecto RAN BAU (Business as usual)','customer_id'=>  7  ,'start_date'=>'2013-10-01','end_date'=>'2016-02-01'],
			['name' => 'NQA OC','description'=>'NQA trabajando para Integraciones Ericsson, aúna Rehomins y NQA','customer_id'=>  7  ,'start_date'=>'2013-09-01','end_date'=>'2016-12-01'],
			['name' => 'REFARMING PVA','description'=>'Añadir vecinas 3G2G en celdas Huawei dentro del OSP PVA freq Swap','customer_id'=>  4  ,'start_date'=>'2013-10-01','end_date'=>'2013-10-01'],
			['name' => 'Legacy Rehoming-Post RR','description'=>'Continuación del Legacy Rehoming','customer_id'=>  7  ,'start_date'=>'2014-01-01','end_date'=>'2014-07-01'],
			['name' => 'Venta de PEUGEOT 308','description'=>'Venta de coche','customer_id'=>  1  ,'start_date'=>'2014-01-01','end_date'=>'2014-01-01'],
			['name' => 'Reordenación Frecuencias EKTL','description'=>'Actualización de frecuencias de celdas 1 y 2 portadora U21 PVA en la red de Ericsson, 3G y 2G, y de Huawei.','customer_id'=>  7  ,'start_date'=>'2013-07-01','end_date'=>'2014-01-01'],
			['name' => 'BAJA/PERMISO','description'=>'Baja por enfermedad, permiso de exámenes, boda, traslado, etc.','customer_id'=>  1  ,'start_date'=>'2005-03-01','end_date'=> null ],
			['name' => 'REFARMING ORA Fase 5 a 12','description'=>'Refarming en provincias en DCS para hacer hueco a LTE','customer_id'=>  7  ,'start_date'=>'2014-01-01','end_date'=>'2015-11-01'],
			['name' => 'OSP MS','description'=>'Optimización Z1 y Z5., Managed Services Orange','customer_id'=>  7  ,'start_date'=>'2013-12-01','end_date'=>'2015-07-01'],
			['name' => 'Medidas WIFI Gob Vasco 3','description'=>'Medidas de nivel de señal Wifi en escuelas para el Gobierno Vasco','customer_id'=>  10,'start_date'=>'2014-02-01','end_date'=>'2014-03-01'],
			['name' => 'Ericsson EON Fase II','description'=>'Pilotos de diferentes tecnologías RF y análisis de soluciones de comunicaciones para CTs','customer_id'=>  7  ,'start_date'=>'2014-03-01','end_date'=>'2016-05-01'],
			['name' => 'EVERIS Traslado Equipamiento','description'=>'Traslado de Equipos de Comunicaciones entre dos sedes','customer_id'=>  9 ,'start_date'=>'2014-04-01','end_date'=>'2014-04-01'],
			['name' => 'VOLCADOS OSS TFCA','description'=>'Programación de scripts para obtención de volcados OSS 2G/3G Ericsson Telefónica','customer_id'=>  7  ,'start_date'=>'2014-05-01','end_date'=>'2014-05-01'],
			['name' => 'ANE TFCA','description'=>'Swap Nokia 2G a Ericsson  e Introducción de U900 y LTE. Diseño, monitorización y optimización. Telefónica','customer_id'=>  7  ,'start_date'=>'2014-06-01','end_date'=> null ],
			['name' => 'Servicios TME ANE GR Norte_IP','description'=>'1 consultor en Bilbao trabajando para TFCA','customer_id'=>  7  ,'start_date'=>'2014-07-01','end_date'=>'2015-06-01'],
			['name' => 'EUSKALTELWIFI_KALEAN','description'=>'Medidas sobre la red WIFI abierta de Euskaltel','customer_id'=>  8  ,'start_date'=>'2015-02-01','end_date'=>'2015-08-01'],
			['name' => 'CAMBIO LAC OSP','description'=>'Diseño e implementación de los cambios de definiciones de celdas en BSCs y RNCs de OSP por cambio de LAC y RAC','customer_id'=>  7  ,'start_date'=>'2015-02-01','end_date'=>'2015-11-01'],
			['name' => 'VFE TX','description'=>'Tareas de transmisión sobre la red de Vodafone','customer_id'=>  7  ,'start_date'=>'2015-06-01','end_date'=> null ],
			['name' => 'Formación Huawei','description'=>'Formación Huawei','customer_id'=>  1  ,'start_date'=>'2015-06-01','end_date'=>'2016-12-01'],
			['name' => 'OSP MS15','description'=>'Optimización Z1 y Z5., Managed Services Orange 2015','customer_id'=>  7  ,'start_date'=>'2015-06-01','end_date'=> null ],
			['name' => 'LITUANIA','description'=>'Inversión, desarrollo de negocio Lituania-Mainsec','customer_id'=>  1  ,'start_date'=>'2015-06-01','end_date'=> null ],
			['name' => 'CONSULTORIA','description'=>'Servicios prestados por consultores externos','customer_id'=>  7  ,'start_date'=>'2015-07-01','end_date'=> null ],
			['name' => 'ASESORIA DESPLIEGUE LTE','description'=>'Este proyecto incluye  la asesoría en Despliegue. No incluye una posible participación en el Managed Services tras el Despliegue','customer_id'=>  8  ,'start_date'=>'2015-07-01','end_date'=>'2015-11-01'],
			['name' => 'RAN EVO','description'=>'Proyecto de ampliaciones de red e implantaciones de Orange fusión de DELTA y RAN BAU. Fundamentalmente es un proyecto de despliegue 4G','customer_id'=>  7  ,'start_date'=>'2015-09-01','end_date'=> null ],
			['name' => 'ASESORIA MERCEDES','description'=>'Medidas cobertura y soluciones de ingenieria','customer_id'=>  11,'start_date'=>'2016-01-01','end_date'=>'2016-02-01'],
			['name' => 'INTERNACIONALIZACION','description'=>'Proceso de apertura a mercados exteriores','customer_id'=>  1  ,'start_date'=>'2016-01-01','end_date'=>'2016-09-01'],
			['name' => 'Formación ZTE','description'=>'Formación ZTE','customer_id'=>  1  ,'start_date'=>'2016-03-01','end_date'=>'2016-12-01'],
			['name' => 'VFE Ran Bau','description'=>'Proyecto de coordinación y monitorización de red LTE de VFN (coordinación India, LCC incluido)','customer_id'=>  7  ,'start_date'=>'2016-04-01','end_date'=> null ],
			['name' => 'SON 16','description'=>'Elección de una herramienta SON de apoyo a la optimización de red, de las disponibles en el mercado','customer_id'=>  1  ,'start_date'=>'2016-05-01','end_date'=> null ],
			['name' => 'NOSEVENT','description'=>'Eventos NOS Portugal','customer_id'=>  7  ,'start_date'=>'2016-05-01','end_date'=> null ],
			['name' => 'EWK MEDIDAS 2016','description'=>'Medidas WIFI Euskaltel 2016','customer_id'=>  8  ,'start_date'=>'2016-05-01','end_date'=>'2016-05-01'],
			['name' => '3dB EMT','description'=>'Event Monitoring Tool. Herramienta de supervisión de red que permite garantizar la calidad de servicio en eventos especiales en redes de telefonía móvil, a través de la automatización de procesos de monitorización y la inteligencia en la toma de decisiones de resolución de problemas','customer_id'=>  1  ,'start_date'=>'2016-05-01','end_date'=> null ],
			['name' => '3dB CEM','description'=>'Customer Experience Management Service. Definición de servicios a partir de la medición de la experiencia de cliente dentro de 3dB consult. Estudio del estado del arte del CEM. Selección de partner para realización del servicio. Definición de servicios a ofertar a los clientes. Creación de herramientas necesarias. Generación de documentación de marketing','customer_id'=>  1  ,'start_date'=>'2016-06-01','end_date'=> null ],
			['name' => 'YOIGO TX','description'=>'Trabajos de diseño de red TX para Yoigo','customer_id'=>  13,'start_date'=>'2016-06-01','end_date'=> null ],
			['name' => 'NQA ANE def Externals ','description'=>'Definición Externals NQA ANE','customer_id'=>  7  ,'start_date'=>'2016-08-01','end_date'=> null ],
			['name' => '3dB NESTART','description'=>'Evaluar la posisibilidad de desarrollo de un ITK propio en 3dB Network Statistics Reporting Tool','customer_id'=>  1  ,'start_date'=>'2016-10-01','end_date'=> null ],
			['name' => '3dB CC','description'=>'Desarrollo de herramienta de CC interna','customer_id'=>  1  ,'start_date'=>'2016-10-01','end_date'=> null ],
			['name' => '3dB RTM','description'=>'Desarrollo de herramienta de Real Time Monitoring','customer_id'=>  1  ,'start_date'=>'2016-05-01','end_date'=> null ],
			['name' => '3dB Marketing - Social Networks','description'=>'Marketing a través de redes sociales como Linkedin u otras para posicionar a la empresa','customer_id'=>  1  ,'start_date'=>'2016-09-01','end_date'=> null ],
			['name' => '3dB Marketing – Baltics','description'=>'Marketing para conseguir contratos en Letonia, Estonia y Lituania, visitas, soporte de ventas, viajes, etc.','customer_id'=>  1  ,'start_date'=>'2016-09-01','end_date'=> null ],
			['name' => '3dB Marketing – Nordics','description'=>'Marketing para conseguir contratos en Dinamarca, Noruega, Finlandia, Suecia+ Islandia, visitas, soporte de ventas, viajes, etc.','customer_id'=>  1  ,'start_date'=>'2016-09-01','end_date'=> null ],
			['name' => '3dB Marketing – Spain','description'=>'Marketing para conseguir contratos en España, visitas, soporte de ventas, viajes, etc.','customer_id'=>  1  ,'start_date'=>'2016-09-01','end_date'=> null ],
			['name' => '3dB Marketing – Portugal','description'=>'Marketing para conseguir contratos en Portugal, visitas, soporte de ventas, viajes, etc.','customer_id'=>  1  ,'start_date'=>'2016-09-01','end_date'=> null ],
			['name' => '3DB DATA PN','description'=>'Plan de negocio Big Data','customer_id'=>  1  ,'start_date'=>'2016-11-01','end_date'=> null ],
			['name' => 'F Curso','description'=>'Asistencia a cursos de formación de 3dB o externos validados por el responsable de formación previamente a su celebración.','customer_id'=>  1  ,'start_date'=>'2017-01-01','end_date'=> null ],
			['name' => 'F Puesto','description'=>'Formación en el puesto hasta el número de horas asignadas por el RP para la formación en una tarea puesto concretado. El RP informará previo a la ejecución de la acción a la dirección a través de un correo: CF F Puesto #nombre apellidos# #puesto# #horas# de la acción formativa. La formación en el puesto implica el aprendizaje de una nueva competencia (Diseño, CC, IT, Drive Test, Exports, etc.) por parte de la persona que se forma. No se entiende formación la adaptación a procesos específicos de proyecto. Únicamente la persona que se forma reportará sus horas de formación y las horas del formador.','customer_id'=>  1  ,'start_date'=>'2017-01-01','end_date'=> null ],
			['name' => 'F Desarrollo','description'=>'Tiempo personal dedicado a desarrollar contenidos ya sea para una línea de investigación, un grupo de trabajo o asistencia a reuniones de grupo de desarrollo.','customer_id'=>  1  ,'start_date'=>'2017-01-01','end_date'=> null ],
			['name' => 'NQA','description'=>'NQA','customer_id'=>  7  ,'start_date'=>'2017-01-01','end_date'=> null ],
			['name' => 'MEDIDAS WIFI MERCEDES','description'=>'Medidas Wifi fábrica MBE Vitoria','customer_id'=>  11,'start_date'=>'2017-01-01','end_date'=>'2017-02-01'],
			['name' => 'ELISA PILOTO','description'=>'Piloto de Auditoría de red sobre cluster H LTE','customer_id'=>  5  ,'start_date'=>'2017-02-01','end_date'=> null ],
			['name' => 'MEDIDAS WIFI BERRIO-OTXOA','description'=>'Medidas WIFI Colegio Berrio-Otxoa','customer_id'=> 3,'start_date'=>'2017-01-01','end_date'=>'2017-02-01']
			['name' => ' 3dB Gestión Interna ','description'=>'BBDD web para gestión interna de vacaciones, formación, reporte de horas, evaluación de las personas, etc.','customer_id'=> 1,'start_date'=>'2017-03-01','end_date'=> null]
		];
		*/

        $projects=[
			[
				'name'               => 'MIND Ingenieria',
				'description'        => 'Renovación de la red de TX de ORA con equipos Alcatel-Lucent',
				'customer_id'        => 2,
				'start_date'         => '2011-12-01',
				'end_date'           => null,
			],
			[
				'name'               => 'MEDIDAS WIFI MERCEDES',
				'description'        => 'Medidas Wifi fábrica MBE Vitoria',
				'customer_id'        => 11,
				'start_date'         => '2017-01-01',
				'end_date'           => '2017-02-01',
			],
			[
				'name'               => 'RAN EVO',
				'description'        => 'Proyecto de ampliaciones de red e implantaciones de Orange fusión de DELTA y RAN BAU. Fundamentalmente es un proyecto de despliegue 4G',
				'customer_id'        => 7,
				'start_date'         => '2015-09-01',
				'end_date'           => null,
			],
			[
				'name'               => 'ANE TFCA',
				'description'        => 'Swap Nokia 2G a Ericsson  e Introducción de U900 y LTE. Diseño, monitorización y optimización. Telefónica',
				'customer_id'        => 7,
				'start_date'         => '2014-06-01',
				'end_date'           => null,
			],
		];

        foreach ($projects as $project) {
			DB::table('projects')->insert([
				'name'               => $project['name'],
				'description'        => $project['description'],
				'customer_id'        => $project['customer_id'],
				'start_date'         => $project['start_date'],
				'end_date'           => $project['end_date'],
			]);
		}
    }
}
