<?php


class Estimatebest extends Eloquent
{
	public $table = 'estimatebest';

	public static function getByReId($id){

		return Estimatebest::whereRealestateId($id)
			->first();		
	}
}