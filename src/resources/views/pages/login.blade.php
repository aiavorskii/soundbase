@extends('layouts.auth-layout')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Login</h5>
                <form action="{{ route('login.auth') }}">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    @csrf
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
