<?php

class RentaldetailsController extends BaseController
{

    public function handleCreate()
    {
        // Handle create form submission.
        $rentaldetail = new Rentaldetail;
        $re_id = Input::get('re_id');
        $rentaldetail->rd_re_id = $re_id;
        $rentaldetail->rd_months_min = Input::get('rd_months_min');
        $rentaldetail->rd_months_max = Input::get('rd_months_max');
        $rentaldetail->rd_repair_min = Input::get('rd_repair_min');
        $rentaldetail->rd_repair_max = Input::get('rd_repair_max');
        $rentaldetail->rd_pm_monthly_charge = Input::get('rd_pm_monthly_charge');
        $rentaldetail->rd_pm_vacancy_charge = Input::get('rd_pm_vacancy_charge');
        $rentaldetail->save();

        return Redirect::action('MontecarloController@index', $re_id);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $re_id = Input::get('re_id');
        $rentaldetail = Rentaldetail::getByReId($re_id);
        $rentaldetail->rd_months_min = Input::get('rd_months_min');
        $rentaldetail->rd_months_max = Input::get('rd_months_max');
        $rentaldetail->rd_repair_min = Input::get('rd_repair_min');
        $rentaldetail->rd_repair_max = Input::get('rd_repair_max');
        $rentaldetail->rd_pm_monthly_charge = Input::get('rd_pm_monthly_charge');
        $rentaldetail->rd_pm_vacancy_charge = Input::get('rd_pm_vacancy_charge');
        $rentaldetail->save();
        return Redirect::action('MontecarloController@index', $re_id);
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        $rentaldetail = Rentaldetail::findOrFail($re_id);
        $rentaldetail->delete();

        return Redirect::action('MontecarloController@index');
    }
}