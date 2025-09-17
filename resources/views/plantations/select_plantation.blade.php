@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">🗺 Select Plantation Land</h1>

    <div id="map" class="w-full h-96 mb-4"></div>

    <form id="plantationForm">
        @csrf
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div class="mb-2">
            <label for="description" class="block font-semibold">Description</label>
            <input type="text" name="description" id="description" class="border rounded w-full p-2">
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save Plantation Land</button>
    </form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyALPpKWjxbKNdHhPsA4SAUpp6HWpTC_XOc"></script>
<script>
let map;
let marker;

function initMap() {
    const initialPos = { lat: 20.5937, lng: 78.9629 }; // India center
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 5,
        center: initialPos,
    });

    map.addListener("click", (event) => {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initMap();

    document.getElementById('plantationForm').addEventListener('submit', function(e){
        e.preventDefault();

        fetch('{{ route("plantation.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                latitude: document.getElementById('latitude').value,
                longitude: document.getElementById('longitude').value,
                description: document.getElementById('description').value
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection
