<?php

// app/controllers/ArticleController.php

class MontecarloController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function index($realestate_id = null)
    {
        $realestates = Realestate::getByUser(Auth::user()->id);
        $realestates = compact('realestates');

        if($realestate_id != null){
            $rentaldetail = Rentaldetail::getByReId($realestate_id);
            $renttier = Renttier::getByReId($realestate_id);
            $mortgage = Mortgage::getByReId($realestate_id);
            $fixedexpense = Fixedexpense::getByReId($realestate_id); 
            $roi = Returnoninvestment::getByReId($realestate_id);                    
            $estimate = Estimate::getByReId($realestate_id);

            if(isset($estimate)){
                $vacancy_percent = number_format(100-($estimate->rent/$renttier->rent)*100)."%";
                $ary_account_income = array(
                    trans("general.gpi") => $renttier->rent,
                    trans("general.vac", array("vacancy" => $vacancy_percent)) => ($renttier->rent - $estimate->rent),
                    trans("general.egi") => $estimate->rent,
                    trans("general.oi") => 0,
                    trans("general.goi") => $estimate->rent
                );

                $ary_account_expenses = array(
                    "general.propertytaxes" => $fixedexpense->taxes,
                    "general.insurance" => $fixedexpense->insurance,
                    "general.utilities" => $fixedexpense->utilities,
                    "general.otherexpenses" => $fixedexpense->misc,
                    "general.repairs" => $estimate->repairs,
                    "general.propertymanagement" => $estimate->variable_expenses-$estimate->repairs
                ); 

                $ary_yearly_account_income = array(
                    trans("general.gpi") => $renttier->rent*12,
                    trans("general.vac", array("vacancy" => $vacancy_percent)) => (($renttier->rent*12) - ($estimate->rent*12)),
                    trans("general.egi") => ($estimate->rent*12),
                    trans("general.oi") => 0,
                    trans("general.goi") => ($estimate->rent*12)
                );

                $ary_yearly_account_expenses = array(
                    "general.propertytaxes" => $fixedexpense->taxes*12,
                    "general.insurance" => $fixedexpense->insurance*12,
                    "general.utilities" => $fixedexpense->utilities*12,
                    "general.otherexpenses" => $fixedexpense->misc*12,
                    "general.repairs" => $estimate->repairs*12,
                    "general.propertymanagement" => ($estimate->variable_expenses*12)-($estimate->repairs)*12
                );  
            }
            $rentaldetail = compact('rentaldetail');         
            $renttier = compact('renttier');
            $mortgage = compact('mortgage'); 
            $roi = compact('roi');             
            $fixedexpense = compact('fixedexpense');            
            $estimate = compact('estimate');
        
            return View::make('montecarloIndex')
                ->with($realestates)
                ->with($rentaldetail)
                ->with($renttier)
                ->with($mortgage)
                ->with($fixedexpense)
                ->with($roi)
                ->with($estimate)
                ->with('ary_account_income', @$ary_account_income)
                ->with('ary_account_expenses', @$ary_account_expenses)
                ->with('ary_yearly_account_income', @$ary_yearly_account_income)
                ->with('ary_yearly_account_expenses', @$ary_yearly_account_expenses)
                ->with('realestate_id', $realestate_id);
        } else {
            return View::make('montecarloIndex')->with($realestates);
        }
        
    }

     public function newMontecarloEstimate()
    {
        $realestates = Realestate::getByUser(Auth::user()->id);
        $realestates = compact('realestates');
        
        return View::make('montecarloIndex')->with($realestates);
    }

    public function handleCreate($realestate_id){
               
    }

    public function calculateEstimate($realestate_id, $scenarios){
        error_log("estimating montecarlo...");
        Montecarlo::calculateEstimate($realestate_id, $scenarios);
        error_log("...estimates finished");

        return Redirect::action('MontecarloController@index', $realestate_id);        
    }

    public function handlePropertyInfoSave(){
        $realestates = Realestate::getByUser(Auth::user()->id);
        $realestates = compact('realestates');

        error_log("saving property info...");

        // Handle edit form submission.  
        $realestate_id = Input::get('realestate_id');        

        $rentaldetail = Rentaldetail::getByReId($realestate_id);
        if(!isset($rentaldetail)){ 
            $rentaldetail = new Rentaldetail; 
            $rentaldetail->realestate_id = $realestate_id;
        }
        $rentaldetail->months_min = Input::get('months_min');
        $rentaldetail->months_max = Input::get('months_max');
        $rentaldetail->repair_min = Input::get('repair_min');
        $rentaldetail->repair_max = Input::get('repair_max');
        $rentaldetail->pm_monthly_charge = Input::get('pm_monthly_charge');
        $rentaldetail->pm_vacancy_charge = Input::get('pm_vacancy_charge');
        $rentaldetail->save();        
                       
        $renttier = renttier::getByReId($realestate_id);
        if(!isset($renttier)){ 
            $renttier = new Renttier;
            $renttier->realestate_id = $realestate_id;
        }
        $renttier->units = Input::get('units');
        $renttier->rent = Input::get('rent');        
        $renttier->save();

        $mortgage = Mortgage::getByReId($realestate_id);
        
        $mortgage->sale_price = Input::get('sale_price');
        $mortgage->interest_rate = Input::get('interest_rate');
        $mortgage->percent_down = Input::get('percent_down');        
        $mortgage->term = Input::get('term');
        $mortgage->term2 = Input::get('term2');
        $mortgage->calculator = Input::get('calculator');

        if(isset($mortgage->calculator)){
            $mortgage->monthly_payment = SmartPassiveIncome::calculateMortgage($mortgage->percent_down, $mortgage->sale_price, $mortgage->interest_rate, $mortgage->term);
            $mortgage->monthly_payment2 = SmartPassiveIncome::calculateMortgage($mortgage->percent_down, $mortgage->sale_price, $mortgage->interest_rate, $mortgage->term2);
            if($mortgage->percent_down < 20){
                $down_payment = $mortgage->sale_price * ($mortgage->percent_down / 100);
                $financing_amount = $mortgage->sale_price - $down_payment;
                $mortgage->pmi = SmartPassiveIncome::calculatePMIPerMonth($financing_amount);
                $mortgage->pmi2 = SmartPassiveIncome::calculatePMIPerMonth($financing_amount);;
            } else {
                $mortgage->pmi = 0;
                $mortgage->pmi2 = 0;
            }
        } else {
            $mortgage->calculator = 0;
            $mortgage->monthly_payment = Input::get('monthly_payment');
            $mortgage->pmi = Input::get('pmi');
        }        
        
        $mortgage->save();

        $fixedexpense = Fixedexpense::getByReId($realestate_id);
        if(!isset($fixedexpense)){
            $fixedexpense = new Fixedexpense;
            $fixedexpense->realestate_id = $realestate_id;
        }
        $fixedexpense->taxes = Input::get('taxes');
        $fixedexpense->insurance = Input::get('insurance');
        $fixedexpense->utilities = Input::get('utilities');
        $fixedexpense->misc = Input::get('misc');
        $fixedexpense->save();

        $roi = Returnoninvestment::getByReId($realestate_id);
        if(!isset($roi)){
            $roi = new Returnoninvestment;
            $roi->realestate_id = $realestate_id;
        }
        $roi->down_payment = Input::get('down_payment');
        $roi->closing_costs = Input::get('closing_costs');
        $roi->misc_expenses = Input::get('misc_expenses');
        $roi->init_investment = Input::get('init_investment');
        $roi->save();

        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');

        error_log("...information saved");
        return Redirect::action('MontecarloController@calculateEstimate', array($realestate_id, '10000'));

    }

    public function handleSelectRealestate(){
        $realestate_id = Input::get('realestate_dropdown');
        $realestates = Realestate::getByUser(Auth::user()->id);
        $rentaldetail = Rentaldetail::getByReId($realestate_id);
        $renttier = Renttier::getByReId($realestate_id);
        $mortgage = Mortgage::getByReId($realestate_id);
        $fixedexpense = Fixedexpense::getByReId($realestate_id);
        $roi = Returnoninvestment::getByReId($realestate_id);
        $estimate = Estimate::getByReId($realestate_id);

        $vacancy_percent = number_format(100-($estimate->rent/$renttier->rent)*100)."%";
        $ary_yearly_account_income = array(
            trans("general.gpi") => $renttier->rent*12,
            trans("general.vac", array("vacancy" => $vacancy_percent)) => (($renttier->rent*12) - ($estimate->rent*12)),
            trans("general.egi") => ($estimate->rent*12),
            trans("general.oi") => 0,
            trans("general.goi") => ($estimate->rent*12)
        );

        $ary_yearly_account_expenses = array(
            "general.propertytaxes" => $fixedexpense->taxes*12,
            "general.insurance" => $fixedexpense->insurance*12,
            "general.utilities" => $fixedexpense->utilities*12,
            "general.otherexpenses" => $fixedexpense->misc*12,
            "general.repairs" => $estimate->repairs*12,
            "general.propertymanagement" => ($estimate->variable_expenses*12)-($estimate->repairs)*12
        );  

        $ary_account_income = array(
            trans("general.gpi") => $renttier->rent,
            trans("general.vac", array("vacancy" => $vacancy_percent)) => ($renttier->rent - $estimate->rent),
            trans("general.egi") => $estimate->rent,
            trans("general.oi") => 0,
            trans("general.goi") => $estimate->rent
        );

        $ary_account_expenses = array(
            "general.propertytaxes" => $fixedexpense->taxes,
            "general.insurance" => $fixedexpense->insurance,
            "general.utilities" => $fixedexpense->utilities,
            "general.otherexpenses" => $fixedexpense->misc,
            "general.repairs" => $estimate->repairs,
            "general.propertymanagement" => $estimate->variable_expenses-$estimate->repairs
        );      

        $realestates = compact('realestates');
        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');
        $estimate = compact('estimate');

        return View::make('montecarloIndex')            
            ->with($realestates)
            ->with($rentaldetail)
            ->with($renttier)
            ->with($mortgage)
            ->with($fixedexpense)
            ->with($roi)
            ->with($estimate)
            ->with('ary_account_income', $ary_account_income)
            ->with('ary_account_expenses', $ary_account_expenses)
            ->with('ary_yearly_account_income', $ary_yearly_account_income)
            ->with('ary_yearly_account_expenses', $ary_yearly_account_expenses)            
            ->with('realestate_id', $realestate_id); 
    }

    public function deleteRealEstate($realestate_id){

        //$rentaldetails = Rentaldetail::getByReId($realestate_id);
        //$realestates->delete();

        //return View::make('montecarloIndex');
        return $rentaldetails." ".$realestate_id;
    }
}