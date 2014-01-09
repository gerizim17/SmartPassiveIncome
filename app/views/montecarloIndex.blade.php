@extends('layouts.layout')

@section('content')
    <h1>Montecarlo Estimates!</h1>    
    @if ($realestates->isEmpty())        
        <p>There are no real estate! :(</p>
    @else        

    @if (isset($re_id))
        {{ Flowchart::drawColumnChart($re_id, "bar_div") }}
        {{ Flowchart::drawPieChart($re_id, "pie_div") }}
    @endif

    <form name="dropDownForm" id="dropDownForm" action="{{ action('MontecarloController@handleSelectRealestate') }}" method="post" role="form">
        <select class="form-control" name="re_dropdown" onchange="$('#dropDownForm').submit();">
            <option value="">Please select a property</option>
        @foreach($realestates as $realestate)
            <option value="{{ $realestate->re_id }}" {{ @$re_id==$realestate->re_id?"selected":"" }}>{{ $realestate->re_address1.", ".$realestate->re_city." ".$realestate->re_state  }}</option>
        @endforeach
        </select> 
    </form> 
    @endif
    <br />
    <div class="form-group row">
        <div class="col-sm-6" id="bar_div"></div>
        <div class="col-sm-6" id="pie_div"></div>
    </div>
    <div id="montecarloForm" {{ isset($re_id)?"":'style="display:none"' }}>
       
        <form name="rentTierForm" action="{{ url('montecarlo/propertyinfosave') }}" method="post" role="form">            
            <!-- <div class="form-group">
                <div class="col-sm-offset-3">
                    <input type="submit" value="Save" class="btn btn-primary" />
                    <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">Cancel</a>
                </div>
            </div> -->
            <input type="hidden" name="rd_id" value="{{ @$rentaldetail->rd_id }}">
            <input type="hidden" name="re_id" value="{{ @$re_id }}">                    
            <div class="row"><h3>Revenue</h3></div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="rt_rent" class="control-label">Rent</label></div>                
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="rt_rent" value="{{ @$renttier->rt_rent }}" />
                    </div>
                </div>
                <div class="col-sm-2"><label for="rt_units" class="control-label">Units</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="rt_units" value="{{ @$renttier->rt_units }}" />
                    </div>
                </div>
            </div>      

            <div class="form-group row">
                <div class="col-sm-2"><label for="rd_months_min" class="control-label">Monthts Rented Min</label></div>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <input type="text" class="form-control input-small" name="rd_months_min" value="{{ @$rentaldetail->rd_months_min }}" />
                        </div>
                    </div>          
                <div class="col-sm-1"></div>
                <div class="col-sm-2"><label for="rd_months_max" class="control-label">Months Rented Max</label></div>
                <div class="col-sm-1">
                        <div class="input-group"><input type="text" class="form-control" name="rd_months_max" value="{{ @$rentaldetail->rd_months_max }}" /></div>
                </div>
            </div>

            <div class="row"><h3>Variable Expenses<h3></div>

            <div class="form-group row">
                <div class="col-sm-2"><label for="rd_repair_min" class="control-label">Repairs Min</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="rd_repair_min" value="{{ @$rentaldetail->rd_repair_min }}" />             
                    </div>
                </div>            
                <div class="col-sm-2"><label for="rd_repair_max" class="control-label">Repairs Max</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="rd_repair_max" value="{{ @$rentaldetail->rd_repair_max }}" />             
                    </div>
                </div>
            </div>        
            
             <div class="form-group row">
                <div class="col-sm-2"><label for="rd_pm_monthly_charge" class="control-label">Property Management</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="rd_pm_monthly_charge" value="{{ @$rentaldetail->rd_pm_monthly_charge }}" />
                        <span class="input-group-addon">%</span>
                    </div>
                </div>            
                <div class="col-sm-2"><label for="rd_pm_vacancy_charge" class="control-label">PM Vacancy Rate</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="rd_pm_vacancy_charge" value="{{ @$rentaldetail->rd_pm_vacancy_charge }}" />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="mg_calculator" class="control-label">Mortgage Calculator
                </div>
                <div class="col-sm-2">
                    <input type="checkbox" {{ @$mortgage->mg_calculator=="1"?"checked":"" }} value="1" name="mg_calculator">
                </div>
            </div>
            <div id="mg_calculator_div" {{ @$mortgage->mg_calculator=="1"?"":"style='display:none'" }}>
                <div class="row"><h3>Mortgage<h3></div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="mg_sale_price" class="control-label">Sale Price</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" name="mg_sale_price" value="{{ @$mortgage->mg_sale_price }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="mg_interest_rate" class="control-label">Interest Rate</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" name="mg_interest_rate" value="{{ @$mortgage->mg_interest_rate }}" />             
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="mg_percent_down" class="control-label">Percent Down</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" name="mg_percent_down" value="{{ @$mortgage->mg_percent_down }}" />             
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>        
                 <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="mg_term" class="control-label">Mortgage Term</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="mg_term" value="{{ @$mortgage->mg_term }}" />
                            <span class="input-group-addon">years</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label for="mg_term2" class="control-label">Compare with</label>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">                    
                            <input type="text" class="form-control" name="mg_term2" value="{{ @$mortgage->mg_term2 }}" />
                            <span class="input-group-addon">years</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"><h3>Fixed Expenses<h3></div> 
                     
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="mg_monthly_payment" class="control-label">Mortgage Payment</label>            
                </div>
                
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="mg_monthly_payment" value="{{ @$mortgage->mg_monthly_payment }}" />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2"><label for="fe_taxes" class="control-label">Taxes</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="fe_taxes" value="{{ @$fixedexpense->fe_taxes }}" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="fe_insurance" class="control-label">Insurance</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="fe_insurance" value="{{ @$fixedexpense->fe_insurance }}" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="fe_utilities" class="control-label">Utilities</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="fe_utilities" value="{{ @$fixedexpense->fe_utilities }}" />             
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2"><label for="fe_misc" class="control-label">Miscellaneous</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="fe_misc" value="{{ @$fixedexpense->fe_misc }}" />             
                    </div>
                </div>
            </div>            

            <div class="row"><h3>Return On Investment<h3></div> 
             <div class="form-group row">
                <div class="col-sm-2">
                    <label for="roi_down_payment" class="control-label">Down Payment</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="roi_down_payment" value="{{ @$roi->roi_down_payment }}" />
                    </div>
                </div>
            </div>           
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="roi_closing_costs" class="control-label">Closing Costs</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="roi_closing_costs" value="{{ @$roi->roi_closing_costs }}" />             
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="roi_misc_expenses" class="control-label">Misc Expenses</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="roi_misc_expenses" value="{{ @$roi->roi_misc_expenses }}" />             
                    </div>
                </div>
            </div>
             <div class="form-group row">
                <div class="col-sm-2">
                    <label for="roi_init_investment" class="control-label">Initial Investment</label>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="roi_init_investment" value="{{ @$roi->roi_init_investment }}" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3">
                    <input type="submit" value="Save" class="btn btn-primary" />
                    <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">Cancel</a>
                </div>
            </div>
        </form>

    </div>
@stop

@section('javascript')
    @parent
    function showForm(value){
        if(value != ''){
            $('input[name=\'re_id\']').val(value);
            $('#montecarloForm').show();
        } else {
            $('input[name=\'re_id\']').val("");
            $('#montecarloForm').hide();
        }        
    }

    $("input[name='mg_calculator']").on('switch-change', function () {
        console.log("calc: ");
        $("#mg_calculator_div").toggle();
    });
@stop