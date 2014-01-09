<?php

class RentalhistoryController extends BaseController
{
    public function index($re_id = null)
    {        
        // Show a listing of real estate.      
        $realestates = Realestate::all();  
        $realestates = compact('realestates');
        return View::make('rentalHistoryIndex')
            ->with($realestates);      
    }

    public function create($re_id, $rh_id = null) {

        if($rh_id != null){
            $rentalhistory = Rentalhistory::find($rh_id);
        } else {
            $rentalhistory = Rentalhistory::getByReId($re_id)->last();
        }

        $rentalhistory = compact('rentalhistory');
        // Show the create real estate form.    
        return View::make('rentalHistoryCreate')
            ->with($rentalhistory)
            ->with('rh_id', $rh_id)
            ->with('re_id', $re_id);
    }

    public function handleCreate()
    {        
        $ary_start_date = explode("/", Input::get('rh_date'));
        $formatted_start_date = $ary_start_date[1].'/'.$ary_start_date[0].'/01';
        
        if(Input::has('rh_id')){            
            $rentalhistory = Rentalhistory::findOrFail(Input::get('rh_id'));
        } else {
            $rentalhistory = new Rentalhistory;
        }

        $rentalhistory->rh_re_id = Input::get('re_id');
        $rentalhistory->rh_date = $formatted_start_date;
        $rentalhistory->rh_rent = Input::get('rh_rent');
        $rentalhistory->rh_mortgage = Input::get('rh_mortgage');
        $rentalhistory->rh_property_management = Input::get('rh_property_management');
        $rentalhistory->rh_tax = Input::get('rh_tax');
        $rentalhistory->rh_insurance = Input::get('rh_insurance');
        $rentalhistory->rh_electricity = Input::get('rh_electricity');
        $rentalhistory->rh_water = Input::get('rh_water');        
        $rentalhistory->rh_repairs = Input::get('rh_repairs');
        
        $cashflow = $rentalhistory->rh_rent-$rentalhistory->rh_repairs-$rentalhistory->rh_mortgage - $rentalhistory->rh_property_management - $rentalhistory->rh_tax - $rentalhistory->rh_insurance - $rentalhistory->rh_electricity - $rentalhistory->rh_water;
        $rentalhistory->rh_cashflow = $cashflow;
        $rentalhistory->save();

        $message = urlencode('<div class="alert alert-success">Rental history succesfully <strong>saved</strong>.</div>');
        return Redirect::action('RentalhistoryController@handleSelectRealestate', array($rentalhistory->rh_re_id, $message));        
    }

    public function edit($re_id)
    {   
        // Show the edit real estate form.    
        $realestate = Realestate::find($re_id);
        $mortgage = Mortgage::getByReId($re_id);
        $realestate = compact('realestate');
        $mortgage = compact('mortgage');
        return View::make('rentalHistoryEdit')
            ->with($realestate)
            ->with($mortgage);
    }

    public function handleDelete($rh_id)
    {
        $rentalhistory = Rentalhistory::findOrFail($rh_id);
        $re_id = $rentalhistory->rh_re_id;
        $rentalhistory->delete();
        $message = urlencode('<div class="alert alert-success">Rental history succesfully <strong>deleted</strong>.</div>');
        return Redirect::action('RentalhistoryController@handleSelectRealestate', array($re_id, $message));
    }

    public function handleSelectRealestate($re_id = null, $message = null){
        if($re_id == null){
            $re_id = Input::get('re_dropdown');
            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            if($start_date == ""){
                $formatted_start_date = date('Y').'/01/01';
                $formatted_end_date = date('Y').'/12/31';
            }else{
                $ary_start_date = explode("/", $start_date);
                $ary_end_date = explode("/", $end_date);
                $formatted_start_date = $ary_start_date[1].'/'.$ary_start_date[0].'/01';
                $formatted_end_date = $ary_end_date[1].'/'.$ary_end_date[0].'/31';
            }
        } else {
            $start_date = '01/'.date('Y');
            $end_date = '12/'.date('Y');
            $formatted_start_date = date('Y').'/01/01';
            $formatted_end_date = date('Y').'/12/31';
        }       
        $realestates = Realestate::all();        
        $rentalhistories = Rentalhistory::getByReIdBetweenDates($re_id, $formatted_start_date, $formatted_end_date);
        $estimate = Estimate::getByReId($re_id);            

        $total_cashflow = 0;
        $total_rent = 0;
        $total_repairs = 0;
        $total_water = 0;
        $total_electricity = 0;
        $total_tax = 0;
        $total_insurance = 0;
        $total_mortgage = 0;
        $total_property_management = 0;

        $count = 0;

        foreach ($rentalhistories as $rentalhistory) {
            $total_cashflow += $rentalhistory->rh_cashflow;
            $total_rent += $rentalhistory->rh_rent;
            $total_repairs += $rentalhistory->rh_repairs;
            $total_water += $rentalhistory->rh_water;
            $total_electricity += $rentalhistory->rh_electricity;
            $total_tax += $rentalhistory->rh_tax;
            $total_insurance += $rentalhistory->rh_insurance;
            $total_mortgage += $rentalhistory->rh_mortgage;
            $total_property_management += $rentalhistory->rh_property_management;
            $count++;
        }
        if($rentalhistories->isEmpty()){
            $count = 1;
        }
        
        $average_cashflow = number_format($total_cashflow/$count);
        $average_rent = number_format($total_rent/$count);
        $average_repairs = number_format($total_repairs/$count);
        $average_water = number_format($total_water/$count);
        $average_electricity = number_format($total_electricity/$count);
        $average_tax = number_format($total_tax/$count);
        $average_insurance = number_format($total_insurance/$count);
        $average_mortgage = number_format($total_mortgage/$count);
        $average_property_management = number_format($total_property_management/$count);
        
        $realestates = compact('realestates');
        $estimate = compact('estimate');
        $rentalhistories = compact('rentalhistories');

        return View::make('rentalhistoryIndex')            
            ->with($realestates)           
            ->with($rentalhistories)
            ->with($estimate)
            ->with('total_cashflow', $total_cashflow)
            ->with('total_rent', $total_rent)
            ->with('total_repairs', $total_repairs)
            ->with('total_water', $total_water)
            ->with('total_electricity', $total_electricity)
            ->with('total_tax', $total_tax)
            ->with('total_insurance', $total_insurance)
            ->with('total_mortgage', $total_mortgage)
            ->with('total_property_management', $total_property_management)
            ->with('average_cashflow', $average_cashflow)
            ->with('average_rent', $average_rent)
            ->with('average_repairs', $average_repairs)
            ->with('average_water', $average_water)
            ->with('average_electricity', $average_electricity)
            ->with('average_tax', $average_tax)
            ->with('average_insurance', $average_insurance)
            ->with('average_mortgage', $average_mortgage)
            ->with('average_property_management', $average_property_management)
            ->with('start_date', $start_date)
            ->with('end_date', $end_date)
            ->with('message', $message)
            ->with('re_id', $re_id); 
    }
}