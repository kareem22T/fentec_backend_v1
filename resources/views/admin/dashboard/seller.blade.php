@extends('admin.layouts.admin-layout')

@section('title', 'Seller Details')
@section('reports_active', 'active')
@if($id)
@section('content')
<div class="statistics_wrapper" id="statistics_wrapper">
    <h1 style="text-align: center;margin-top: 30px;">
        @{{seller_data.name}} Details
    </h1>

    <div style="display: flex;justify-content: center;align-items: center; gap: 1.5rem;margin: 1rem 0">
        <h3>
            Phone: @{{seller_data.phone}}
        </h3>
        <h3>
            Address: @{{seller_data.address}}
        </h3>
        <h3>
            Email: @{{seller_data.email}}
        </h3>
    </div>
    <section class="row-2 table_wrapper" >
        <div class="head" style="display: grid;grid-template-columns: 1fr 1fr 1fr;gap: 1rem">
            <h1 style="display: flex;justify-content: start">Transactions History</h1>
            <div class="pagination">
                <button @click="this.handlePrevInseller()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.seller_current_page }}</span>
                /
                <span>@{{ this.seller_last_page }}</span>
                <button @click="this.handleNextInseller()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="flex-center" style="justify-content: end">
                <button class="add-btn primary" @click="showFilterseller = true">
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
                        <span @click="isFillterApplied = false; getSellerData().then(() => {showFilterseller = false})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </span>
                        From <span>@{{new Date(this.from).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span> TO <span>@{{new Date(this.to).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span>
                      </span>
                </button>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="history && history.length > 0" v-for="result in history" :key="result.id">
                    <td>@{{result.user.name}}</td>
                    <td>@{{result.amount}}</td>
                    <td>@{{new Date(result.created_at).toLocaleString('en-US', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true })}}</td>
                </tr>
                <tr v-if="!history || history.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="6"><h2>Empty History (No Transactions)!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>


    <div class="hide-content" @click="showAddSeller = false" v-if="showAddSeller | showEditSeller | showFilterseller"></div>
    <div class="pop-up show_request_details_wrapper card" v-if="showEditSeller && seller_data">
        <h1>edit @{{ seller_data.name }} information</h1>
        <br>
        <div class="form-group">
            <input type="text" name="name" id="name" class="form-control input" v-model="to_edit_name" placeholder="Name">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="email" id="email" class="form-control input"  v-model="to_edit_email" placeholder="Email">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="phone" id="phone" class="form-control input" v-model="to_edit_phone" placeholder="Phone">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="address" id="address" class="form-control input" v-model="to_edit_address" placeholder="address">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="password" id="password" class="form-control input" v-model="to_edit_password" placeholder="New Password (optional)">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="password" id="password" class="form-control input" v-model="to_edit_password" placeholder="New Password confirmation">
        </div>
        <br>
        <div class="btns flex-center">
            <button class="button secondary" @click="showEditSeller = false">Cancel</button>
            <button class="button success" @click="editSeller()">edit</button>
        </div>
    </div>
    <div class="pop-up show_request_details_wrapper card" v-if="showFilterseller">
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
            <button class="button secondary" @click="this.showFilterseller = false">Cancel</button>
            <button class="button success" @click="getsellerFillterByDate()">Choose</button>
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
        seller: null,
        seller_id: "{{$id}}",
        history: null,
        showFilterseller: false,
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
        showEditSeller: false,
        seller_data: null,
        seller: null,
        search: null,
        seller_current_page: 1,
        seller_last_page: 1,
        
    }
  },
  methods: {
    handleEditSeller(seller) {
        this.getEditValues(seller);
        this.seller_data = seller;
        this.showEditSeller = true;
    },
    getEditValues(seller) {
        this.to_edit_name = seller.name;
        this.to_edit_email = seller.email;
        this.to_edit_phone = seller.phone;
        this.to_edit_address = seller.address;
        this.to_edit_id = seller.id;
    },
    handlePrevInseller () {
        if (this.seller_current_page > 1) {
            this.seller_current_page -= 1; 
            this.getSellerData()
        }
        
    },
    handleNextInseller () {
        if (this.seller_current_page < this.seller_last_page) {
            this.seller_current_page += 1; 
            this.getSellerData()
        }
        
    },
    async editSeller() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('update.seller') }}`, {
                seller_id: this.to_edit_id,
                name: this.to_edit_name,
                email: this.to_edit_email,
                phone: this.to_edit_phone,
                address: this.to_edit_address,
                password: this.to_edit_password,
                password_confirmation: this.to_edit_password_confirmation,
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
    },
    async getSellerData() {
    //   $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('get.seller.details') }}?page=${this.seller_current_page}`, {
                seller_id: this.seller_id,
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            );
            if (response.data.status === true) {
                this.history = response.data.data.history.data;
                this.seller_current_page = response.data.data.history.current_page;
                this.seller_last_page = response.data.data.history.last_page;
                this.seller_data = response.data.data;

                $('.loader').fadeOut()
                setTimeout(() => {
                    $('#errors').fadeOut('slow')
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
    },
    async getsellerFillterByDate() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('fillter.sellerHistory') }}`, {
                seller_id: this.seller_id,
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
                this.history = response.data.data.history.data;
                this.seller_current_page = response.data.data.history.current_page;
                this.seller_last_page = response.data.data.history.last_page;
                this.seller_data = response.data.data;
                this.isFillterApplied = true
                this.showFilterseller = false
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
    // $('.loader').fadeOut()
    this.getSellerData()
  },
  mounted() {
  },
}).mount('#statistics_wrapper')
</script>
@endsection
@endif