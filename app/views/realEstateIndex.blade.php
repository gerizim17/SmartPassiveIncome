@extends('layouts.layout')

@section('content')
    <div class="page-header">
        <h1>All Real Estate <small>Trading time for dollars is the biggest barrier!</small></h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <a href="{{ action('RealestatesController@create') }}" class="btn btn-primary">Create Real Estate</a>
        </div>
    </div>

    @if ($realestates->isEmpty())
        <p>There are no real estate! :(</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($realestates as $realestate)
                <tr>
                    <td>{{ $realestate->re_address1 }}</td>
                    <td>{{ $realestate->re_address2 }}</td>
                    <td>{{ $realestate->re_city }}</td>
                    <td>{{ $realestate->re_state }}</td>
                    <td>{{ $realestate->re_zip }}</td>
                    @foreach($mortgages as $mortgage)
                        @if ($mortgage->mg_re_id == $realestate->re_id)
                            <td>{{ $mortgage->mg_sale_price }}</td>
                        @endif
                    @endforeach
                    <td>
                        <a href="{{ action('RealestatesController@edit', $realestate->re_id) }}" class="btn btn-default">Edit</a>
                        <a href="{{ action('MontecarloController@index', $realestate->re_id) }}" class="btn btn-default">Montecarlo</a>
                        <a href="{{ url('rentalhistory/selectrealestate', $realestate->re_id) }}" class="btn btn-default">Rental History</a>
                        <a href="{{ action('RealestatesController@handleDelete', $realestate->re_id) }}" class="btn btn-danger">Delete</a>                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@stop