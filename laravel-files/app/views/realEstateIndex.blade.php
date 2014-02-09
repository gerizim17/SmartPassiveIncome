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
                    <td>{{ $realestate->address1 }}</td>
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
                        <a href="{{ action('RealestatesController@edit', $realestate->id) }}" class="btn btn-default">Edit</a>
                        <a href="{{ action('MontecarloController@index', $realestate->id) }}" class="btn btn-default">Montecarlo</a>
                        <a href="{{ url('rentalhistory/selectrealestate', $realestate->id) }}" class="btn btn-default">Rental History</a>
                        <a href="{{ action('RealestatesController@handleDelete', $realestate->id) }}" class="btn btn-danger">Delete</a>                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@stop