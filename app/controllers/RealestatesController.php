<?php

class RealestatesController extends BaseController
{
    public function index()
    {
        // Show a listing of real estate.
        //$realestates = Realestate::all();        
        //return View::make('realestateIndex', compact('realestates'));        

        $realestates = Realestate::all(); 
        $mortgages = Mortgage::all(); 
        $realestates = compact('realestates');
        $mortgages = compact('mortgages');
        return View::make('realestateIndex')
            ->with($realestates)
            ->with($mortgages);
    }

    public function create()
    {
        // Show the create real estate form.
        return View::make('realEstateCreate');
    }

    public function handleCreate()
    {
        // Handle create form submission.
        $realestate = new Realestate;
        $mortgage = new Mortgage;

        $realestate->re_address1 = Input::get('re_address1');
        $realestate->re_address2 = Input::get('re_address2');
        $realestate->re_city = Input::get('re_city');
        $realestate->re_state = Input::get('re_state');
        $realestate->re_zip  = Input::get('re_zip');
        $realestate->save();
        
        $mortgage->mg_re_id = $realestate->re_id;
        $mortgage->mg_sale_price = Input::get('mg_sale_price');
        $mortgage->mg_monthly_payment = 0;
        $mortgage->save();
        return Redirect::action('RealestatesController@index');
    }

    public function edit($re_id)
    {   
        // Show the edit real estate form.    
        $realestate = Realestate::find($re_id);
        $mortgage = Mortgage::getByReId($re_id);
        $realestate = compact('realestate');
        $mortgage = compact('mortgage');
        return View::make('realEstateEdit')
            ->with($realestate)
            ->with($mortgage);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $realestate = Realestate::findOrFail(Input::get('re_id'));
        $realestate->re_address1 = Input::get('re_address1');
        $realestate->re_address2 = Input::get('re_address2');
        $realestate->re_city = Input::get('re_city');
        $realestate->re_state = Input::get('re_state');
        $realestate->re_zip  = Input::get('re_zip');
        $realestate->save();

        $mortgage = Mortgage::getByReId(Input::get('re_id'));
        $mortgage->mg_sale_price = Input::get('mg_sale_price');        
        $mortgage->save();

        return Redirect::action('RealestatesController@index');
    }


    public function handleDelete($re_id)
    {
        // Handle the delete confirmation.       
        //$estimate = Estimate::getByReId($re_id);
        //$fixedexpense = Fixedexpense::getByReId($re_id);
        $mortgage = Mortgage::getByReId($re_id);
        //$rentaldetail = Rentaldetail::getByReId($re_id);
        //$rentalhistory = Rentalhistory::getByReId($re_id);
        //$renttier = Renttier::getByReId($re_id);
        //$returnoninvestment = Returnoninvestment::getByReId($re_id);
        $realestate = Realestate::findOrFail($re_id);

        //$estimate->delete();
        //$fixedexpense->delete();
        $mortgage->delete();
        //$rentaldetail->delete();
        //$rentalhistory->delete();
        //$renttier->delete();
        //$returnoninvestment->delete();
        $realestate->delete();

        return Redirect::action('RealestatesController@index');
    }
}