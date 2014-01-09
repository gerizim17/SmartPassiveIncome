<?php

class Mortgage extends Eloquent
{
	protected $primaryKey = 'mg_id';

	public static function getByReId($id){

		return Mortgage::whereMgReId($id)
			->first();		
	}
}