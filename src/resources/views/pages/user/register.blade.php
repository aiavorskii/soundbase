@extends('layouts.logged-in-layout')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                @include('parts.user-form', [
                    'title'  => 'Register',
                    'action' => route('user.create')
                ])
            </div>
        </div>
    </div>
@endsection
