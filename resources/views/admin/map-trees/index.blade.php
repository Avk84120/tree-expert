@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">🗺 Map Tree</h2>

    <div id="map" class="w-full h-[500px] rounded"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALPpKWjxbKNdHhPsA4SAUpp6HWpTC_XOc"></script>
<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: { lat: 19.0760, lng: 72.8777 } // Mumbai as default
        });

        var markers = [];

        @foreach($trees as $tree)
            var position = { lat: {{ $tree->latitude }}, lng: {{ $tree->longitude }} };
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                title: "{{ $tree->tree_name }}",
            });
            markers.push(marker);

            var infoWindow = new google.maps.InfoWindow({
                content: `<b>{{ $tree->tree_name }}</b><br>Lat: {{ $tree->latitude }}<br>Lng: {{ $tree->longitude }}`
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        @endforeach
    }

    window.onload = initMap;
</script>
@endsection
