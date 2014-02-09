<?php

class FixedexpensesController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $fixedexpenses = new Fixedexpense;
        $realestate_id = Input::get('realestate_id');
        $fixedexpenses->realestate_id = $realestate_id;
        $fixedexpenses->taxes = Input::get('taxes');
        $fixedexpenses->insurance = Input::get('insurance');
        $fixedexpenses->utilities = Input::get('utilities');
        $fixedexpenses->misc = Input::get('misc');
        $fixedexpenses->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $realestate_id = Input::get('realestate_id');
        $fixedexpenses = fixedexpenses::getByReId($realestate_id);
        $fixedexpenses->taxes = Input::get('taxes');
        $fixedexpenses->insurance = Input::get('insurance');
        $fixedexpenses->utilities = Input::get('utilities');
        $fixedexpenses->misc = Input::get('misc');
        $fixedexpenses->save();
        return Redirect::action('MontecarloController@index', $realestate_id);
    }


    public function handleDelete($realestate_id)
    {
        // Handle the delete confirmation.       
        $fixedexpenses = fixedexpenses::findOrFail($realestate_id);
        $fixedexpenses->delete();

        return Redirect::action('MontecarloController@index');
    }
}