<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real Estate</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />    
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-switch.min.css') }}" />
     <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- <script src="{{ asset('js/jquery-1.10.2.min.js') }}"></script> -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap-switch.min.js') }}"></script>    
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    @yield('head')
</head>
<body>    
    <div class="container">        
        <nav class="navbar navbar-default" role="navigation" >
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                
              </button>
              <a class="navbar-brand" href="{{ action('RealestatesController@index') }}">Smart Passive Income</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <li><a href="{{ action('RealestatesController@index') }}" class="navbar-brand">Real Estate</a></li>
                <li><a href="{{ action('MontecarloController@index') }}" class="navbar-brand">Montecarlo</a></li>
                <li><a href="{{ action('RentalhistoryController@index') }}" class="navbar-brand">Rental History</a></li>                
                <li><a href="{{ action('GoalsController@index') }}" class="navbar-brand">Goals</a></li>
              </ul>
            </div><!--/.nav-collapse -->            
        </nav>        
        @yield('content')
    </div>
   
    <script type="text/javascript">
        @section('javascript')
            $(function() {
              // initialize all the inputs
              $('input[type="checkbox"],[type="radio"]').not('#create-switch').bootstrapSwitch();
            });
        @show
    </script>
</body>
</html>