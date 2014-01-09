<?php

class BaseModel extends Eloquent 
{

    public static $validationMessages = null;

    public static function validate($input = null) {
        if (is_null($input)) {
            $input = Input::all();
        }

        $v = Validator::make($input, static::$rules);

        if ($v->passes()) {
            return true;
        } else {
            // save the input to the current session
            Input::flash();
            self::$validationMessages = $v->getMessages();
            return false;
        }
    }

}