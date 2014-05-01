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
        <input type="hidden" name="realestate_id" value="{{ $realestate_id }}">
        <input type="hidden" name="rentalhistory_id" value="{{ $rentalhistory_id }}">
        <div class="form-group">
            <label for="date">Date</label>
            <input type="text" class="form-control" name="date" value="{{ SmartPassiveIncome::dateToMonthYear(@$rentalhistory->date) }}" />
        </div>
        <div class="form-group">
            <label for="rent">Gross Rent</label>
            <input type="text" class="form-control" name="rent" value="{{ @$rentalhistory->rent }}" />
        </div>
         <div class="form-group">
            <label for="repairs">Repairs</label>
            <input type="text" class="form-control" name="repairs" value="{{ @$rentalhistory->repairs }}" />
        </div>
         <div class="form-group">
            <label for="water">Water</label>
            <input type="text" class="form-control" name="water" value="{{ @$rentalhistory->water }}" />
        </div>
         <div class="form-group">
            <label for="electricity">Electricity</label>
            <input type="text" class="form-control" name="electricity" value="{{ @$rentalhistory->electricity }}" />
        </div>   
        <div class="form-group">
            <label for="tax">Tax</label>
            <input type="text" class="form-control" name="tax" value="{{ @$rentalhistory->tax }}" />
        </div>  
        <div class="form-group">
            <label for="insurance">Insurance</label>
            <input type="text" class="form-control" name="insurance" value="{{ @$rentalhistory->insurance }}" />
        </div>  
        <div class="form-group">
            <label for="mortgage">Mortgage</label>
            <input type="text" class="form-control" name="mortgage" value="{{ @$rentalhistory->mortgage }}" />
        </div>
        <div class="form-group">
            <label for="property_management">Property Management</label>
            <input type="text" class="form-control" name="property_management" value="{{ @$rentalhistory->property_management      }}" />
        </div>     
        <input type="submit" value="Save" class="btn btn-primary" />
        <a href="{{ URL::previous() }}" class="btn btn-link">Cancel</a>
    </form>
@stop