<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*Route::get('/', array(
	//Filters example from filters.php
    //'before' => 'birthday|christmas',
    //'before'=> 'birthday:12/12',
    function()
    {
       return View::make('rentalHistory');
    }
));*/

//HOME
Route::get('/', 'HomeController@showLogin');
Route::post('login', 'HomeController@handleLogin');
Route::get('logout', 'HomeController@handleLogout');

//REAL ESTATE//

// Show pages.
Route::get('realestate', 'RealestatesController@index');
Route::get('realestate/create', 'RealestatesController@create');
Route::get('realestate/edit/{realestate}', 'RealestatesController@edit');
Route::get('realestate/delete/{realestate}', 'RealestatesController@handleDelete');
Route::post('realestate/create', 'RealestatesController@handleCreate');
Route::post('realestate/edit', 'RealestatesController@handleEdit');


//RENTAL HISTORY
Route::get('rentalHistory', 'RentalHistoryController@showIndex');
Route::post('rentalHistory/new', 'RentalHistoryController@newRentalHistory');

//MONTECARLO ESTIMATE
Route::get('montecarlo/{realestate_id?}', 'MontecarloController@index');
Route::post('montecarlo', 'MontecarloController@handleCreate');
Route::post('montecarlo', 'MontecarloController@handleSelectRealestate');
Route::post('montecarlo/propertyinfosave', 'MontecarloController@handlePropertyInfoSave');
Route::get('/calculateestimate/{realestate_id}/{scenarios}', 'MontecarloController@calculateEstimate');

//RENTALDETAIL
Route::post('montecarlo/rentaldetailcreate', 'RentaldetailsController@handleCreate');
Route::post('montecarlo/rentaldetailedit', 'RentaldetailsController@handleEdit');

//RENTTIER
Route::get('renttiercreate', 'RenttiersController@handleCreate');
Route::post('renttieredit', 'RenttiersController@handleEdit');

//MORTGAGE
Route::post('montecarlo/mortgagecreate', 'MortgagesController@handleCreate');
Route::post('montecarlo/mortgageedit', 'MortgagesController@handleEdit');

//FIXEDEXPENSES
Route::post('montecarlo/fixedexpensecreate', 'FixedexpensesController@handleCreate');
Route::post('montecarlo/fixedexpenseedit', 'FixedexpensesController@handleEdit');

//ROI
Route::post('montecarlo/roicreate', 'ReturnoninvestmentsController@handleCreate');
Route::post('montecarlo/roiedit', 'ReturnoninvestmentsController@handleEdit');

//RENTALHISTORY
Route::get('rentalhistory/{realestate_id?}', 'RentalhistoryController@index');
Route::get('rentalhistory/create/{realestate_id}/{rentalhistory_id?}', 'RentalhistoryController@create');
Route::get('rentalhistory/delete/{realestate}', 'RentalhistoryController@handleDelete');
Route::post('rentalhistory/create', 'RentalhistoryController@handleCreate');
Route::post('rentalhistory', 'RentalhistoryController@handleSelectRealestate');
Route::any('rentalhistory/selectrealestate/{realestate_id?}/{message?}', 'RentalhistoryController@handleSelectRealestate');

//GOALS
Route::get('goals', 'GoalsController@index');

//USERS
Route::get('users', 'UsersController@index');
Route::get('users/register', 'UsersController@create');
Route::post('users/handleRegister', 'UsersController@handleCreate');





//Route::get('rentalhistory/{realestate_id?}', 'RentalhistoryController@handleSelectRealestate');

// Route::get('the/{first}/avenger/{second}', array(
//     'as' => 'ironman',
//     function($first, $second) {
//         return "Tony Stark, the {$first} avenger {$second}.";
//     }
// ));
// Route::get('example', function()
// {
//     return URL::route('ironman', array('best', 'ever'));
// });