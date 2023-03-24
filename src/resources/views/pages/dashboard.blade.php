@extends('layouts.logged-in-layout')

@section('content')
    <div class="dashboard mt-3">
        <div class="row">
            <div class="col-12">
                <ul class="nav  provider-nav">
                    <li class="nav-item">
                        <a href="" class="nav-link">Spotify</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Soundcloud</a>
                    </li>
                </ul>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-3">
                <!-- Nav tabs -->
                <ul class="nav flex-column" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                            role="tab" aria-controls="home" aria-selected="true">Liked tracks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                            role="tab" aria-controls="profile" aria-selected="false">My playlists</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button"
                            role="tab" aria-controls="messages" aria-selected="false">Liked playlists</a>
                    </li>
                </ul>

            </div>
            <div class="col-9">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @include('parts.tracks-table')
                    </div>
                    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @include('parts.tracks-table')
                    </div>
                    <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        @include('parts.tracks-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
