@extends('layouts.layout')

@section('content')
    <h1>{{ trans('general.goals') }}<small> {{ trans('general.goalstext') }}</small></h1>

    {{ Flowchart::drawGoalsCharts("goals_chart_div") }}

   
     <div id="goals_chart_div"></div>
   

@stop

@section('javascript')
    @parent    
@stop