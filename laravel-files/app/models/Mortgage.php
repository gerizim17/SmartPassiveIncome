<?php

class Mortgage extends BaseModel
{
	public $table = 'mortgage';

	public static $rules = array(
     	'sale_price' => 'Required'   	
  	);


	public static function getByReId($id){

		return Mortgage::whereRealestateId($id)
			->first();		
	}
}