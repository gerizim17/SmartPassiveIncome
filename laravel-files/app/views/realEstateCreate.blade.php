@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>Create Real Estate <small>and someday finish it!</small></h1>
    </div>

    <form action="{{ action('RealestatesController@handleCreate') }}" method="post" role="form">
        <input type="hidden" name="realestate_id" value="{{ @$realestate->id }}">

        <div class="form-group">
            <label for="address1">Address 1</label>
            <input type="text" class="form-control" name="address1" />
        </div>
        <div class="form-group">
            <label for="address2">Address 2</label>
            <input type="text" class="form-control" name="address2" />
        </div>
         <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" name="city" />
        </div>
         <div class="form-group">
            <label for="state">State</label>
            <input type="text" class="form-control" name="state" />
        </div>
         <div class="form-group">
            <label for="zip">Zip</label>
            <input type="text" class="form-control" name="zip" />
        </div>
         <div class="form-group">
            <label for="sale_price">Sale Price</label>
            <input type="text" class="form-control" name="sale_price" />
        </div> 
        <input type="submit" value="Create" class="btn btn-primary" />
        <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">Cancel</a>
    </form>
@stop