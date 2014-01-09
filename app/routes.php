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

//REAL ESTATE//

// Show pages.
Route::get('/', 'RealestatesController@index');
Route::get('/create', 'RealestatesController@create');
Route::get('/edit/{realestate}', 'RealestatesController@edit');
Route::get('/delete/{realestate}', 'RealestatesController@handleDelete');
Route::post('/create', 'RealestatesController@handleCreate');
Route::post('/edit', 'RealestatesController@handleEdit');
Route::get('/calculateestimate/{re_id}/{scenarios}', 'MontecarloController@calculateEstimate');

//RENTAL HISTORY
Route::get('rentalHistory', 'RentalHistoryController@showIndex');
Route::post('rentalHistory/new', 'RentalHistoryController@newRentalHistory');

//MONTECARLO ESTIMATE
Route::get('montecarlo/{re_id?}', 'MontecarloController@index');
Route::post('montecarlo', 'MontecarloController@handleCreate');
Route::post('montecarlo', 'MontecarloController@handleSelectRealestate');
Route::post('montecarlo/propertyinfosave', 'MontecarloController@handlePropertyInfoSave');

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
Route::get('rentalhistory/{re_id?}', 'RentalhistoryController@index');
Route::get('rentalhistory/create/{re_id}/{rh_id?}', 'RentalhistoryController@create');
Route::get('rentalhistory/delete/{realestate}', 'RentalhistoryController@handleDelete');
Route::post('rentalhistory/create', 'RentalhistoryController@handleCreate');
Route::post('rentalhistory', 'RentalhistoryController@handleSelectRealestate');
Route::any('rentalhistory/selectrealestate/{re_id?}/{message?}', 'RentalhistoryController@handleSelectRealestate');

//GOALS
Route::get('goals', 'GoalsController@index');
//Route::get('rentalhistory/{re_id?}', 'RentalhistoryController@handleSelectRealestate');

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