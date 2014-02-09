<?php

class Mortgage extends Eloquent
{
	public $table = 'mortgage';

	public static function getByReId($id){

		return Mortgage::whereRealestateId($id)
			->first();		
	}
}