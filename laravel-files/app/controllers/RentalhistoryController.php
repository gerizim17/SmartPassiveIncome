<?php

class RentalhistoryController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function index($realestate_id = null)
    {        
        // Show a listing of real estate.      
        $realestates = Realestate::getByUser(Auth::user()->id);
        $realestates = compact('realestates');
        return View::make('rentalHistoryIndex')
            ->with($realestates);
    }

    public function create($realestate_id, $rentalhistory_id = null) {

        if($rentalhistory_id != null){
            $rentalhistory = Rentalhistory::find($rentalhistory_id);
        } else {
            $rentalhistory = Rentalhistory::getByReId($realestate_id)->last();
        }

        $rentalhistory = compact('rentalhistory');
        // Show the create real estate form.    
        return View::make('rentalHistoryCreate')
            ->with($rentalhistory)
            ->with('rentalhistory_id', $rentalhistory_id)
            ->with('realestate_id', $realestate_id);
    }

    public function handleCreate()
    {        
        $ary_start_date = explode("/", Input::get('date'));
        $formatted_start_date = $ary_start_date[1].'/'.$ary_start_date[0].'/01';
        
        if(Input::has('rentalhistory_id')){            
            $rentalhistory = Rentalhistory::findOrFail(Input::get('rentalhistory_id'));
        } else {
            $rentalhistory = new Rentalhistory;
        }

        $rentalhistory->realestate_id = Input::get('realestate_id');
        $rentalhistory->date = $formatted_start_date;
        $rentalhistory->rent = Input::get('rent');
        $rentalhistory->mortgage = Input::get('mortgage');
        $rentalhistory->property_management = Input::get('property_management');
        $rentalhistory->tax = Input::get('tax');
        $rentalhistory->insurance = Input::get('insurance');
        $rentalhistory->electricity = Input::get('electricity');
        $rentalhistory->water = Input::get('water');        
        $rentalhistory->repairs = Input::get('repairs');
        
        $cashflow = $rentalhistory->rent-$rentalhistory->repairs-$rentalhistory->mortgage - $rentalhistory->property_management - $rentalhistory->tax - $rentalhistory->insurance - $rentalhistory->electricity - $rentalhistory->water;
        $rentalhistory->cashflow = $cashflow;
        $rentalhistory->save();

        $message = urlencode('<div class="alert alert-success">Rental history succesfully <strong>saved</strong>.</div>');
        return Redirect::action('RentalhistoryController@handleSelectRealestate', array($rentalhistory->realestate_id, $message));        
    }

    public function edit($realestate_id)
    {   
        // Show the edit real estate form.    
        $realestate = Realestate::find($realestate_id);
        $mortgage = Mortgage::getByReId($realestate_id);
        $realestate = compact('realestate');
        $mortgage = compact('mortgage');
        return View::make('rentalHistoryEdit')
            ->with($realestate)
            ->with($mortgage);
    }

    public function handleDelete($rentalhistory_id)
    {
        $rentalhistory = Rentalhistory::findOrFail($rentalhistory_id);
        $realestate_id = $rentalhistory->realestate_id;
        $rentalhistory->delete();
        $message = urlencode('<div class="alert alert-success">Rental history succesfully <strong>deleted</strong>.</div>');
        return Redirect::action('RentalhistoryController@handleSelectRealestate', array($realestate_id, $message));
    }

    public function handleSelectRealestate($realestate_id = null, $message = null){
        if($realestate_id == null){
            $realestate_id = Input::get('realestate_dropdown');
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
        $realestates = Realestate::getByUser(Auth::user()->id);
        $rentalhistories = Rentalhistory::getByReIdBetweenDates($realestate_id, $formatted_start_date, $formatted_end_date);
        $estimate = Estimate::getByReId($realestate_id);            
        $renttier = Renttier::getByReId($realestate_id);
        error_log("realestate_id: ".$realestate_id);

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
            $total_cashflow += $rentalhistory->cashflow;
            $total_rent += $rentalhistory->rent;
            $total_repairs += $rentalhistory->repairs;
            $total_water += $rentalhistory->water;
            $total_electricity += $rentalhistory->electricity;
            $total_tax += $rentalhistory->tax;
            $total_insurance += $rentalhistory->insurance;
            $total_mortgage += $rentalhistory->mortgage;
            $total_property_management += $rentalhistory->property_management;
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

        $vacancy_percent = number_format(100-($average_rent/$renttier->rent)*100)."%";
        $ary_account_income = array(
            trans("general.gpi") => $renttier->rent,
            trans("general.vac", array("vacancy" => $vacancy_percent)) => ($renttier->rent - $total_rent/$count),
            trans("general.egi") => $average_rent,
            trans("general.oi") => 0,
            trans("general.goi") => $average_rent
        );

        $ary_account_expenses = array(
            "general.propertytaxes" => $average_tax,
            "general.insurance" => $average_insurance,
            "general.utilities" => $average_water + $average_electricity,
            "general.otherexpenses" => 0,
            "general.repairs" => $average_repairs,
            "general.propertymanagement" => $average_property_management
        ); 
        
        $ary_annual_account_income = array(
            trans("general.gpi") => $renttier->rent*12,
            trans("general.vac", array("vacancy" => $vacancy_percent)) => ($renttier->rent - $total_rent),
            trans("general.egi") => $total_rent,
            trans("general.oi") => 0,
            trans("general.goi") => $total_rent
        );

        $ary_annual_account_expenses = array(
            "general.propertytaxes" => $total_tax,
            "general.insurance" => $total_insurance,
            "general.utilities" => $total_water + $total_electricity,
            "general.otherexpenses" => 0,
            "general.repairs" => $total_repairs,
            "general.propertymanagement" => $total_property_management
        ); 

        return View::make('rentalHistoryIndex')            
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
            ->with('ary_account_income', $ary_account_income)
            ->with('ary_account_expenses', $ary_account_expenses)
            ->with('ary_annual_account_income', $ary_annual_account_income)
            ->with('ary_annual_account_expenses', $ary_annual_account_expenses)
            ->with('debt_service', $total_mortgage)
            ->with('realestate_id', $realestate_id); 
    }
}