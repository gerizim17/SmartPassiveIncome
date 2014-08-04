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
            $estimatebest = Estimatebest::getByReId($realestate_id);
            $estimateworst = Estimateworst::getByReId($realestate_id);

            $rentaldetail = compact('rentaldetail');         
            $renttier = compact('renttier');
            $mortgage = compact('mortgage'); 
            $roi = compact('roi');             
            $fixedexpense = compact('fixedexpense');            
            $estimate = compact('estimate');
            $estimatebest = compact('estimatebest');
            $estimateworst = compact('estimateworst');
        
            return View::make('montecarloIndex')
                ->with($realestates)
                ->with($rentaldetail)
                ->with($renttier)
                ->with($mortgage)
                ->with($fixedexpense)
                ->with($roi)
                ->with($estimate)
                ->with($estimatebest)
                ->with($estimateworst)
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
        Montecarlo::calculateEstimate($realestate_id, $scenarios);        
        return Redirect::action('MontecarloController@index', $realestate_id);        
    }

    public function handlePropertyInfoSave(){
        $realestates = Realestate::getByUser(Auth::user()->id);
        $realestates = compact('realestates');

        // Handle edit form submission.  
        $realestate_id = Input::get('realestate_id');        

        $rentaldetail = Rentaldetail::getByReId($realestate_id);
        if(!isset($rentaldetail)){
            $rentaldetail = new Rentaldetail; 
            $rentaldetail->realestate_id = $realestate_id;
        }
        $rentaldetail->months_min = SmartPassiveIncome::defaultIfBlank(Input::get('months_min'), "12");
        $rentaldetail->months_max = SmartPassiveIncome::defaultIfBlank(Input::get('months_max'), "12");
        $rentaldetail->repair_min = SmartPassiveIncome::defaultIfBlank(Input::get('repair_min'), "0.00");
        $rentaldetail->repair_max = SmartPassiveIncome::defaultIfBlank(Input::get('repair_max'), "0.00");
        $rentaldetail->pm_monthly_charge = SmartPassiveIncome::defaultIfBlank(Input::get('pm_monthly_charge'), "0.00");
        $rentaldetail->pm_vacancy_charge = SmartPassiveIncome::defaultIfBlank(Input::get('pm_vacancy_charge'), "0.00");
        $rentaldetail->save();        
                       
        $renttier = Renttier::getByReId($realestate_id);
        if(!isset($renttier)){ 
            $renttier = new Renttier;
            $renttier->realestate_id = $realestate_id;
        }
        $renttier->units = Input::get('units');
        $renttier->rent = Input::get('rent');        
        $renttier->save();

        $mortgage = Mortgage::getByReId($realestate_id);
        
        $mortgage->sale_price = SmartPassiveIncome::defaultIfBlank(Input::get('sale_price'), "0.00");
        $mortgage->interest_rate = SmartPassiveIncome::defaultIfBlank(Input::get('interest_rate'), "0.00");
        $mortgage->percent_down = SmartPassiveIncome::defaultIfBlank(Input::get('term'), "0.00");
        $mortgage->term = SmartPassiveIncome::defaultIfBlank(Input::get('term'), "0.00");
        $mortgage->term2 = SmartPassiveIncome::defaultIfBlank(Input::get('term2'), "0.00");
        $mortgage->calculator = SmartPassiveIncome::defaultIfBlank(Input::get('calculator'), "0.00");

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
        $fixedexpense->taxes = SmartPassiveIncome::defaultIfBlank(Input::get('taxes'), "0.00");
        $fixedexpense->insurance = SmartPassiveIncome::defaultIfBlank(Input::get('insurance'), "0.00");
        $fixedexpense->utilities = SmartPassiveIncome::defaultIfBlank(Input::get('utilities'), "0.00");
        $fixedexpense->misc = SmartPassiveIncome::defaultIfBlank(Input::get('misc'), "0.00");
        $fixedexpense->save();

        $roi = Returnoninvestment::getByReId($realestate_id);
        if(!isset($roi)){
            $roi = new Returnoninvestment;
            $roi->realestate_id = $realestate_id;
        }


        $roi->down_payment = SmartPassiveIncome::defaultIfBlank(Input::get('down_payment'), "0.00");
        $roi->closing_costs = SmartPassiveIncome::defaultIfBlank(Input::get('closing_costs'), "0.00");
        $roi->misc_expenses = SmartPassiveIncome::defaultIfBlank(Input::get('misc_expenses'), "0.00");
        $roi->init_investment = SmartPassiveIncome::defaultIfBlank(Input::get('init_investment'), "0.00");
        $roi->save();

        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');

        return Redirect::action('MontecarloController@calculateEstimate', array($realestate_id, '1001'));

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
        $estimatebest = Estimatebest::getByReId($realestate_id);
        $estimateworst = Estimateworst::getByReId($realestate_id);  

        $realestates = compact('realestates');
        $rentaldetail = compact('rentaldetail');
        $renttier = compact('renttier');
        $mortgage = compact('mortgage');
        $fixedexpense = compact('fixedexpense');
        $roi = compact('roi');
        $estimate = compact('estimate');
        $estimatebest = compact('estimatebest');
        $estimateworst = compact('estimateworst');

        return View::make('montecarloIndex')            
            ->with($realestates)
            ->with($rentaldetail)
            ->with($renttier)
            ->with($mortgage)
            ->with($fixedexpense)
            ->with($roi)
            ->with($estimate)
            ->with($estimatebest)
            ->with($estimateworst)     
            ->with('realestate_id', $realestate_id); 
    }

    public function deleteRealEstate($realestate_id){

        //$rentaldetails = Rentaldetail::getByReId($realestate_id);
        //$realestates->delete();

        //return View::make('montecarloIndex');
        return $rentaldetails." ".$realestate_id;
    }
}