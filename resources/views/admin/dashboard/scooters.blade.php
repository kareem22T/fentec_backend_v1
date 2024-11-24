@extends('admin.layouts.admin-layout')

@section('title', 'Scooters')
@section('scooters_active', 'active')

@section('content')
    <div class="scooter_wrapper" id="scooter_wrapper">
        <section class="main">
            <div class="statistics side">
                <!-- Total devices -->
                <div class="card" @mouseover="hoveredCategory = 'all'" @mouseleave="hoveredCategory = null">
                    <h1 v-if="scooters && scooters.length">
                        Total devices <br>
                        <span>@{{ scooters?.length }}</span>
                    </h1>
                    <!-- Pop-up on hover -->
                    <div class="popup" v-if="hoveredCategory === 'all'">
                        <table>
                            <tr v-for="scooter in scooters" :key="scooter.id">
                                <td>@{{ scooter.iot_id }}</td>
                                <td>@{{ scooter.machine_no }}</td>
                                <td>@{{ scooter.battary_charge }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Activated scooters -->
                <div class="card" @mouseover="hoveredCategory = 'activated'" @mouseleave="hoveredCategory = null">
                    <h1>
                        Activated <br>
                        <span>{{ $Activated_scooters->count() }}</span>
                    </h1>
                    <!-- Pop-up on hover -->
                    <div class="popup" v-if="hoveredCategory === 'activated'">
                        <table>
                            @foreach ($Activated_scooters as $item)
                            <tr>
                                <td>{{ $item->iot_id }}</td>
                                <td>{{ $item->machine_no }}</td>
                                <td>{{ $item->battary_charge }}%</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <!-- Locked scooters -->
                <div class="card" @mouseover="hoveredCategory = 'locked'" @mouseleave="hoveredCategory = null">
                    <h1>
                        Locked <br>
                        <span>{{ $locked_scooters->count() }}</span>
                    </h1>
                    <!-- Pop-up on hover -->
                    <div class="popup" v-if="hoveredCategory === 'locked'">
                        <table>
                            @foreach ($locked_scooters as $item)
                            <tr>
                                <td>{{ $item->iot_id }}</td>
                                <td>{{ $item->machine_no }}</td>
                                <td>{{ $item->battary_charge }}%</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <!-- Battery less than 20% -->
                <div class="card" @mouseover="hoveredCategory = 'lowBattery'" @mouseleave="hoveredCategory = null">
                    <h1>
                        Battery less 20% <br>
                        <span>@{{ scooters?.filter(item => item.battary_charge < 20).length }}</span>
                    </h1>
                    <!-- Pop-up on hover -->
                    <div class="popup" v-if="hoveredCategory === 'lowBattery'">
                        <table>
                            <tr v-for="scooter in scooters.filter(scooter => scooter.battary_charge < 20)" :key="scooter.id">
                                <td>@{{ scooter.iot_id }}</td>
                                <td>@{{ scooter.machine_no }}</td>
                                <td>@{{ scooter.battary_charge }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="map_wrapper card">
                <div class="head">
                    @if(Auth::guard("admin")->user()->role != "Moderator")
                    <a href="{{ route('zones.manage') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="transform: ;msFilter:;">
                            <path d="M3 5v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2H5c-1.103 0-2 .897-2 2zm16.002 14H5V5h14l.002 14z"></path>
                            <path d="M15 12h2V7h-5v2h3zm-3 3H9v-3H7v5h5z"></path>
                        </svg>
                        Zones
                    </a>
                    @endif
                </div>
                <br>
                <div class="map" id="map"></div>
            </div>
        </section>

        <section class="row-2 table_wrapper">
            <div class="head">
                <h1>Scooters List </h1>
                <div class="export-btn">
                    @exportTable('scooters', true)
                </div>
                <div class="pagination">
                    <button @click="this.handlePrevInIot()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled"
                            width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z"
                                stroke-width="0" fill="currentColor" />
                        </svg>
                    </button>
                    <span>@{{ this.iot_current_page }}</span>
                    /
                    <span>@{{ this.iot_last_page }}</span>
                    <button @click="this.handleNextIniot()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled"
                            width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z"
                                stroke-width="0" fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div class="flex-center">
                    <button class="add-btn" @click="iot_title = 'Iot';showAddIot = true">Add <i
                            class="bx bx-plus"></i></button>
                    <div class="form-group search">
                        <input type="text" name="search" id="search" placeholder="Search Scooter" class="input"
                            v-model="search" @input="getScooters()">
                        <i class='bx bx-search'></i>
                    </div>
                </div>
            </div>
            <table class="normal_table">
                <thead>
                    <tr style="white-space: nowrap">
                        <th>Iot ID</th>
                        <th>Machine No.</th>
                        <th>Token</th>
                        <th>Charge</th>
                        <th>Controls</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="scooters && scooters.length > 0" v-for="iot in scooters" :key="iot.id">
                        <td>@{{ iot.iot_id }}</td>
                        <td>@{{ iot.machine_no }}</td>
                        <td>@{{ iot.token }}</td>
                        <td>@{{ iot.battary_charge }}%</td>
                        <td>
                            <div class="btns flex-center">
                                <button class="button success" @click="handleEditIot(iot)"><i
                                        class='bx bx-edit'></i></button>
                                {{-- <button class="button danger" @click="deleteIot(iot.id, iot.machine_no)"><i
                                        class='bx bx-trash'></i></button> --}}
                                <div class="button primary" style="position: relative;margin: 0;"
                                    @click="showBattaryPopup[iot.id] ? showBattaryPopup[iot.id] = !showBattaryPopup[iot.id] : showBattaryPopup[iot.id] = true">
                                    <i class='bx bxs-battery-charging'></i>
                                    <div class="battary-pop-up"
                                        v-if="showBattaryPopup[iot.id] && showBattaryPopup[iot.id] === true"
                                        style="position: absolute;bottom: 100%;padding-bottom: 10px;display: flex;gap: 10px;">
                                        <button class="button success"
                                            @click="unlockBattary(iot.id, iot.machine_no)">Unlock</button>
                                        <button class="button danger"
                                            @click="lockBattary(iot.id, iot.machine_no)">Lock</button>
                                    </div>
                                </div>
                                <button class="button secondary" @click="lockWheel(iot.id, iot.machine_no)"><i
                                        class='bx bx-lock'></i></button>
                                <button class="button gray" @click="unlockWheelandlock(iot.id, iot.machine_no)"><i
                                        class='bx bx-lock-open'></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!scooters || scooters.length == 0"
                        style="font-size: 20px; font-weight: 700; text-align: center">
                        <td colspan="6">
                            <h2>There is no scooters!</h2>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>


        <div class="hide-content" @click="showAddIot = false" v-if="showAddIot| showEditIot"></div>
        <div class="pop-up show_request_details_wrapper card" v-if="showAddIot">
            <h1>Add @{{ iot_title }}</h1>
            <br>
            <div class="form-group">
                <input type="text" name="iot_id" id="iot_id" class="form-control input" v-model="iot_id"
                    placeholder="Iot Id">
            </div>
            <div class="form-group">
                <input type="text" name="machine_no" id="machine_no" class="form-control input" v-model="machine_no"
                    placeholder="Machine No.">
            </div>
            <br>
            <div class="form-group">
                <input type="text" name="token" id="token" class="form-control input" v-model="token"
                    placeholder="Token">
            </div>
            <br>
            <div class="btns flex-center">
                <button class="button secondary" @click="showAddIot = false;iot_data = null">Cancel</button>
                <button class="button success" @click="addIot()">Add</button>
            </div>
        </div>
        <div class="pop-up show_request_details_wrapper card" v-if="showEditIot && iot_data">
            <h1>Edit @{{ iot_data.machine_no }} information</h1>
            <br>
            <div class="form-group">
                <input type="text" name="iot_id" id="iot_id" class="form-control input"
                    v-model="to_edit_iot_iot_id" placeholder="Iot id">
            </div>
            <div class="form-group">
                <input type="text" name="machine_no" id="machine_no" class="form-control input"
                    v-model="to_edit_iot_machine_no" placeholder="Machine No">
            </div>
            <br>
            <div class="form-group">
                <input type="text" name="token" id="token" class="form-control input"
                    v-model="to_edit_iot_token" placeholder="Token">
            </div>
            <br>
            <div class="btns flex-center">
                <button class="button secondary" @click="showEditIot = false">Cancel</button>
                <button class="button success" @click="editIot()">edit</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('.loader').fadeOut();
        });
        async function initMap() {
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: {
                    lat: 35.54754277777778,
                    lng: 6.149226666666666
                },
            });

            let markers = []; // Array to store the markers

            // Fetch scooter data
            async function getZones() {
                // Fetch zone data and draw polygons
                const response = await fetch("/admin/scooters/get-zones");
                const zones = await response.json();

                zones.forEach((zone) => {
                    const polygon = new google.maps.Polygon({
                        paths: JSON.parse(zone.path),
                        strokeColor: "#000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: zone.type === 0 ? "#ff000063" : (zone.type === 1 ? "#00ff0057" :
                            "#ffa50040"),
                        fillOpacity: 0.35,
                    });
                    polygon.setMap(map);
                });
            }
            getZones()
            let scooters = await fetch("/admin/scooters/get-scooters");
            let scooters_data = await scooters.json();

            const infowindow = new google.maps.InfoWindow();
            // Add markers to the map
            scooters_data.forEach((scooter, index) => {
                if (index === 0) {
                    map.setCenter({
                        lat: parseFloat(scooter.latitude),
                        lng: parseFloat(scooter.longitude)
                    });
                }

                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(scooter.latitude),
                        lng: parseFloat(scooter.longitude)
                    },
                    map: map,
                    icon: {
                        url: "{{ asset('/images/high_charge.png') }}",
                        scaledSize: new google.maps.Size(40, 50),
                    },
                    customInfo: `<div><h3>Scooter ${scooter.machine_no}</h3><p>Last User: (${scooter.trips.user?.email})</p></div>`,
                    title: `Scooter ${scooter.machine_no}`
                });

                markers.push(marker);

                // Add hover event listener
                marker.addListener("mouseover", () => {
                    infowindow.setContent(marker.customInfo);
                    infowindow.open(map, marker);
                });

                marker.addListener("mouseout", () => {
                    infowindow.close();
                });
            });


            // Update markers every 5 seconds
            setInterval(async () => {
                markers.forEach(marker => {
                    marker.setMap(null);
                });

                markers = [];

                scooters = await fetch("/admin/scooters/get-scooters");
                scooters_data = await scooters.json();

                scooters_data.forEach((scooter, index) => {
                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(scooter.latitude),
                            lng: parseFloat(scooter.longitude)
                        },
                        map: map,
                        icon: {
                            url: "{{ asset('/images/high_charge.png') }}",
                            scaledSize: new google.maps.Size(40, 50),
                        },
                        customInfo: `<div><h3>Scooter ${scooter.machine_no}</h3><p>Last User: (${scooter.trips.user?.email})</p></div>`,
                        title: `Scooter ${scooter.machine_no}`
                    });

                    markers.push(marker);

                    marker.addListener("mouseover", () => {
                        infowindow.setContent(marker.customInfo);
                        infowindow.open(map, marker);
                    });

                    marker.addListener("mouseout", () => {
                        infowindow.close();
                    });
                });
            }, 5000);
        }

        // Ensure that the initMap function is globally accessible
        window.initMap = initMap;
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADMSyZQR7V38GWvZ3MEl_DcDsn0pTS0WU&callback=initMap&libraries=places&v=weekly"
        defer></script>

    <script>
        const {
            createApp,
            ref
        } = Vue;

        createApp({
            data() {
                return {
                    machine_no: null,
                    iot_id: null,
                    showAddIot: false,
                    iot_title: null,
                    hoveredCategory: null, // stores the category of the card being hovered
                    token: null,
                    to_edit_iot_machine_no: null,
                    to_edit_iot_iot_id: null,
                    to_edit_iot_token: null,
                    to_edit_id: null,
                    showEditIot: false,
                    iot_data: null,
                    iot: null,
                    search: "",
                    iot_current_page: 1,
                    iot_last_page: 1,
                    scooters: null,
                    showBattaryPopup: {},
                }
            },
            methods: {
                handleEditIot(iot) {
                    this.getEditValues(iot);
                    this.iot_data = iot;
                    this.showEditIot = true;
                },
                getEditValues(iot) {
                    this.to_edit_iot_machine_no = iot.machine_no;
                    this.to_edit_iot_iot_id = iot.iot_id;
                    this.to_edit_iot_token = iot.token;
                    this.to_edit_id = iot.id;
                },
                handlePrevInIot() {
                    if (this.iot_current_page > 1) {
                        this.iot_current_page -= 1;
                        this.getScooters()
                    }

                },
                handleNextIniot() {
                    if (this.iot_current_page < this.iot_last_page) {
                        this.iot_current_page += 1;
                        this.getScooters()
                    }

                },
                async editIot() {
                    $('.loader').fadeIn().css('display', 'flex')
                    try {
                        const response = await axios.post(`{{ route('scooter.update') }}`, {
                            id: this.to_edit_id,
                            token: this.to_edit_iot_token,
                            machine_no: this.to_edit_iot_machine_no,
                            iot_id: this.to_edit_iot_iot_id,
                        }, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });
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
                            $.each(response.data.errors, function(key, value) {
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
                },
                async addIot() {
                    $('.loader').fadeIn().css('display', 'flex')
                    try {
                        const response = await axios.post(`{{ route('scooter.add') }}`, {
                            token: this.token,
                            machine_no: this.machine_no,
                            iot_id: this.iot_id,
                        }, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });
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
                            $.each(response.data.errors, function(key, value) {
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
                },
                async deleteIot(id, serial) {
                    if (confirm("Are You Sure you want to delete " + serial + " Scooter")) {
                        $('.loader').fadeIn().css('display', 'flex')
                        try {
                            const response = await axios.post(`{{ route('scooter.delete') }}`, {
                                iot_id: id,
                            }, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            });
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
                                $.each(response.data.errors, function(key, value) {
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
                },
                async unlockBattary(id, serial) {
                    if (confirm("Are You Sure you want to unlock battary " + serial + " Scooter")) {
                        $('.loader').fadeIn().css('display', 'flex')
                        try {
                            const response = await axios.post(`{{ route('scooter.unlock.battary') }}`, {
                                iot_id: id,
                            }, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            });
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
                                    // window.location.reload()
                                }, 2000);
                            } else {
                                $('.loader').fadeOut()
                                document.getElementById('errors').innerHTML = ''
                                $.each(response.data.errors, function(key, value) {
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
                },
                async lockBattary(id, serial) {
                    if (confirm("Are You Sure you want to lock battary " + serial + " Scooter")) {
                        $('.loader').fadeIn().css('display', 'flex')
                        try {
                            const response = await axios.post(`{{ route('scooter.lock.battary') }}`, {
                                iot_id: id,
                            }, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            });
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
                                    // window.location.reload()
                                }, 2000);
                            } else {
                                $('.loader').fadeOut()
                                document.getElementById('errors').innerHTML = ''
                                $.each(response.data.errors, function(key, value) {
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
                },
                async lockWheel(id, serial) {
                    if (confirm("Are You Sure you want to lock Wheel for " + serial + " Scooter")) {
                        $('.loader').fadeIn().css('display', 'flex')
                        try {
                            const response = await axios.post(`{{ route('scooter.lock.wheel') }}`, {
                                iot_id: id,
                            }, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            });
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
                                    // window.location.reload()
                                }, 2000);
                            } else {
                                $('.loader').fadeOut()
                                document.getElementById('errors').innerHTML = ''
                                $.each(response.data.errors, function(key, value) {
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
                },
                async unlockWheelandlock(id, serial) {
                    if (confirm("Are You Sure you want to unlock " + serial + " Scooter")) {
                        $('.loader').fadeIn().css('display', 'flex')
                        try {
                            const response = await axios.post(`{{ route('scooter.unlock.wheel.lock') }}`, {
                                iot_id: id,
                            }, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            });
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
                                    // window.location.reload()
                                }, 2000);
                            } else {
                                $('.loader').fadeOut()
                                document.getElementById('errors').innerHTML = ''
                                $.each(response.data.errors, function(key, value) {
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
                },
                async getScooters() {
                    try {
                        const response = await axios.get(
                            `{{ route('scooters.get') }}?page=${this.iot_current_page}&search_word=${this.search}`
                            );
                        if (response.data.status === true) {
                            this.scooters = response.data.data.data;
                            this.iot_current_page = response.data.data.current_page;
                            this.iot_last_page = response.data.data.last_page;
                            // console.log(response.data);
                            $('.loader').fadeOut()
                            setTimeout(() => {
                                $('#errors').fadeOut('slow')
                            }, 2000);
                        } else {
                            $('.loader').fadeOut()
                            document.getElementById('errors').innerHTML = ''
                            $.each(response.data.errors, function(key, value) {
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
                },
            },
            created() {
                this.getScooters()
            },
            mounted() {},
        }).mount('#scooter_wrapper')
    </script>
@endsection
