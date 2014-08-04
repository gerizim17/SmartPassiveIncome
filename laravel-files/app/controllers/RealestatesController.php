<?php

class RealestatesController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter('auth');
    }
    
    public function index()
    {
        $realestates = Realestate::getByUser(Auth::user()->id); 
        $mortgages = Mortgage::all(); 
        $realestates = compact('realestates');
        $mortgages = compact('mortgages');

        return View::make('realEstateIndex')
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

        $validator = Realestate::validate(Input::all());
        $validator2 = Mortgage::validate(Input::all());
        if($validator->fails() || $validator2->fails()){
            return Redirect::action('RealestatesController@create')->withInput()
                ->withErrors(array_merge_recursive(
                    $validator->messages()->toArray(), 
                    $validator2->messages()->toArray()
                ));
        }
        
        //error_log("validators: ".$validator->messages()->first());

        $realestate->address1 = Input::get('address1');
        $realestate->address2 = Input::get('address2');
        $realestate->city = Input::get('city');
        $realestate->state = Input::get('state');
        $realestate->zip  = Input::get('zip');
        $realestate->user_id  = Auth::user()->id;
        $realestate->save();
        
        $mortgage->realestate_id = $realestate->id;
        $mortgage->sale_price = Input::get('sale_price');
        $mortgage->monthly_payment = 0;
        $mortgage->save();
        return Redirect::action('RealestatesController@index');
    }

    public function edit($id)
    {   
        // Show the edit real estate form.    
        $realestate = Realestate::find($id);
        $mortgage = Mortgage::getByReId($id);
        $realestate = compact('realestate');
        $mortgage = compact('mortgage');
        return View::make('realEstateEdit')
            ->with($realestate)
            ->with($mortgage);
    }

    public function handleEdit()
    {
        // Handle edit form submission.
        $validator = Realestate::validate(Input::all());
        if($validator){
            error_log("true");
        } else {
            error_log("messages: ".Realestate::$validationMessages);
            error_log("false");
        }
        error_log("validators: ".$validator);

        $realestate = Realestate::findOrFail(Input::get('realestate_id'));
        $realestate->address1 = Input::get('address1');
        $realestate->address2 = Input::get('address2');
        $realestate->city = Input::get('city');
        $realestate->state = Input::get('state');
        $realestate->zip  = Input::get('zip'); 

        $realestate->save();
        $mortgage = Mortgage::getByReId(Input::get('realestate_id'));
        $mortgage->sale_price = Input::get('sale_price');        
        $mortgage->save();

        return Redirect::action('RealestatesController@index');
    }


    public function handleDelete($id)
    {
        // Handle the delete confirmation.       
        $estimate = Estimate::getByReId($id);
        $estimatebest = Estimatebest::getByReId($id);    
        $estimateworst = Estimateworst::getByReId($id);
        $fixedexpense = Fixedexpense::getByReId($id);
        $mortgage = Mortgage::getByReId($id);
        $rentaldetail = Rentaldetail::getByReId($id);
        $rentalhistory = Rentalhistory::getByReId($id);
        $renttier = Renttier::getByReId($id);
        $returnoninvestment = Returnoninvestment::getByReId($id);
        $realestate = Realestate::findOrFail($id);

        if(!isset($estimate)){
            $estimate->delete();
            $estimatebest->delete();
            $estimateworst->delete();
            $fixedexpense->delete();
            $rentaldetail->delete();
            $renttier->delete();
            $returnoninvestment->delete();
        }
       
        if(isset($rentalhistory) && !$rentalhistory->isEmpty()){
            $rentalhistory->delete();
        }
        $mortgage->delete();
        $realestate->delete();

        return Redirect::action('RealestatesController@index');
    }
}