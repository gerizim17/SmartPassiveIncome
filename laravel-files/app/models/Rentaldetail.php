<?php


class Rentaldetail extends Eloquent
{	
	public $table = 'rentaldetail';

	public static function getByReId($id){

		return Rentaldetail::whereRealestateId($id)
			->first();		
	}
}