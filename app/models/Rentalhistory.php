<?php

class Rentalhistory extends Eloquent
{
	protected $primaryKey = 'rh_id';

	public static function getByReId($id){
		return Rentalhistory::whereRhReId($id)
			->get();		
	}

	public static function getByReIdBetweenDates($id, $start_date, $end_date){
		return Rentalhistory::whereRhReId($id)
			->whereBetween('rh_date', array($start_date, $end_date))
			->get();		
	}

	public static function getByReIdBetweenDatesToSql($id, $start_date, $end_date){
		return Rentalhistory::whereRhReId($id)
			->whereBetween('rh_date', array($start_date, $end_date))
			->toSql();		
	}
}