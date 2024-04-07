@extends('admin.layouts.admin-layout')

@section('title', 'trips')
@section('trips_active', 'active')

@section('content')
<style>
    .trip_photo:hover .after {
        opacity: 1 !important;
    }
</style>
<div class="trips_wrapper" id="trips_wrapper">
    <section class="row-2 table_wrapper">
        <div class="head">
            <h1>Trips List</h1>
            <div class="pagination">
                <button @click="this.handlePrevInTrips()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.trips_current_page }}</span>
                /
                <span>@{{ this.trips_last_page }}</span>
                <button @click="this.handleNextInTrips()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="flex-center">
                <button class="add-btn primary" @click="showFilterTrips = true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-month" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                        <path d="M16 3v4" />
                        <path d="M8 3v4" />
                        <path d="M4 11h16" />
                        <path d="M7 14h.013" />
                        <path d="M10.01 14h.005" />
                        <path d="M13.01 14h.005" />
                        <path d="M16.015 14h.005" />
                        <path d="M13.015 17h.005" />
                        <path d="M7.01 17h.005" />
                        <path d="M10.01 17h.005" />
                      </svg>
                      <span class="period" v-if="isFillterApplied && from && to">
                        <span @click="isFillterApplied = false; getTrips()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </span>
                        From <span>@{{new Date(this.from).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span> TO <span>@{{new Date(this.to).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span>
                      </span>
                </button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Trip" class="input" v-model="search" @input="getTripsbySearch()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table" style="white-space: nowrap">
            <thead>
                <tr>
                    <th>Park</th>
                    <th>User Name</th>
                    <th>User phone</th>
                    <th>User email</th>
                    <th>Scooter Serial</th>
                    <th>Started at</th>
                    <th>Ended at</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="trips && trips.length > 0" v-for="trip in trips" :key="trip.id">
                    <td>
                        <a :href="'/images/uploads/' + trip.lock_photo" download class="img trip_photo" v-if="trip.lock_photo" style="display: block;position: relative;width: 70px;height: 70px;padding: 5px;background: white;border-radius: 5px;border: 2px solid rgba(255, 115, 0,1);">
                            <div class="after" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;display: flex;justify-content: center;align-items: center;background: white;border-radius: 5px;opacity: 0;transition: 0.3s all ease-in">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="40" height="40" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff7300" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    {{-- <path stroke="none" d="M0 0h24v24H0z" fill="none"/> --}}
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                    <path d="M7 11l5 5l5 -5" />
                                    <path d="M12 4l0 12" />
                                </svg>
                            </div>
                            <img :src="'/images/uploads/' + trip.lock_photo" style="width: 100%; height: 100%"/>
                        </a>
                        <svg v-if="!trip.lock_photo" style="width: 70px;height: 70px;padding: 5px;background: white;border-radius: 5px;border: 2px solid #ff2724;" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ff2825" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M15 8h.01" />
                            <path d="M13 21h-7a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v7" />
                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0" />
                            <path d="M22 22l-5 -5" />
                            <path d="M17 22l5 -5" />
                        </svg>
                    </td>
                    <td v-if="trip.user">@{{trip.user.name}}</td>
                    <td v-if="trip.user">@{{trip.user.phone}}</td>
                    <td v-if="trip.user">@{{trip.user.email}}</td>
                    <td>@{{trip.scooter.machine_no}}</td>
                    <td>
                        @{{new Date(trip.started_at).toLocaleDateString("en-US", {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        })}}
                    </td>
                    <td>
                        @{{new Date(trip.ended_at).toLocaleDateString("en-US", {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        })}}
                    </td>
                    <td>@{{!trip.ended_at ? "Ongoing" : (trip.lock_photo ? "Completed" : "Not Submited")}}</td>
                </tr>
                <tr v-if="!trips || trips.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="6"><h2>There is no Sellsers!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>


    <div class="hide-content" @click="showFilterTrips = false" v-if="showFilterTrips"></div>
    <div class="pop-up show_request_details_wrapper card" v-if="showFilterTrips">
        <h1>Choose time interval</h1>
        <br>
        <div class="form-group">
            <label for="date_from" style="font-size: 18px;margin-bottom: 10px;text-align: left;display: block;padding: 0 1rem;font-weight: 500;">From</label>
            <input type="date" name="date_from" id="date_from" class="form-control input" v-model="from" placeholder="Date From">
        </div>
        <br>
        <div class="form-group">
            <label for="date_from" style="font-size: 18px;margin-bottom: 10px;text-align: left;display: block;padding: 0 1rem;font-weight: 500;">To</label>
            <input type="date" name="date_to" id="date_to" class="form-control input" v-model="to" placeholder="Date To">
        </div>
        <br>
        <div class="btns flex-center">
            <button class="button secondary" @click="this.showFilterTrips = false">Cancel</button>
            <button class="button success" @click="getTripsFillterByDate()">Choose</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const { createApp, ref } = Vue;

createApp({
  data() {
    return {
        trip: null,
        showScooters: true,
        showTrips: false,
        showFilterTrips: false,
        isFillterApplied: false,
        from: null,
        to: null,
        name: null,
        email: null,
        phone: null,
        address: null,
        password: null,
        password_confirmation: null,
        to_edit_name: null,
        to_edit_email: null,
        to_edit_phone: null,
        to_edit_password: null,
        to_edit_id: null,
        to_edit_password_confirmation: null,
        to_edit_address: null,
        trip_data: null,
        trips: null,
        search: null,
        trips_current_page: 1,
        trips_last_page: 1,

    }
  },
  methods: {
    getEditValues(trip) {
        this.to_edit_name = trip.name;
        this.to_edit_email = trip.email;
        this.to_edit_phone = trip.phone;
        this.to_edit_address = trip.address;
        this.to_edit_id = trip.id;
    },
    handlePrevInTrips () {
        if (this.trips_current_page > 1) {
            this.trips_current_page -= 1;
            if (!this.search)
                this.getTrips()
            else
                this.getTripsbySearch()
        }

    },
    handleNextInTrips () {
        if (this.trips_current_page < this.trips_last_page) {
            this.trips_current_page += 1;
            if (!this.search)
                this.getTrips()
            else
                this.getTripsbySearch()
        }

    },
    async getTrips() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.get(`{{ route('get.trips') }}?page=${this.trips_current_page}`);
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                $('.loader').fadeOut()
                this.isFillterApplied = false
                this.showFilterTrips = false
                this.trips = response.data.data.data
                this.trips_last_page = response.data.data.last_page
                this.trips_current_page = response.data.data.current_page
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
    },
    async getTripsbySearch() {
    //   $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('search.trips') }}`, {
                page: this.trips_current_page,
                search_word: this.search
            });
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.isFillterApplied = false;
                this.showFilterTrips = false;
                $('.loader').fadeOut()
                this.trips = response.data.data.data
                this.trips_last_page = response.data.data.last_page
                this.trips_current_page = response.data.data.current_page
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
    },
    async getTripsFillterByDate() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('fillter.trip') }}`, {
                from: this.from,
                to: this.to
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.trips = response.data.data.data
                this.trips_last_page = response.data.data.last_page
                this.trips_current_page = response.data.data.current_page
                this.isFillterApplied = true
                this.showFilterTrips = false
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
    },
  },
  created() {
    this.getTrips()
    $('.loader').fadeOut()
  },
  mounted() {
  },
}).mount('#trips_wrapper')
</script>
@endsection
