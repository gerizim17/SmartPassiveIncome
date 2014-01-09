<?php

class ReturnoninvestmentsController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $roi = new Returnoninvestment;
        $re_id = Input::get('re_id');
        $roi->roi_re_id = $re_id;
        $roi->roi_down_payment = Input::get('roi_down_payment');
        $roi->roi_closing_costs = Input::get('roi_closing_costs');
        $roi->roi_misc_expenses = Input::get('roi_misc_expenses');
        $roi->roi_init_investment = Input::get('roi_init_investment');
        $roi->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $re_id = Input::get('re_id');
        $roi = Returnoninvestment::getByReId($re_id);
        $roi->roi_down_payment = Input::get('roi_down_payment');
        $roi->roi_closing_costs = Input::get('roi_closing_costs');
        $roi->roi_misc_expenses = Input::get('roi_misc_expenses');
        $roi->roi_init_investment = Input::get('roi_init_investment');
        $roi->save();
        return Redirect::action('MontecarloController@index', $re_id);
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        $roi = Returnoninvestment::findOrFail($re_id);
        $roi->delete();

        return Redirect::action('MontecarloController@index');
    }
}