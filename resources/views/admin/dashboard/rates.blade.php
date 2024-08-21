@extends('admin.layouts.admin-layout')

@section('title', 'rates')
@section('rates_active', 'active')

@section('content')
<style>
    .rate_photo:hover .after {
        opacity: 1 !important;
    }
</style>
<div class="rates_wrapper" id="rates_wrapper">
    <section class="row-2 table_wrapper">
        <div class="head">
            <h1>Rates List</h1>
            <div class="pagination">
                <button @click="this.handlePrevInRates()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.rates_current_page }}</span>
                /
                <span>@{{ this.rates_last_page }}</span>
                <button @click="this.handleNextInRates()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="flex-center">
            </div>
        </div>
        <table class="normal_table" style="white-space: nowrap">
            <thead>
                <tr>
                    <th>Reaction</th>
                    <th>User Name</th>
                    <th>User phone</th>
                    <th>User email</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="rates && rates.length > 0" v-for="rate in rates" :key="rate.id">
                    <td v-if="rate.user">@{{rate.reaction == 1 ? "Bad" : (rate.reaction == 2 ? "Good" : "Cool")}}</td>
                    <td v-if="rate.user">@{{rate.user.name}}</td>
                    <td v-if="rate.user">@{{rate.user.phone}}</td>
                    <td v-if="rate.user">@{{rate.user.email}}</td>
                    <td v-if="rate.user">@{{rate.comment}}</td>
                </tr>
                <tr v-if="!rates || rates.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="6"><h2>There is no rates!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>


</div>
@endsection

@section('scripts')
<script>
const { createApp, ref } = Vue;

createApp({
  data() {
    return {
        rate: null,
        showScooters: true,
        showRates: false,
        showFilterRates: false,
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
        rate_data: null,
        rates: null,
        search: null,
        rates_current_page: 1,
        rates_last_page: 1,

    }
  },
  methods: {
    getEditValues(rate) {
        this.to_edit_name = rate.name;
        this.to_edit_email = rate.email;
        this.to_edit_phone = rate.phone;
        this.to_edit_address = rate.address;
        this.to_edit_id = rate.id;
    },
    handlePrevInRates () {
        if (this.rates_current_page > 1) {
            this.rates_current_page -= 1;
            if (!this.search)
                this.getRates()
            else
                this.getRatesbySearch()
        }

    },
    handleNextInRates () {
        if (this.rates_current_page < this.rates_last_page) {
            this.rates_current_page += 1;
            if (!this.search)
                this.getRates()
            else
                this.getRatesbySearch()
        }

    },
    async getRates() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.get(`{{ route('get.rates') }}?page=${this.rates_current_page}`);
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                $('.loader').fadeOut()
                this.isFillterApplied = false
                this.showFilterRates = false
                this.rates = response.data.data.data
                this.rates_last_page = response.data.data.last_page
                this.rates_current_page = response.data.data.current_page
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
    this.getRates()
    $('.loader').fadeOut()
  },
  mounted() {
  },
}).mount('#rates_wrapper')
</script>
@endsection
