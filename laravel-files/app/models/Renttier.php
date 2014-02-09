<?php


class Renttier extends Eloquent
{	
	public $table = 'renttier';

	public static function getByReId($id){

		return Renttier::whereRealestateId($id)
			->first();		
	}
}