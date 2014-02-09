@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>All Real Estate <small>Trading time for dollars is the biggest barrier!</small></h1>
    </div>
    {{ trans('instructions.rentalhistory') }}
    <form name="dropDownForm" id="dropDownForm" action="{{ url('rentalhistory/selectrealestate') }}" method="post" role="form">
    <div class="form-group">        
        <select class="form-control" name="realestate_dropdown" onchange="$('#dropDownForm').submit();">
            <option value="">Please select a property</option>
            @foreach($realestates as $realestate)
                <option value="{{ $realestate->id }}" {{ @$realestate_id==$realestate->id?"selected":"" }}>{{ $realestate->address1.", ".$realestate->city." ".$realestate->state  }}</option>
            @endforeach
        </select> 
    </div>

    @if (isset($message))
        {{ urldecode($message) }}
    @endif

    @if (isset($realestate_id))  
        <div class="panel panel-default">
            <div class="panel-body">                
                <a href="{{ action('RentalhistoryController@create', $realestate_id) }}" class="btn btn-primary">Create Rental History</a>                    
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">                
                <div>
                    from:&nbsp;<input name="start_date" size="10" value="{{ isset($start_date)?$start_date:'01/'.date('Y') }}" />&nbsp;&nbsp;to:&nbsp;<input name="end_date" size="10" value="{{ isset($end_date)?$end_date:'12/'.date('Y') }}" />
                    <a href="#" onclick="$('#dropDownForm').submit();" class="btn btn-default">Submit</a>                      
                </div>                
            </div>
        </div>
    

         @if ($rentalhistories->isEmpty())
            <p>There is no rental history! :(</p>
        @else
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>{{ trans('general.date') }}</th>
                        <th>{{ trans('general.estimatedcashflow') }}</th>
                        <th>{{ trans('general.actualcashflow') }}</th>
                        <th>{{ trans('general.grossmonthly') }}</th>
                        <th>{{ trans('general.repairs') }}</th>
                        <th>{{ trans('general.water') }}</th>
                        <th>{{ trans('general.electric') }}</th>
                        <th>{{ trans('general.tax') }}</th>
                        <th>{{ trans('general.insurance') }}</th>
                        <th>{{ trans('general.mortgage') }}</th>
                        <th>{{ trans('general.propertymanagement') }}</th>                    
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentalhistories as $rentalhistory)
                    <tr>
                        <td>{{ date_format(date_create($rentalhistory->date), 'M Y') }}</td>
                        <td>{{ $estimate->cashflow }}</td>
                        <td>{{ $rentalhistory->cashflow }}</td>
                        <td>{{ $rentalhistory->rent }}</td>
                        <td>{{ $rentalhistory->repairs }}</td>                    
                        <td>{{ $rentalhistory->water }}</td>
                        <td>{{ $rentalhistory->electricity }}</td>
                        <td>{{ $rentalhistory->tax }}</td>
                        <td>{{ $rentalhistory->insurance }}</td>
                        <td>{{ $rentalhistory->mortgage }}</td>
                        <td>{{ $rentalhistory->property_management }}</td>                    
                        <td><a href="{{ action('RentalhistoryController@create', array($realestate_id, $rentalhistory->id)) }}" class="btn btn-default">Edit</a></td>
                        <td><a href="{{ action('RentalhistoryController@handleDelete', $rentalhistory->id) }}" class="btn btn-danger">Delete</a></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{ trans('general.averages') }}:</td>
                        <td></td>
                        <td>${{ $average_cashflow }}</td>
                        <td>{{ $average_rent }}</td>
                        <td>{{ $average_repairs }}</td>
                        <td>{{ $average_water }}</td>
                        <td>{{ $average_electricity }}</td>
                        <td>{{ $average_tax }}</td>
                        <td>{{ $average_insurance }}</td>
                        <td>{{ $average_mortgage }}</td>
                        <td>{{ $average_property_management }}</td>                        
                    </tr>
                    <tr>
                        <td>{{ trans('general.totals') }}:</td>
                        <td></td>
                        <td><strong>${{ number_format($total_cashflow) }}</strong></td>
                        <td>{{ number_format($total_rent) }}</td>
                        <td>{{ number_format($total_repairs) }}</td>
                        <td>{{ number_format($total_water) }}</td>
                        <td>{{ number_format($total_electricity) }}</td>
                        <td>{{ number_format($total_tax) }}</td>
                        <td>{{ number_format($total_insurance) }}</td>
                        <td>{{ number_format($total_mortgage) }}</td>
                        <td>{{ number_format($total_property_management) }}</td>   
                    </tr>
                   
                    @endif
                </tbody>
            </table>          
            @if (!$rentalhistories->isEmpty())  
             <div class="form-group row">
                <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $ary_account_income, $ary_account_expenses, $average_mortgage, "Averaged Income Statement") }} </div>
                <div class="col-sm-5">{{ Accounting::createIncomeStatement($realestate_id, $ary_annual_account_income, $ary_annual_account_expenses, $debt_service, "Yearly Income Statement") }} </div>
            </div>            
            @endif
    @endif
@stop
</form>