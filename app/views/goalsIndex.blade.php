@extends('layouts.layout')

@section('content')
    <h1>Goals!<small> without action they are only dreams.</small></h1>

    {{ Flowchart::drawGoalsCharts("goals_chart_div") }}

   
     <div id="goals_chart_div"></div>
   

@stop

@section('javascript')
    @parent    
@stop