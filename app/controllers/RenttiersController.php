<?php

class RenttiersController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $renttier = new Renttier;
        $rentaldetail = new Rentaldetail;        
        $re_id = Input::get('re_id');
        $renttier->rt_re_id = $re_id;
        $renttier->rt_units = Input::get('rt_units');
        $renttier->rt_rent = Input::get('rt_rent');        
        $renttier->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $rentaldetail = new Rentaldetail;
        $re_id = Input::get('re_id');             
        $renttier = renttier::getByReId($re_id);        
        $renttier->rt_units = Input::get('rt_units');
        $renttier->rt_rent = Input::get('rt_rent');        
        $renttier->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        $renttier = renttier::getByRdId($re_id);
        $renttier->delete();

        return Redirect::action('MontecarloController@index');
    }
}