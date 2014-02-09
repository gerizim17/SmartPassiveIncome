<?php

class Rentalhistory extends Eloquent
{	
	public $table = 'rentalhistory';
	public static function getByReId($id){
		return Rentalhistory::whereRealestateId($id)
			->get();		
	}

	public static function getByReIdBetweenDates($id, $start_date, $end_date){
		return Rentalhistory::whereRealestateId($id)
			->whereBetween('date', array($start_date, $end_date))
			->get();		
	}

	public static function getByReIdBetweenDatesToSql($id, $start_date, $end_date){
		return Rentalhistory::whereRealestateId($id)
			->whereBetween('date', array($start_date, $end_date))
			->toSql();		
	}
}