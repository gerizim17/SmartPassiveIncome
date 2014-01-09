<?php

// app/controllers/ArticleController.php

class MontecarloController extends BaseController
{

    public function index($re_id = null)
    {
        $realestates = Realestate::all();
        $realestates = compact('realestates');

        if($re_id != null){
            $rentaldetail = Rentaldetail::getByReId($re_id);
            $renttier = Renttier::getByReId($re_id);
            $mortgage = Mortgage::getByReId($re_id);
            $fixedexpense = Fixedexpense::getByReId($re_id); 
            $roi = Returnoninvestment::getByReId($re_id);                    
            $rentaldetail = compact('rentaldetail');         
            $renttier = compact('renttier');
            $mortgage = compact('mortgage'); 
            $roi = compact('roi'); 
            $fixedexpense = compact('fixedexpense'); 

            return View::make('montecarloIndex')
                ->with($realestates)
                ->with($rentaldetail)
                ->with($renttier)
                ->with($mortgage)
                ->with($fixedexpense)
                ->with($roi)
                ->with('re_id', $re_id);
        } else {
            return View::make('montecarloIndex')->with($realestates);
        }
        
    }

     public function newMontecarloEstimate()
    {
        $realestates = Realestate::all();
        $realestates = compact('realestates');
        
        return View::make('montecarloIndex')->with($realestates);
    }

    public function handleCreate($re_id){
               
    }

    public function calculateEstimate($re_id, $scenarios){
        error_log("estimating montecarlo...");
        Montecarlo::calculateEstimate($re_id, $scenarios);
        error_log("...estimates finished");

        return Redirect::action('MontecarloController@index', $re_id);        
    }

    public function handlePropertyInfoSave(){
        $realestates = Realestate::all();
        $realestates = compact('realestates');

        error_log("saveing property info...");

        // Handle edit form submission.  
        $re_id = Input::get('re_id');        

        $rentaldetail = Rentaldetail::getByReId($re_id);
        if(!isset($rentaldetail)){ 
            $rentaldetail = new Rentaldetail; 
            $rentaldetail->rd_re_id = $re_id;
        }
        $rentaldetail->rd_months_min = Input::get('rd_months_min');
        $rentaldetail->rd_months_max = Input::get('rd_months_max');
        $rentaldetail->rd_repair_min = Input::get('rd_repair_min');
        $rentaldetail->rd_repair_max = Input::get('rd_repair_max');
        $rentaldetail->rd_pm_monthly_charge = Input::get('rd_pm_monthly_charge');
        $rentaldetail->rd_pm_vacancy_charge = Input::get('rd_pm_vacancy_charge');
        $rentaldetail->save();        
                       
        $renttier = renttier::getByReId($re_id);
        if(!isset($renttier)){ 
            $renttier = new Renttier;
            $renttier->rt_re_id = $re_id;
        }
        $renttier->rt_units = Input::get('rt_units');
        $renttier->rt_rent = Input::get('rt_rent');        
        $renttier->save();

        $mortgage = Mortgage::getByReId($re_id);
        
        $mortgage->mg_sale_price = Input::get('mg_sale_price');
        $mortgage->mg_interest_rate = Input::get('mg_interest_rate');
        $mortgage->mg_percent_down = Input::get('mg_percent_down');        
        $mortgage->mg_term = Input::get('mg_term');
        $mortgage->mg_term2 = Input::get('mg_term2');
        $mortgage->mg_calculator = Input::get('mg_calculator');

        if(isset($mortgage->mg_calculator)){
            $mortgage->mg_monthly_payment = SmartPassiveIncome::calculateMortgage($mortgage->mg_percent_down, $mortgage->mg_sale_price, $mortgage->mg_interest_rate, $mortgage->mg_term);
            $mortgage->mg_monthly_payment2 = SmartPassiveIncome::calculateMortgage($mortgage->mg_percent_down, $mortgage->mg_sale_price, $mortgage->mg_interest_rate, $mortgage->mg_term2);
            if($mortgage->mg_percent_down < 20){
                $down_payment = $mortgage->mg_sale_price * ($mortgage->mg_percent_down / 100);
                $financing_amount = $mortgage->mg_sale_price - $down_payment;
                $mortgage->mg_pmi = SmartPassiveIncome::calculatePMIPerMonth($financing_amount);
                $mortgage->mg_pmi2 = SmartPassiveIncome::calculatePMIPerMonth($financing_amount);;
            } else {
                $mortgage->mg_pmi = 0;
                $mortgage->mg_pmi2 = 0;
            }
        } else {
            $mortgage->mg_calculator = 0;
            $mortgage->mg_monthly_payment = Input::get('mg_monthly_payment');
            $mortgage->mg_pmi = Input::get('mg_pmi');
        }
        
        $mortgage->save();

        $fixedexpense = Fixedexpense::getByReId($re_id);
        if(!isset($fixedexpense)){
            $fixedexpense = new Fixedexpense;
            $fixedexpense->fe_re_id = $re_id;
        }
        $fixedexpense->fe_taxes = Input::get('fe_taxes');
        $fixedexpense->fe_insurance = Input::get('fe_insurance');
        $fixedexpense->fe_utilities = Input::get('fe_utilities');
        $fixedexpense->fe_misc = Input::get('fe_misc');
        $fixedexpense->save();

        $roi = Returnoninvestment::getByReId($re_id);
        if(!isset($roi)){
            $roi = new Returnoninvestment;
            $roi->roi_re_id = $re_id;
        }
        $roi->roi_down_payment = Input::get('roi_down_payment');
        $roi->roi_closing_costs = Input::get('roi_closing_costs');
        $roi->roi_misc_expenses = Input::get('roi_misc_expenses');
        $roi->roi_init_investment = Input::get('roi_init_investment');
        $roi->save();

        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');

        error_log("...information saved");
        return Redirect::action('MontecarloController@calculateEstimate', array($re_id, '10000'));

    }

    public function handleSelectRealestate(){
        $re_id = Input::get('re_dropdown');
        $realestates = Realestate::all();        
        $rentaldetail = Rentaldetail::getByReId($re_id);
        $renttier = Renttier::getByReId($re_id);
        $mortgage = Mortgage::getByReId($re_id);
        $fixedexpense = Fixedexpense::getByReId($re_id);
        $roi = Returnoninvestment::getByReId($re_id);
        $realestates = compact('realestates');
        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');
        return View::make('montecarloIndex')            
            ->with($realestates)
            ->with($rentaldetail)
            ->with($renttier)
            ->with($mortgage)
            ->with($fixedexpense)
            ->with($roi)
            ->with('re_id', $re_id); 
    }

    public function deleteRealEstate($re_id){

        //$rentaldetails = Rentaldetail::getByReId($re_id);
        //$realestates->delete();

        //return View::make('montecarloIndex');
        return $rentaldetails." ".$re_id;
    }
}