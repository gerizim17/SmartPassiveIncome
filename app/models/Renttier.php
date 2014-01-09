<?php


class Renttier extends Eloquent
{
	protected $primaryKey = 'rt_id';

	public static function getByReId($id){

		return Renttier::whereRtReId($id)
			->first();		
	}
}