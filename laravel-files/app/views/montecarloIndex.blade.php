@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>Montecarlo Estimates!</h1>            
    </div>
    
   @if (isset($realestate_id) && !isset($renttier))
        <div class="page-header">
            {{ trans('instructions.montecarloestimate') }}
            <br />
        </div>
    @endif

    @if (isset($realestate_id) && isset($renttier))        
        {{ Flowchart::drawPieChart($realestate_id, "pie_div") }}
        {{ Flowchart::drawColumnChart($realestate_id, "bar_div") }}

        {{ Flowchart::drawPieChart($realestate_id, "pie_div_best", "best") }}
        {{ Flowchart::drawColumnChart($realestate_id, "bar_div_best", "best") }}

        {{ Flowchart::drawPieChart($realestate_id, "pie_div_worst", "worst") }}
        {{ Flowchart::drawColumnChart($realestate_id, "bar_div_worst", "worst") }}
    @endif

    <form name="dropDownForm" id="dropDownForm" action="{{ action('MontecarloController@handleSelectRealestate') }}" method="post" role="form">
        <select class="form-control" name="realestate_dropdown" onchange="$('#dropDownForm').submit();">
            <option value="">{{ trans('general.selectproperty') }}</option>
        @foreach($realestates as $realestate)
            <option value="{{ $realestate->id }}" {{ @$realestate_id==$realestate->id?"selected":"" }}>{{ $realestate->address1.", ".$realestate->city." ".$realestate->state  }}</option>
        @endforeach
        </select> 
    </form> 
    
    <br />


    @if (isset($realestate_id) && isset($renttier))
    <div id="this-carousel-id" class="carousel slide"><!-- class of slide for animation -->
      <div class="carousel-inner">
        <div class="item">
            <div class="montecarlo_carousel"><div class="text-center"><h3>Worst Year: {{ SmartPassiveIncome::money($estimateworst->cashflow*12) }}</h3><hr /></div>
                <div class="form-group row">
                    <div class="col-sm-6" id="pie_div_worst"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4" id="bar_div_worst"></div>
                </div>
                <div class="form-group row">                   
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment, "Monthly Income Statement", "worst", "", "") }} </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment*12, "Yearly Income Statement", "worst", "", "") }} </div>                    
                </div>
            </div>           
        </div>
        <div class="item active"><!-- class of active since it's the first item -->
            <div class="montecarlo_carousel"><div class="text-center"><h3>Median Year: {{ SmartPassiveIncome::money($estimate->cashflow*12) }}</h3><hr /></div>
               <div class="form-group row">
                    <div class="col-sm-6" id="pie_div"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4" id="bar_div"></div>  
                </div>
                <div class="form-group row">                   
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment, "Monthly Income Statement", "average", "", "") }} </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment*12, "Yearly Income Statement", "average", "", "") }} </div>                    
                </div>
            </div>
        </div>
        <div class="item">
            <div class="montecarlo_carousel"><div class="text-center"><h3>Best Year: {{ SmartPassiveIncome::money($estimatebest->cashflow*12) }}</h3><hr /></div>
            <div class="form-group row">
                    <div class="col-sm-6" id="pie_div_best"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4" id="bar_div_best"></div>  
                </div>
                <div class="form-group row">                   
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment, "Monthly Income Statement", "best", "", "") }} </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $mortgage->monthly_payment*12, "Yearly Income Statement", "best", "", "") }} </div>                    
                </div>
            </div>
        </div>
      </div>
           
        <a class="carousel-control left" href="#this-carousel-id" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="carousel-control right" href="#this-carousel-id" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>

    <div>
        Risk Assessment: {{ $estimate->risk }}% chance of losing money on any given year.
    </div>

    @endif

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
                <div class="col-sm-2"><label for="rent" class="control-label">{{ trans('general.rent') }}<span class="required_field"> *</span></label></div>                
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control input-small" name="rent" value="{{ @$renttier->rent }}" />
                    </div>
                </div>
            <!--div class="col-sm-2"><label for="units" class="control-label">{{ trans('general.units') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="units" value="{{ @$renttier->units }}" />
                    </div>
                </div-->
            </div>
            <input type="hidden" name="units" value="1" />      

            <div class="form-group row">
                <div class="col-sm-2"><label for="months_min" class="control-label">{{ trans('general.rentmin') }}</label></div>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <input type="text" class="form-control input-small" name="months_min" value="{{ (isset($rentaldetail->months_min))?$rentaldetail->months_min:8 }}" />
                        </div>
                    </div>          
                <div class="col-sm-1"></div>
                <div class="col-sm-2"><label for="months_max" class="control-label">{{ trans('general.rentmax') }}</label></div>
                <div class="col-sm-1">
                        <div class="input-group"><input type="text" class="form-control" name="months_max" value="{{ (isset($rentaldetail->months_max))?$rentaldetail->months_max:12 }}" /></div>
                </div>
            </div>

            <div class="row"><h3>{{ trans('general.varexpenses') }}<h3></div>

            <div class="form-group row">
                <div class="col-sm-2"><label for="repair_min" class="control-label">{{ trans('general.repairsmin') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="repair_min" value="{{ (isset($rentaldetail->repair_min))?$rentaldetail->repair_min:0 }}" />             
                    </div>
                </div>            
                <div class="col-sm-2"><label for="repair_max" class="control-label">{{ trans('general.repairsmax') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="repair_max" value="{{ (isset($rentaldetail->repair_max))?$rentaldetail->repair_max:0 }}" />             
                    </div>
                </div>
            </div>        
            
             <div class="form-group row">
                <div class="col-sm-2"><label for="pm_monthly_charge" class="control-label">{{ trans('general.propertymanagement') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="pm_monthly_charge" value="{{ (isset($rentaldetail->pm_monthly_charge))?$rentaldetail->pm_monthly_charge:0 }}" />
                        <span class="input-group-addon">%</span>
                    </div>
                </div>            
                <div class="col-sm-2"><label for="pm_vacancy_charge" class="control-label">{{ trans('general.pmvacrate') }}</label></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" name="pm_vacancy_charge" value="{{ (isset($rentaldetail->pm_vacancy_charge))?$rentaldetail->pm_vacancy_charge:0 }}" />
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
                        <label for="sale_price" class="control-label">{{ trans('general.saleprice') }}<span class="required_field"> *</span></label>
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
                            <input type="text" class="form-control" name="interest_rate" value="{{ (isset($mortgage->interest_rate))?$mortgage->interest_rate:6 }}" />             
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
                            <input type="text" class="form-control" onblur="updateDownPayment()" name="percent_down" value="{{ (isset($mortgage->percent_down))?$mortgage->percent_down:20 }}" />             
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
                            <input type="text" readonly class="form-control" name="term" value="{{ (isset($mortgage->term))?$mortgage->term:30}}" />
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
                            <input type="text" readonly class="form-control" name="term2" value="{{ (isset($mortgage->term2))?$mortgage->term2:15 }}" />
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
                        <input type="text" class="form-control input-small" name="monthly_payment" value="{{ (isset($mortgage->monthly_payment))?$mortgage->monthly_payment:0 }}" />
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

      $(document).ready(function(){
        $('.carousel').carousel({
          interval: false
        })

      });

    
    // Refdraw the graphs when the slider moves

    $('.carousel').on('slid.bs.carousel', function () {        
        Highcharts.charts[0].reflow();
        Highcharts.charts[1].reflow();
        Highcharts.charts[2].reflow();
        Highcharts.charts[3].reflow();
        Highcharts.charts[4].reflow();
        Highcharts.charts[5].reflow();
    })

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