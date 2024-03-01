@extends('admin.layouts.admin-layout')

@section('title', 'Scooters')
@section('scooters_active', 'active')

@section('content')
<div class="scooter_wrapper" id="scooter_wrapper">
    <h1>Manage Zones</h1>
    <section class="zones_main">
        <div class="map_wrapper card">
            <div class="head" style="display: flex;justify-content: center;align-items: center;gap: 1rem;white-space: nowrap;">
                <h2>Draw zone: </h2>
                <button id="add_point">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M256 0c17.7 0 32 14.3 32 32V42.4c93.7 13.9 167.7 88 181.6 181.6H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H469.6c-13.9 93.7-88 167.7-181.6 181.6V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V469.6C130.3 455.7 56.3 381.7 42.4 288H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H42.4C56.3 130.3 130.3 56.3 224 42.4V32c0-17.7 14.3-32 32-32zM107.4 288c12.5 58.3 58.4 104.1 116.6 116.6V384c0-17.7 14.3-32 32-32s32 14.3 32 32v20.6c58.3-12.5 104.1-58.4 116.6-116.6H384c-17.7 0-32-14.3-32-32s14.3-32 32-32h20.6C392.1 165.7 346.3 119.9 288 107.4V128c0 17.7-14.3 32-32 32s-32-14.3-32-32V107.4C165.7 119.9 119.9 165.7 107.4 224H128c17.7 0 32 14.3 32 32s-14.3 32-32 32H107.4zM256 224a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                    Add point
                </button>
                <select name="zone_type" id="zone_type" class="input">
                    <option value="1" selected>Green Zone</option>
                    <option value="2">Orange Zone</option>
                    <option value="0">Red Zone</option>
                </select>
                <button class="button" onclick="add()">Save Zone</button>
            </div>
            <br>
                    
            <div class="autocomplete-map">
                <div class="pac-card" id="pac-card">
                    <div id="pac-container">
                        <input id="pac-input" type="text" class="input" placeholder="Enter a location" />
                    </div>
                </div>
                <div id="map" class="map auto_complete_map"></div>    
            </div>

        </div>
        <section class="row-2 table_wrapper">
            <div class="head">
                <h1>Zones List</h1>
            </div>
            <table class="normal_table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Controls</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($zones && $zones->count() > 0)
                        @foreach ($zones as $zone)
                            <tr>
                                <td>#{{ $zone->id }}</td>
                                <td>{{ $zone->type == 0 ? "Red Zone" : ($zone->type == 1 ? "Green Zone" : "Orange Zone") }}</td>
                                <td>{{ $zone->created_at }}</td>
                                <td>
                                    <div class="btns flex-center">
                                        <button class="button danger"><i class='bx bx-trash'  onclick="deleteZone({{ $zone->id }})"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr style="font-size: 20px; font-weight: 700; text-align: center">
                            <td colspan="5"><h2>There is no added zones!</h2></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>

    </section>
</div>
@endsection

@section('scripts')

@php
$zones = \App\Models\Zone::all();
@endphp

<script>
    $(function () {
        $('.loader').fadeOut()
    })
</script>
    <script>
        const markers = [];
        let path = []
        async function deleteZone(id) {
            alert("are you sure you want to delete zone #" + id)
            try {
                const response = await axios.post(`{{ route('zones.delete') }}`,
                {
                    id: id,
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                );
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    $('.loader').fadeOut()
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                        window.location.reload()
                    }, 2000);
                    } else {
                    $('.loader').fadeOut()
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('input').css('outline', 'none')
                        $('#errors').fadeOut('slow')
                    }, 5000);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        }

        async function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 30.10811445920852, lng: 31.33138500000002 },
                zoom: 15,
                mapTypeControl: false,
            });
            const card = document.getElementById("pac-card");
            const input = document.getElementById("pac-input");
            const biasInputElement = document.getElementById("use-location-bias");
            const strictBoundsInputElement = document.getElementById("use-strict-bounds");
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
                types: ["establishment"],
            };

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(card);

            const autocomplete = new google.maps.places.Autocomplete(input, options);

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo("bounds", map);
            const polygon = new google.maps.Polygon({
                paths: path,
                strokeColor: "#000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "rgba(0, 255, 102, 1)",
                fillOpacity: 0.35,
            });

            polygon.setMap(map);

            const response = await fetch("/admin/scooters/get-zones");
            const zones = await response.json();
            zones.map((zone, index) => {
                const polygon2 = new google.maps.Polygon({
                    paths: JSON.parse(zone.path),
                    strokeColor: "#000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: zone.type === 0 ? "#ff0000" : (zone.type === 1 ? "#00ff00" : "#ffa500"),
                    fillOpacity: 0.35,
                });
                polygon2.setMap(map)
            })
            

            const svgPath =
            "M15 11.586V6h2V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2h2v5.586l-2.707 1.707A.996.996 0 0 0 6 14v2a1 1 0 0 0 1 1h4v3l1 2 1-2v-3h4a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L15 11.586z";

            // Create a custom SVG icon
            const customIcon = {
                path: svgPath,
                fillColor: "rgba(0, 0, 0, 1)",
                fillOpacity: 1,
                scale: 1.5, // Adjust the scale as needed
                anchor: new google.maps.Point(10, 24), // Anchor point for the icon (bottom left)
                origin: new google.maps.Point(0, -50), // Origin of the icon (top left)
                strokeWeight: 1,
                strokeColor: "black",
            };
               
            document.getElementById("zone_type").addEventListener("change", (event) => {
                const selectedValue = event.target.value;

                switch (selectedValue) {
                    case "0":
                    polygon.setOptions({ fillColor: "#ff0000" }); // Red
                    break;
                    case "1":
                    polygon.setOptions({ fillColor: "#00ff00" }); // Green
                    break;
                    case "2":
                    polygon.setOptions({ fillColor: "#ffa500" }); // Orange
                    break;
                    default:
                    break;
                }
            });
            
            autocomplete.addListener("place_changed", () => {

                const place = autocomplete.getPlace();
                let lat = place.geometry.location.lat()
                let lng = place.geometry.location.lng()
                addressLat = lat;
                addressLng = lng;

                if (!place.geometry || !place.geometry.location) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
            });
            document.getElementById('add_point').addEventListener('click', () => {
                const marker = new google.maps.Marker({
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    map,
                    position: map.getCenter(),
                    icon: customIcon,
                });

                markers.push(marker)
                path = []
                markers.map((point, index, array) => {
                    path.push({lat: point.getPosition().lat(), lng: point.getPosition().lng()})
                })
                polygon.setPaths(path);
                google.maps.event.addListener(marker, 'dragend', function () {
                    map.setCenter(marker.getPosition())
                    geocodePosition(marker.getPosition())
                    path = []
                    markers.map((point, index, array) => {
                        path.push({lat: point.getPosition().lat(), lng: point.getPosition().lng()})
                    })
                    polygon.setPaths(path);
                })
            })

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            window.onload = function () {
                    autocomplete.setTypes(["address"]);
                };

            }

        function geocodePosition(pos) {
            geocoder = new google.maps.Geocoder()
            geocoder.geocode({
                latLng: pos
            },
                function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $('#place-address').text(results[0].formatted_address)
                        $('#place-name').text(results[0].formatted_address.split(',')[0])
                    } else {

                    }
                }
            )
        }
        async function add() {
            try {
                const response = await axios.post(`{{ route('zones.add') }}`,
                {
                    path: JSON.stringify(path),
                    type: $('#zone_type').val()
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                );
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    $('.loader').fadeOut()
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                        window.location.reload()
                    }, 2000);
                    } else {
                    $('.loader').fadeOut()
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('input').css('outline', 'none')
                        $('#errors').fadeOut('slow')
                    }, 5000);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        }
    </script>

    <!-- Load the map when the page is loaded -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&callback=initMap&libraries=places&v=weekly"
        defer></script>
@endsection