<?php

class ReturnoninvestmentsController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function handleCreate()
    {
        // Handle create form submission.
        $roi = new Returnoninvestment;
        $realestate_id = Input::get('realestate_id');
        $roi->realestate_id = $realestate_id;
        $roi->down_payment = Input::get('down_payment');
        $roi->closing_costs = Input::get('closing_costs');
        $roi->misc_expenses = Input::get('misc_expenses');
        $roi->init_investment = Input::get('init_investment');
        $roi->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $realestate_id = Input::get('realestate_id');
        $roi = Returnoninvestment::getByReId($realestate_id);
        $roi->down_payment = Input::get('down_payment');
        $roi->closing_costs = Input::get('closing_costs');
        $roi->misc_expenses = Input::get('misc_expenses');
        $roi->init_investment = Input::get('init_investment');
        $roi->save();
        return Redirect::action('MontecarloController@index', $realestate_id);
    }


    public function handleDelete($realestate_id)
    {
        // Handle the delete confirmation.       
        $roi = Returnoninvestment::findOrFail($realestate_id);
        $roi->delete();

        return Redirect::action('MontecarloController@index');
    }
}