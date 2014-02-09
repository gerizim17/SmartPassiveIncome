<?php

class HomeController extends BaseController
{
    public function index()
    {
        return View::make('goalsIndex');
    }

    public function showLogin()
    {
       return View::make('login');
    }
    
    public function handleLogin()
    {
       if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
            return Redirect::to('realestate')->with('message', 'You are now logged in!');
        } else {
            return Redirect::to('/')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        }
    }

    public function handleLogout()
    {
        error_log("handleLogout");
        Auth::logout();
        return Redirect::to('/')->with('message_success', 'Your are now logged out!');
    }
}