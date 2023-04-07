<div class="table-responsive">
    <table class="table table-primary">
        <thead>
            <tr>
                <th scope="col">Artist</th>
                <th scope="col">Name</th>
                <th scope="col">Duration</th>
            </tr>
        </thead>
        <tbody>
            {{-- track var is Song model with its relations --}}
            @if (empty($tracks))
                <tr>
                    <td colspan="3">No tracks yet</td>
                </tr>
            @else
                @foreach ($tracks as $track)
                {{-- @php
                dd($track);
                @endphp --}}
                    <tr class="">
                        <td>
                            @foreach ($track['artists'] as $artist)
                                <a href="{{ route('artist', ['artist' => $artist['id']]) }}">{{ $artist['name'] }}</a>
                            @endforeach
                        </td>
                        <td>{{ $track['name'] }}</td>
                        <td>{{ msToMinSec($track['duration_ms']) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

