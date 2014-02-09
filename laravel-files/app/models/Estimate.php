<?php


class Estimate extends Eloquent
{
	public $table = 'estimate';

	public static function getByReId($id){

		return Estimate::whereRealestateId($id)
			->first();		
	}
}