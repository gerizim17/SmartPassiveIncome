<?php

class Returnoninvestment extends Eloquent
{
	protected $primaryKey = 'roi_id';

	public static function getByReId($id){

		return Returnoninvestment::whereRoiReId($id)
			->first();		
	}
}