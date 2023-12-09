@extends('admin.layouts.admin-layout')

@section('title', 'Scooters')
@section('scooters_active', 'active')

@section('content')
<div class="scooter_wrapper" id="scooter_wrapper">
    <section class="main">
        <div class="statistics side">
            <div class="card">
                <h1>
                    Total devices <br>
                    <span>45</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    Activated <br>
                    <span>20</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    Locked <br>
                    <span>25</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    Battery less 20% <br>
                    <span>10</span>                    
                </h1>
            </div>
        </div>

        <div class="map_wrapper card">
            <div class="head">
                <a href="{{route('zones.manage')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path d="M3 5v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2zm16.002 14H5V5h14l.002 14z"></path><path d="M15 12h2V7h-5v2h3zm-3 3H9v-3H7v5h5z"></path></svg>
                    Zones
                </a>
                
            </div>
            <br>
            <div class="map" id="map"></div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        $('.loader').fadeOut()
    })
    const markers = [];
    function initMap () {
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {lat: 37.4221, lng: -122.0841},
        });
        const markersList = [
            { lat: 37.4221, lng: -122.0841 },
            { lat: 37.4245, lng: -122.0825 },
            { lat: 37.4269, lng: -122.0811 },
        ];

        for (const marker of markersList) {
            // Create a new marker object with the current lat and lng
            const currentMarker = new google.maps.Marker({
                position: { lat: marker.lat, lng: marker.lng },
                map, // Set the map property to the current map object
            });
            // Add the marker to the markers array
            markers.push(currentMarker);
        }

    }
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&callback=initMap&libraries=places&v=weekly"
    defer></script>
@endsection