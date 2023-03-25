@extends('general')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
                    <div class="container">
                        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="collapsibleNavId">
                            <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('dashboard') }}" aria-current="page">Dashboard</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="{{ route('user.edit') }}" id="dropdownId"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
                                    <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownId">
                                        <a class="dropdown-item" href="{{ route('user.edit') }}">Profile</a>
                                        <a class="dropdown-item" href="{{ route('user.providers') }}">Providers</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        @yield('content')
    </div>
@endsection
