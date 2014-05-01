<?php


class Estimateworst extends Eloquent
{
	public $table = 'estimateworst';

	public static function getByReId($id){

		return Estimateworst::whereRealestateId($id)
			->first();		
	}
}