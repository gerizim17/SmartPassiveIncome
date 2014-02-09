<?php

class GoalsController extends BaseController
{
    public function index()
    {
        return View::make('goalsIndex');
    }

    public function create()
    {
        // Show the create real estate form.
        return View::make('goalsCreate');
    }

    public function handleCreate() {
        
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