@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>Create Real Estate <small>and someday finish it!</small></h1>
    </div>

    <form action="{{ action('RealestatesController@handleCreate') }}" method="post" role="form">
        <div class="form-group">
            <label for="re_address1">Address 1</label>
            <input type="text" class="form-control" name="re_address1" />
        </div>
        <div class="form-group">
            <label for="re_address2">Address 2</label>
            <input type="text" class="form-control" name="re_address2" />
        </div>
         <div class="form-group">
            <label for="re_city">City</label>
            <input type="text" class="form-control" name="re_city" />
        </div>
         <div class="form-group">
            <label for="re_state">State</label>
            <input type="text" class="form-control" name="re_state" />
        </div>
         <div class="form-group">
            <label for="re_zip">Zip</label>
            <input type="text" class="form-control" name="re_zip" />
        </div>
         <div class="form-group">
            <label for="mg_sale_price">Sale Price</label>
            <input type="text" class="form-control" name="mg_sale_price"" />
        </div> 
        <input type="submit" value="Create" class="btn btn-primary" />
        <a href="{{ action('RealestatesController@index') }}" class="btn btn-link">Cancel</a>
    </form>
@stop