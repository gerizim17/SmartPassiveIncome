<?php

class RenttiersController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function handleCreate()
    {
        // Handle create form submission.
        $renttier = new Renttier;
        $rentaldetail = new Rentaldetail;        
        $realestate_id = Input::get('realestate_id');
        $renttier->realestate_id = $realestate_id;
        $renttier->units = Input::get('units');
        $renttier->rent = Input::get('rent');        
        $renttier->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $rentaldetail = new Rentaldetail;
        $realestate_id = Input::get('realestate_id');             
        $renttier = renttier::getByReId($realestate_id);        
        $renttier->units = Input::get('units');
        $renttier->rent = Input::get('rent');        
        $renttier->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }


    public function handleDelete($realestate_id)
    {
        // Handle the delete confirmation.       
        $renttier = renttier::getByRdId($realestate_id);
        $renttier->delete();

        return Redirect::action('MontecarloController@index');
    }
}