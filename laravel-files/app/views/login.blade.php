@extends('layouts.layout')

@section('content')
    

    <div class="form-signup">
        <div class="page-header text-center">
            <h2><span class="text-info">Smart Passive Income</span><br /><small>Of what use is money in the hand of a fool, since he has no desire to get wisdom...</small></h2>            
        </div>
        <div class="well well-lg">
            <form action="{{ action('HomeController@handleLogin') }}" method="post" role="form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="text" class="form-control" name="email" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" />
                </div>

                <input type="submit" value="Login" class="btn btn-primary" />   
                <a href="{{ action('UsersController@create') }}" class="btn btn-link">I'm a new user!</a>     
            </form>
        </div>
    </div>
@stop