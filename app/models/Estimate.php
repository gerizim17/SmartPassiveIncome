<?php


class Estimate extends Eloquent
{
	protected $primaryKey = 'est_id';

	public static function getByReId($id){

		return Estimate::whereEstReId($id)
			->first();		
	}
}