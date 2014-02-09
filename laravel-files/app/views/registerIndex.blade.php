@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>New User Registration <small>get it done son!</small></h1>
    </div>
    <form action="{{ action('UsersController@handleCreate') }}" method="post" role="form">   
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" class="form-control" name="firstname" value="{{ Input::old('firstname') }}" />
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" class="form-control" name="lastname" value="{{ Input::old('lastname') }}" />
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="text" class="form-control" name="email" value="{{ Input::old('email') }}" />
        </div>        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" value="{{ Input::old('password') }}" />
        </div>
        <div class="form-group">
            <label for="password_confirmation">Password Confirmation</label>
            <input type="password" class="form-control" name="password_confirmation" value="{{ Input::old('password_confirmation') }}" />
        </div>

        <input type="submit" value="Create Account" class="btn btn-primary" /> 
    </form>

@stop