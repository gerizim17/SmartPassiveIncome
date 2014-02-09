<?php

class RentaldetailsController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $rentaldetail = new Rentaldetail;
        $realestate_id = Input::get('realestate_id');
        $rentaldetail->realestate_id = $realestate_id;
        $rentaldetail->months_min = Input::get('months_min');
        $rentaldetail->months_max = Input::get('months_max');
        $rentaldetail->repair_min = Input::get('repair_min');
        $rentaldetail->repair_max = Input::get('repair_max');
        $rentaldetail->pm_monthly_charge = Input::get('pm_monthly_charge');
        $rentaldetail->pm_vacancy_charge = Input::get('pm_vacancy_charge');
        $rentaldetail->save();

        return Redirect::action('MontecarloController@index', $realestate_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $realestate_id = Input::get('realestate_id');
        $rentaldetail = Rentaldetail::getByReId($realestate_id);
        $rentaldetail->months_min = Input::get('months_min');
        $rentaldetail->months_max = Input::get('months_max');
        $rentaldetail->repair_min = Input::get('repair_min');
        $rentaldetail->repair_max = Input::get('repair_max');
        $rentaldetail->pm_monthly_charge = Input::get('pm_monthly_charge');
        $rentaldetail->pm_vacancy_charge = Input::get('pm_vacancy_charge');
        $rentaldetail->save();
        return Redirect::action('MontecarloController@index', $realestate_id);
    }


    public function handleDelete($realestate_id)
    {
        // Handle the delete confirmation.       
        $rentaldetail = Rentaldetail::findOrFail($realestate_id);
        $rentaldetail->delete();

        return Redirect::action('MontecarloController@index');
    }
}