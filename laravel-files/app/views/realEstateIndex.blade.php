@extends('layouts.layout')

@section('content')
    <div class="page-header">
        {{ App::setLocale('en') }}
        <h1>{{ trans('general.allrealestate') }} <!--small>Trading time for dollars is the biggest barrier!</small--></h1>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="{{ action('RealestatesController@create') }}" class="btn btn-primary">{{ trans('general.createrealestate') }}</a>
        </div>
    </div>

    @if ($realestates->isEmpty())
        <p>{{ trans('general.norealestate') }}</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('general.address1') }}</th>
                    <th>{{ trans('general.address2') }}</th>
                    <th>{{ trans('general.city') }}</th>
                    <th>{{ trans('general.state') }}</th>
                    <th>{{ trans('general.zip') }}</th>
                    <th>{{ trans('general.price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($realestates as $realestate)
                <tr>
                    <td><a href="{{ url('rentalhistory/selectrealestate', $realestate->id) }}">{{ $realestate->address1 }}</a></td>
                    <td>{{ $realestate->address2 }}</td>
                    <td>{{ $realestate->city }}</td>
                    <td>{{ $realestate->state }}</td>
                    <td>{{ $realestate->zip }}</td>
                    @foreach($mortgages as $mortgage)
                        @if ($mortgage->realestate_id == $realestate->id)
                            <td>{{ SmartPassiveIncome::money($mortgage->sale_price) }}</td>
                        @endif
                    @endforeach
                    <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            menu <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-right dropup" role="menu">                            
                            <li><a href="{{ action('MontecarloController@index', $realestate->id) }}">Montecarlo</a></li>
                            <li><a href="{{ url('rentalhistory/selectrealestate', $realestate->id) }}">Rental History</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ action('RealestatesController@edit', $realestate->id) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a></li>
                            <li><a href="{{ action('RealestatesController@handleDelete', $realestate->id) }}"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>                            
                          </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@stop