@extends('layouts.logged-in-layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach ($providers as $provider)
                    <div class="col-3 mt-3">
                        @if ($provider['authorized'])
                            <a href="{{ $provider['dashboard'] }} ">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $provider['name'] }}</h4>
                                        <p class="card-text">Songs count: {{ $provider['tracks_count'] }}</p>
                                        <br/>
                                        <a class="btn btn-primary"
                                            href="{{ $provider['data_action'] }}">Get data</a>
                                    </div>
                                </div>
                            </a>
                        @else
                            <a class="btn btn-primary" href="{{ $provider['auth_action'] }}">Authorize {{ $provider['name'] }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
