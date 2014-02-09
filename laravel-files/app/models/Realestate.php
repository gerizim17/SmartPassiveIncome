<?php


class Realestate extends BaseModel {
	public $table = 'realestate';

	public static $rules = array(
       	'address1' => 'Required',
   		'city'     => 'Required',
  	);

  	public static $messages = array(
        'address1.Required' => 'Address1 field is required.'
    );

    public static function getByUser($id){
		return Realestate::whereUserId($id)
			->get();		
	}

}