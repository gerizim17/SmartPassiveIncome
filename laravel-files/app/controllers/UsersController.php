<?php

class UsersController extends BaseController
{
    public function index()
    {
        return View::make('users');
    }

    public function create(){

        // Show the create real estate form.
        return View::make('registerIndex');
    }

    public function handleCreate()
    {
        error_log("handleCreate registration");
        $user = new User;

        // Handle edit form submission.
        $validator = User::validate(Input::all());
        if($validator->passes())
        {
            $user->firstname = Input::get('firstname');
            $user->lastname = Input::get('lastname');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();            
            return Redirect::action('HomeController@showLogin')->with('message_success', 'Thanks for registering! You can now login.');
        } else 
        {            
            return Redirect::action('UsersController@create')->withErrors($validator)->withInput();
        }            
    }

    public function edit($re_id)
    {   
        
    }

    public function handleEdit()
    {
       
    }


    public function handleDelete($re_id)
    {
       
    }
}