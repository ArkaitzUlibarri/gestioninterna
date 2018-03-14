<?php

return [
	'absences'=> [		
		'leave',
		'holidays',
		'others',
		'sick leave'
	],

	'holidays' => [
		'current_year',
		'last_year',
		'next_year',
		'extras'
	],

	'periods' => [
		'Today',
		'Yesterday',
		'This week',
		'Last week',
		'This month',
		'Last month',
		'This year',
		'Last year',
	],

	'training' => [
		'course',
		'development',
		'learning on the job'
	],

	'typeOfJob' => [
		'on site work',
		'remote',
		'teleworking'
	],

	'types' => [
		'on site work',
		'remote',
	],

	'status' => [
		'active',
		'inactive'
	],

	'validations' => [
		'validated',
		'not validated'
	],

	'daysWeek' => [
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
		'sunday'
	],

	'Weekdays' => [
		'Monday',
		'Tuesday',
		'Wednesday',
		'Thursday',
		'Friday',
	],

	'Weekends' => [
		'Saturday',
		'Sunday'
	],

	//Evaluation
	'criterion' => [
		'quality',
		'efficiency',
		'knowledge',
		'availability'
	],

	'months' => [
		'January'   => 1,
		'February'  => 2,
		'March'     => 3,
		'April'     => 4,
		'May'       => 5,
		'June'      => 6,
		'July'      => 7,
		'August'    => 8,
		'September' => 9,
		'October'   => 10,
		'November'  => 11,
		'December'  => 12
	],

	'performance_evaluation' => [
		[
			'code' => 'quality',
			'name' => 'Calidad del trabajo realizado',
			'percentage' => 30,
			'description' => 'Calidad del trabajo realizado en el puesto para el cliente interno y externo. Tiene en cuenta para el puesto: el dominio técnico, la gestión del trabajo, la gestión de personas, la orientación al cliente y el trabajo en equipo a nivel de proyecto y de empresa',
			'points' => [

				[
					'value' => 0,
					'description'=> 'Comete errores repetitivos o que suponen denuncias graves por parte del cliente'
				],
				[
					'value' => 1,
					'description'=> 'Comete errores no repetitivos que no suponen denuncias graves por parte del cliente'
				],
				[
					'value' => 2,
					'description'=> 'Relación profesional con el cliente. Practicamente no comete errores y los suele subsanar antes de que el cliente los reclame'
				],
				[
					'value' => 3,
					'description'=> 'Calidad excelente percibida por RP y cliente. No comete errores casi nunca. Resolución proactiva de problemas. Propone mejoras para incrementar la calidad. Recibe felicitaciones del cliente'
				],

			],
		],

		[
			'code' => 'efficiency',
			'name' => 'Eficiencia en el puesto',
			'percentage' => 35,
			'description' => 'El RP con su experiencia establece el criterio de medida de la eficiencia y el valor medio de dicho criterio para cada puesto en el proyecto y lo aprueba DI. La medida debe ser lo más objetiva posible.Tiene en cuenta para el puesto: el dominio técnico, la gestión del trabajo, la gestión de personas, la orientación al cliente y el trabajo en equipo.Puede incluir: nodos tratados, procesos mejorados, eficiencia en la gestión del correo, etc.',
			'points' => [

				[
					'value' => 0,
					'description'=> 'Eficiencia inferior a la media para el puesto en el proyecto en un 40% o más'
				],

				[
					'value' => 1,
					'description'=> 'Eficiencia inferior a la media para el puesto en el proyecto'
				],

				[
					'value' => 2,
					'description'=> 'Eficiencia igual o superior a la media para el puesto en el proyecto'
				],

				[
					'value' => 3,
					'description'=> 'Eficiencia un 50% superior a la media para el puesto en el proyecto'
				],

			],
		],

		[
			'code' => 'knowledge',
			'name' => 'Contribución al desarrollo del conocimiento',
			'percentage' => 20,
			'description' => 'La medida se realiza anualmente por parte del responsable de formación de 3dB consult.Horas invertidas y aprovechamiento de las mismas',
			'points' => [

				[
					'value' => 0,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 1,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 2,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 3,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 4,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 5,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 6,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 7,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 8,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 9,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],
				[
					'value' => 10,
					'description'=> 'Desarrollo de líneas de investigación y contenidos formativos nuevos para la empresa y propagación de los mismos al resto de la organización'
				],

			],
		],

		[
			'code' => 'availability',
			'name' => 'Disponibilidad',
			'percentage' => 15,
			'description' => 'Disponibilidad para trabajos no planificados, turnos, festivos, trabajo nocturno, cobertura de vacaciones, ayudas a compañeros, cambios de proyecto por necesidades de la empresa, proyectos que requieran cambio del centro de trabajo a nivel nacional o internacional',
			'points' => [

				[
					'value' => 0,
					'description'=> 'Negativa a realizar una disponibiilidad, sin motivo razonable que lo justifique'
				],
				[
					'value' => 1,
					'description'=> 'Actitud negativa ante solicitudes de disponibilidad, aceptando realizarlas cuando no hay más remedio'
				],
				[
					'value' => 2,
					'description'=> 'Acepta las solicitudes que se le requieren dentro de las posibilidades personales'
				],
				[
					'value' => 3,
					'description'=> 'Disposición total aportar soluciones a las necesidades,  proporcionando máxima flesibilidad dentro de las posibilidades personales'
				],

			],
		],

	],



];