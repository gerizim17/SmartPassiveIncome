@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>Edit Real Estate <small>Hiya buddy!</small></h1>
    </div>
<!-- 
    <ul class="errors">
    @foreach($errors->all('<li>:message</li>') as $message)
        {{ $message }}
    @endforeach
    </ul> -->
    @if (isset($message))
        {{ $message }}
    @endif

    <form action="{{ action('RentalhistoryController@handleCreate') }}" method="post" role="form">
        <input type="hidden" name="re_id" value="{{ $re_id }}">
        <input type="hidden" name="rh_id" value="{{ $rh_id }}">
        <div class="form-group">
            <label for="rh_date">Date</label>
            <input type="text" class="form-control" name="rh_date" value="{{ SmartPassiveIncome::dateToMonthYear($rentalhistory->rh_date) }}" />
        </div>
        <div class="form-group">
            <label for="rh_rent">Gross Rent</label>
            <input type="text" class="form-control" name="rh_rent" value="{{ $rentalhistory->rh_rent }}" />
        </div>
         <div class="form-group">
            <label for="rh_repairs">Repairs</label>
            <input type="text" class="form-control" name="rh_repairs" value="{{ $rentalhistory->rh_repairs }}" />
        </div>
         <div class="form-group">
            <label for="rh_water">Water</label>
            <input type="text" class="form-control" name="rh_water" value="{{ $rentalhistory->rh_water }}" />
        </div>
         <div class="form-group">
            <label for="rh_electricity">Electricity</label>
            <input type="text" class="form-control" name="rh_electricity" value="{{ $rentalhistory->rh_electricity }}" />
        </div>   
        <div class="form-group">
            <label for="rh_tax">Tax</label>
            <input type="text" class="form-control" name="rh_tax" value="{{ @$rentalhistory->rh_tax }}" />
        </div>  
        <div class="form-group">
            <label for="rh_insurance">Insurance</label>
            <input type="text" class="form-control" name="rh_insurance" value="{{ @$rentalhistory->rh_insurance }}" />
        </div>  
        <div class="form-group">
            <label for="rh_mortgage">Mortgage</label>
            <input type="text" class="form-control" name="rh_mortgage" value="{{ @$rentalhistory->rh_mortgage }}" />
        </div>
        <div class="form-group">
            <label for="rh_property_management">Property Management</label>
            <input type="text" class="form-control" name="rh_property_management" value="{{ @$rentalhistory->rh_property_management      }}" />
        </div>     
        <input type="submit" value="Save" class="btn btn-primary" />
        <a href="{{ URL::previous() }}" class="btn btn-link">Cancel</a>
    </form>
@stop