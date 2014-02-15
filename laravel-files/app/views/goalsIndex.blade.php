@extends('layouts.layout')

@section('content')
	<div class="page-header">
    	<h1>{{ trans('general.goals') }}<small> {{ trans('general.goalstext') }}</small></h1>
    </div>

    <div class="page-header">
	    {{ trans('instructions.goals') }}
	    <br />
    </div>

    {{ Flowchart::drawGoalsCharts("goals_chart_div") }}

   
     <div id="goals_chart_div"></div>
   

@stop

@section('javascript')
    @parent    
@stop