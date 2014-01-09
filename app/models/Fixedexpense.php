<?php

class Fixedexpense extends Eloquent
{
	protected $primaryKey = 'fe_id';

	public static function getByReId($id){

		return Fixedexpense::whereFeReId($id)
			->first();		
	}
}