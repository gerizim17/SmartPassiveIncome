@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>All Real Estate <small>Trading time for dollars is the biggest barrier!</small></h1>
    </div>
    <form name="dropDownForm" id="dropDownForm" action="{{ url('rentalhistory/selectrealestate') }}" method="post" role="form">
    <div class="form-group">        
        <select class="form-control" name="re_dropdown" onchange="$('#dropDownForm').submit();">
            <option value="">Please select a property</option>
            @foreach($realestates as $realestate)
                <option value="{{ $realestate->re_id }}" {{ @$re_id==$realestate->re_id?"selected":"" }}>{{ $realestate->re_address1.", ".$realestate->re_city." ".$realestate->re_state  }}</option>
            @endforeach
        </select> 
    </div>

    @if (isset($message))
        {{ urldecode($message) }}
    @endif

    @if (isset($re_id))  
        <div class="panel panel-default">
            <div class="panel-body">                
                <a href="{{ action('RentalhistoryController@create', $re_id) }}" class="btn btn-primary">Create Rental History</a>                    
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Estimated Cashflow</th>
                        <th>Actual Cashflow</th>
                        <th>Gross Monthly</th>
                        <th>Repairs</th>
                        <th>Water</th>
                        <th>Electric</th>
                        <th>Tax</th>
                        <th>Insurance</th>
                        <th>Mortgage</th>
                        <th>Property Management</th>                    
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentalhistories as $rentalhistory)
                    <tr>
                        <td>{{ date_format(date_create($rentalhistory->rh_date), 'M Y') }}</td>
                        <td>{{ $estimate->est_cashflow }}</td>
                        <td>{{ $rentalhistory->rh_cashflow }}</td>
                        <td>{{ $rentalhistory->rh_rent }}</td>
                        <td>{{ $rentalhistory->rh_repairs }}</td>                    
                        <td>{{ $rentalhistory->rh_water }}</td>
                        <td>{{ $rentalhistory->rh_electricity }}</td>
                        <td>{{ $rentalhistory->rh_tax }}</td>
                        <td>{{ $rentalhistory->rh_insurance }}</td>
                        <td>{{ $rentalhistory->rh_mortgage }}</td>
                        <td>{{ $rentalhistory->rh_property_management }}</td>                    
                        <td><a href="{{ action('RentalhistoryController@create', array($re_id, $rentalhistory->rh_id)) }}" class="btn btn-default">Edit</a></td>
                        <td><a href="{{ action('RentalhistoryController@handleDelete', $rentalhistory->rh_id) }}" class="btn btn-danger">Delete</a></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>Averages:</td>
                        <td></td>
                        <td>${{ $average_cashflow }}</td>
                        <td>${{ $average_rent }}</td>
                        <td>${{ $average_repairs }}</td>
                        <td>${{ $average_water }}</td>
                        <td>${{ $average_electricity }}</td>
                        <td>${{ $average_tax }}</td>
                        <td>${{ $average_insurance }}</td>
                        <td>${{ $average_mortgage }}</td>
                        <td>${{ $average_property_management }}</td>                        
                    </tr>
                     <tr class="success">
                        <td>Totals:</td>
                        <td></td>
                        <td><strong>${{ number_format($total_cashflow) }}</strong></td>
                        <td>${{ number_format($total_rent) }}</td>
                        <td>${{ number_format($total_repairs) }}</td>
                        <td>${{ number_format($total_water) }}</td>
                        <td>${{ number_format($total_electricity) }}</td>
                        <td>${{ number_format($total_tax) }}</td>
                        <td>${{ number_format($total_insurance) }}</td>
                        <td>${{ number_format($total_mortgage) }}</td>
                        <td>${{ number_format($total_property_management) }}</td>   
                    </tr>
                    @endif
                </tbody>
            </table>
    @endif
@stop
</form>