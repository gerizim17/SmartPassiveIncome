<?php

class MortgagesController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function handleCreate()
    {
        // Handle create form submission.
        $mortgage = new Mortgage;
        $realestate_id = Input::get('realestate_id');
        $mortgage->realestate_id = $realestate_id;
        $mortgage->monthly_payment = Input::get('monthly_payment');
        $mortgage->sale_price = Input::get('sale_price');
        $mortgage->interest_rate = Input::get('interest_rate');
        $mortgage->percent_down = Input::get('percent_down');
        $mortgage->pmi = Input::get('pmi');
        $mortgage->term = Input::get('term');
        $mortgage->term2 = Input::get('term2');
        $mortgage->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $realestate_id = Input::get('realestate_id');
        $mortgage = Mortgage::getByReId($realestate_id);
        $mortgage->monthly_payment = Input::get('monthly_payment');
        $mortgage->sale_price = Input::get('sale_price');
        $mortgage->interest_rate = Input::get('interest_rate');
        $mortgage->percent_down = Input::get('percent_down');
        $mortgage->pmi = Input::get('pmi');
        $mortgage->term = Input::get('term');
        $mortgage->term2 = Input::get('term2');
        $mortgage->save();
        return Redirect::action('MontecarloController@index', $realestate_id);
    }


    public function handleDelete($realestate_id)
    {
        // Handle the delete confirmation.       
        $mortgage = Mortgage::findOrFail($realestate_id);
        $mortgage->delete();

        return Redirect::action('MontecarloController@index');
    }
}