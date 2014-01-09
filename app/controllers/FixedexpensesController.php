<?php

class FixedexpensesController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $fixedexpenses = new Fixedexpense;
        $re_id = Input::get('re_id');
        $fixedexpenses->fe_re_id = $re_id;
        $fixedexpenses->fe_taxes = Input::get('fe_taxes');
        $fixedexpenses->fe_insurance = Input::get('fe_insurance');
        $fixedexpenses->fe_utilities = Input::get('fe_utilities');
        $fixedexpenses->fe_misc = Input::get('fe_misc');
        $fixedexpenses->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $re_id = Input::get('re_id');
        $fixedexpenses = fixedexpenses::getByReId($re_id);
        $fixedexpenses->fe_taxes = Input::get('fe_taxes');
        $fixedexpenses->fe_insurance = Input::get('fe_insurance');
        $fixedexpenses->fe_utilities = Input::get('fe_utilities');
        $fixedexpenses->fe_misc = Input::get('fe_misc');
        $fixedexpenses->save();
        return Redirect::action('MontecarloController@index', $re_id);
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        $fixedexpenses = fixedexpenses::findOrFail($re_id);
        $fixedexpenses->delete();

        return Redirect::action('MontecarloController@index');
    }
}