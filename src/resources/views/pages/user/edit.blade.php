@extends('layouts.logged-in-layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="edit-profile-formV" style="height: 100vh;">
                <div class="card">
                    <div class="card-body">
                        @include('parts.user-form', [
                            'title'  => 'Edit',
                            'action' => route('user.update')
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
