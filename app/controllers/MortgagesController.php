<?php

class MortgagesController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $mortgage = new Mortgage;
        $re_id = Input::get('re_id');
        $mortgage->mg_re_id = $re_id;
        $mortgage->mg_monthly_payment = Input::get('mg_monthly_payment');
        $mortgage->mg_sale_price = Input::get('mg_sale_price');
        $mortgage->mg_interest_rate = Input::get('mg_interest_rate');
        $mortgage->mg_percent_down = Input::get('mg_percent_down');
        $mortgage->mg_pmi = Input::get('mg_pmi');
        $mortgage->mg_term = Input::get('mg_term');
        $mortgage->mg_term2 = Input::get('mg_term2');
        $mortgage->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $re_id = Input::get('re_id');
        $mortgage = Mortgage::getByReId($re_id);
        $mortgage->mg_monthly_payment = Input::get('mg_monthly_payment');
        $mortgage->mg_sale_price = Input::get('mg_sale_price');
        $mortgage->mg_interest_rate = Input::get('mg_interest_rate');
        $mortgage->mg_percent_down = Input::get('mg_percent_down');
        $mortgage->mg_pmi = Input::get('mg_pmi');
        $mortgage->mg_term = Input::get('mg_term');
        $mortgage->mg_term2 = Input::get('mg_term2');
        $mortgage->save();
        return Redirect::action('MontecarloController@index', $re_id);
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        $mortgage = Mortgage::findOrFail($re_id);
        $mortgage->delete();

        return Redirect::action('MontecarloController@index');
    }
}