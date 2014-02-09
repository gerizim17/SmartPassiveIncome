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
        // if ($v->passes()) {            
        //     return true;
        // } else {
        //     // save the input to the current session
        //     Input::flash();
        //     self::$validationMessages = $v->messages();
        //     return false;
        // }
    }

}