<?php


class Rentaldetail extends Eloquent
{
	protected $primaryKey = 'rd_id';

	public static function getByReId($id){

		return Rentaldetail::whereRdReId($id)
			->first();		
	}
}