<?php

class Returnoninvestment extends Eloquent
{
	public $table = 'returnoninvestment';

	public static function getByReId($id){

		return Returnoninvestment::whereRealestateId($id)
			->first();		
	}
}