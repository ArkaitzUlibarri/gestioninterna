<?php

use Carbon\Carbon;

class SeederConfig
{
	/**
	 * Fecha de hoy
	 */
	public static function TODAY() {
		return Carbon::today();
	}

	/**
	 * Numero de timeslots del reporte
	 */
	const TIME_SLOTS = 11;//Todo el dia son 33 timeslots=8,25 horas

	/**
	 * Años de disponibilidad de los cursos
	 */
	const AVAILABLE_YEARS = ['2016', '2017'];

	/**
	 * Horas de duracion de los cursos
	 */
	const CLASS_HOURS = 2;

	/**
	 * Factor de teleworking,para que no todos los contratos sean teletrabajo
	 */
	const TELEWORKING_FACTOR = 40;

	/**
	 * Factor de reduccion de jornada,para que no todos los contratos sean reduccion
	 */
	const REDUCTION_FACTOR = 40;

	/**
	 * Fecha de comienzo de los cursos
	 */
	public static function START_DATE() {
		return Carbon::createFromDate(2016,1,1);
	}

	/**
	 * Fecha de fin de los cursos
	 */
	public static function END_DATE() {
		return Carbon::createFromDate(2017,12,31);
	}
}