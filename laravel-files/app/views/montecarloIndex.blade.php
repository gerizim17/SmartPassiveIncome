@extends('layouts.layout')

@section('content')
    <h1>Montecarlo Estimates!</h1>    
    @if ($realestates->isEmpty())        
        <p>There are no real estate! :(</p>
    @else        
    
    @if (isset($realestate_id) && isset($renttier))
        {{ Flowchart::drawColumnChart($realestate_id, "bar_div") }}
        {{ Flowchart::drawPieChart($realestate_id, "pie_div") }}
    @endif

    <form name="dropDownForm" id="dropDownForm" action="{{ action('MontecarloController@handleSelectRealestate') }}" method="post" role="form">
        <select class="form-control" name="realestate_dropdown" onchange="$('#dropDownForm').submit();">
            <option value="">{{ trans('general.selectproperty') }}</option>
        @foreach($realestates as $realestate)
            <option value="{{ $realestate->id }}" {{ @$realestate_id==$realestate->id?"selected":"" }}>{{ $realestate->address1.", ".$realestate->city." ".$realestate->state  }}</option>
        @endforeach
        </select> 
    </form> 
    @endif
    <br />
    <div class="form-group row">
        <div class="col-sm-6" id="pie_div"></div>
        <div class="col-sm-4" id="bar_div"></div>  
    </div>
    <div class="form-group row">
        @if (isset($realestate_id) && isset($renttier))
            <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $ary_account_income, $ary_account_expenses, $mortgage->monthly_payment) }} </div>
            <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $ary_yearly_account_income, $ary_yearly_account_expenses, $mortgage->monthly_payment*12, "Yearly Income Statement") }} </div>
        @endif
    </div>

    <div id="montecarloForm" {{ isset($realestate_id)?"":'style="display:none"' }}>
       
        <form name="rentTierForm" action="{{ url('montecarlo/propertyinfosave') }}" method="post" role="form">            
            <!-- <div class="form-group">
                <div class="col-sm-offset-3">
                    <input type="submit" value="Save" class="btn btn-primary" />
                    <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">Cancel</a>
                </div>
            </div> -->
            <input type="hidden" name="rentaldetail_id" value="{{ @$rentaldetail->id }}">
            <input type="hidden" name="realestate_id" value="{{ @$realestate_id }}">                    
            <div class="row"><h3>{{ trans('general.revenue') }}</h3></div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="rent" class="control-label">{{ trans('general.rent') }}</label></div>                
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="rent" value="{{ @$renttier->rent }}" />
                    </div>
                </div>
                <div class="col-sm-2"><label for="units" class="control-label">{{ trans('general.units') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="units" value="{{ @$renttier->units }}" />
                    </div>
                </div>
            </div>      

            <div class="form-group row">
                <div class="col-sm-2"><label for="months_min" class="control-label">{{ trans('general.rentmin') }}</label></div>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <input type="text" class="form-control input-small" name="months_min" value="{{ @$rentaldetail->months_min }}" />
                        </div>
                    </div>          
                <div class="col-sm-1"></div>
                <div class="col-sm-2"><label for="months_max" class="control-label">{{ trans('general.rentmax') }}</label></div>
                <div class="col-sm-1">
                        <div class="input-group"><input type="text" class="form-control" name="months_max" value="{{ @$rentaldetail->months_max }}" /></div>
                </div>
            </div>

            <div class="row"><h3>{{ trans('general.varexpenses') }}<h3></div>

            <div class="form-group row">
                <div class="col-sm-2"><label for="repair_min" class="control-label">{{ trans('general.repairsmin') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="repair_min" value="{{ @$rentaldetail->repair_min }}" />             
                    </div>
                </div>            
                <div class="col-sm-2"><label for="repair_max" class="control-label">{{ trans('general.repairsmax') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="repair_max" value="{{ @$rentaldetail->repair_max }}" />             
                    </div>
                </div>
            </div>        
            
             <div class="form-group row">
                <div class="col-sm-2"><label for="pm_monthly_charge" class="control-label">{{ trans('general.propertymanagement') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="pm_monthly_charge" value="{{ @$rentaldetail->pm_monthly_charge }}" />
                        <span class="input-group-addon">%</span>
                    </div>
                </div>            
                <div class="col-sm-2"><label for="pm_vacancy_charge" class="control-label">{{ trans('general.pmvacrate') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="pm_vacancy_charge" value="{{ @$rentaldetail->pm_vacancy_charge }}" />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="calculator" class="control-label">{{ trans('general.mortgagecalculator') }}
                </div>
                <div class="col-sm-2">
                    <input type="checkbox" {{ @$mortgage->calculator=="1"?"checked":"" }} value="1" name="calculator">
                </div>
            </div>
            <div id="calculator_div" {{ @$mortgage->calculator=="1"?"":"style='display:none'" }}>
                <div class="row"><h3>{{ trans('general.mortgage') }}<h3></div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="sale_price" class="control-label">{{ trans('general.saleprice') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" name="sale_price" value="{{ @$mortgage->sale_price }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="interest_rate" class="control-label">{{ trans('general.interestrate') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" name="interest_rate" value="{{ @$mortgage->interest_rate }}" />             
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="percent_down" class="control-label">{{ trans('general.percentdown') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" onblur="updateDownPayment()" name="percent_down" value="{{ @$mortgage->percent_down }}" />             
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>        
                 <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="term" class="control-label">{{ trans('general.mortgageterm') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="term" value="{{ @$mortgage->term }}" />
                            <span class="input-group-addon">{{ trans('general.years') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="term2" class="control-label">{{ trans('general.comparewith') }}</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" name="term2" value="{{ @$mortgage->term2 }}" />
                            <span class="input-group-addon">{{ trans('general.years') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"><h3>{{ trans('general.fixedexpenses') }}<h3></div> 
                     
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="monthly_payment" class="control-label">{{ trans('general.monthlypayment') }}</label>            
                </div>
                
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="monthly_payment" value="{{ @$mortgage->monthly_payment }}" />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2"><label for="taxes" class="control-label">{{ trans('general.taxes') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="taxes" value="{{ @$fixedexpense->taxes }}" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="insurance" class="control-label">{{ trans('general.insurance') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="insurance" value="{{ @$fixedexpense->insurance }}" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="utilities" class="control-label">{{ trans('general.utilities') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="utilities" value="{{ @$fixedexpense->utilities }}" />             
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="misc" class="control-label">{{ trans('general.miscellaneous') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="misc" value="{{ @$fixedexpense->misc }}" />             
                    </div>
                </div>
            </div>            

            <div class="row"><h3>{{ trans('general.returnoninvestment') }}<h3></div> 
             <div class="form-group row">
                <div class="col-sm-2">
                    <label for="down_payment" class="control-label">{{ trans('general.downpayment') }}</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" onblur="updateInitialInvestment()" name="down_payment" value="{{ @$roi->down_payment }}" />
                    </div>
                </div>
            </div>           
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="closing_costs" class="control-label">{{ trans('general.closingcosts') }}</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="closing_costs" onblur="updateInitialInvestment()" value="{{ @$roi->closing_costs }}" />             
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="misc_expenses" class="control-label">{{ trans('general.miscexpenses') }}</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="misc_expenses" onblur="updateInitialInvestment()" value="{{ @$roi->misc_expenses }}" />             
                    </div>
                </div>
            </div>
             <div class="form-group row">
                <div class="col-sm-2">
                    <label for="init_investment" class="control-label">{{ trans('general.initinvestment') }}</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="init_investment" value="{{ @$roi->init_investment }}" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3">
                    <input type="submit" value="Save" class="btn btn-primary" />
                    <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">{{ trans('general.cancel') }}</a>
                </div>
            </div>
        </form>

    </div>
@stop

@section('javascript')
    @parent
    function showForm(value){
        if(value != ''){
            $('input[name=\'id\']').val(value);
            $('#montecarloForm').show();
        } else {
            $('input[name=\'id\']').val("");
            $('#montecarloForm').hide();
        }        
    }

    $("input[name='calculator']").on('switch-change', function () {        
        $("#calculator_div").toggle();
    });

    function updateDownPayment(){
        var price = $("input[name='sale_price']").val();
        var percent_down = $("input[name='percent_down']").val();
        $("input[name='down_payment']").val(price * (percent_down/100));
        updateInitialInvestment();
    }
    
    function updateInitialInvestment(){
        var down_payment = $("input[name='down_payment']").val()*1;
        var closing_costs = $("input[name='closing_costs']").val()*1;
        var misc_expenses = $("input[name='misc_expenses']").val()*1;
        $("input[name='init_investment']").val(down_payment + closing_costs + misc_expenses);
    }
@stop