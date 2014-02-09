<?php

class Fixedexpense extends Eloquent
{
	public $table = 'fixedexpense';

	public static function getByReId($id){

		return Fixedexpense::whereRealestateId($id)
			->first();
	}
}