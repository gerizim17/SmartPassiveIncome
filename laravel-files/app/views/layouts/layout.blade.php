<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real Estate</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />    
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-switch.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/main.css') }}" />
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
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" >
            <div class="navbar-header">
             @if(Auth::check())
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                
                  </button>
              @endif
              <a class="navbar-brand" href="{{ action('RealestatesController@index') }}">Smart Passive Income</a>
            </div>

            <div class="collapse navbar-collapse">
              @if(Auth::check())
                  <ul class="nav navbar-nav">
                    <li><a href="{{ action('RealestatesController@index') }}" class="navbar-brand">Real Estate</a></li>
                    <li><a href="{{ action('MontecarloController@index', @$realestate_id) }}" class="navbar-brand">Montecarlo</a></li>
                    <li><a href="{{ url('rentalhistory/selectrealestate', @$realestate_id) }}" class="navbar-brand">Rental History</a></li>                
                    <li><a href="{{ action('GoalsController@index') }}" class="navbar-brand">Goals</a></li>
                    <li><a href="{{ action('HomeController@handleLogout') }}" class="navbar-brand">Log Out</a></li>
                  </ul>
              @endif
            </div><!--/.nav-collapse -->            
        </nav> 
        
        @if(count($errors) > 0)
            <div class="alert alert-warning">
                @foreach($errors->all() as $message)
                    <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif 

        <!-- @if(isset($message))
            <div class="alert alert-success">
               {{ $message }}
           </div>
        @endif     -->

        @if(Session::has('message_success'))
            <div class="alert alert-success"><p>{{ Session::get('message_success') }}</p></div>
        @endif

        @if(Session::has('warning_message'))
            <div class="alert alert-warning"><p>{{ Session::get('warning_message') }}</p></div>
        @endif

        @yield('content')
        
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