@extends('layouts.layout')

@section('content')
    

    <div>
        <div class="page-header">
            <h1>Login <small>now!</small></h1>
        </div>
        <div class="well well-lg form-signup">
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