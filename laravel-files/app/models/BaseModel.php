<?php

class BaseModel extends Eloquent 
{

    public static $validationMessages = null;

    public static function validate($input = null) {
        if (is_null($input)) {
            $input = Input::all();
        }

        $customMessage = array();

        if(isset(static::$messages)){
            $customMessage = static::$messages;
        }

        $v = Validator::make($input, static::$rules, $customMessage);

        return $v;       
    }

}