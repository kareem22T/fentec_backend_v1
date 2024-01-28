@extends('admin.layouts.admin-layout')

@section('title', 'Statistics')
@section('reports_active', 'active')

@section('content')
<div class="statistics_wrapper" id="statistics_wrapper">
    <div class="switchs">
        <button :class="showScooters ? 'active' : ''" @click="showScooters = true;showTechnicians = false; showAccountant = false; showSellers = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-scooter" width="70" height="70" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                {{-- <path stroke="none" d="M0 0h24v24H0z" fill="none"/> --}}
                <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M8 17h5a6 6 0 0 1 5 -5v-5a2 2 0 0 0 -2 -2h-1" />
              </svg>
              Scooters
        </button>
        <button :class="showSellers ? 'active' : ''" @click="showScooters = false;showTechnicians = false; showAccountant = false; showSellers = true">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-store" width="70" height="70" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                {{-- <path stroke="none" d="M0 0h24v24H0z" fill="none"/> --}}
                <path d="M3 21l18 0" />
                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                <path d="M5 21l0 -10.15" />
                <path d="M19 21l0 -10.15" />
                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
              </svg>
            Sellers
        </button>
    </div>
    <section class="row-2 table_wrapper" v-if="showScooters" >
        <div class="head">
            <h1>Scooters List</h1>
            <div class="pagination"></div>
            <div class="flex-center">
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Scooter" class="input">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Serial</th>
                    <th>Controls</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="Scooters && Scooters.length > 0" v-for="seller in Scooters" :key="seller.id">
                    <td>@{{seller.full_name}}</td>
                    <td>@{{seller.email}}</td>
                    <td>@{{seller.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="handleEditSeller(seller)"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="this.deleteSeller(seller.id, seller.full_name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!Scooters || Scooters.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no Scooters!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper" v-if="showSellers" >
        <div class="head" v-if="showSellers" >
            <h1>Sellers List</h1>
            <div class="pagination">
                <button @click="this.handlePrevInSellers()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.sellers_current_page }}</span>
                /
                <span>@{{ this.sellers_last_page }}</span>
                <button @click="this.handleNextInSellers()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="flex-center">
                <button class="add-btn primary" @click="showFilterSellers = true">
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
                        <span @click="isFillterApplied = false; getSellers()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                        </span>
                        From <span>@{{new Date(this.from).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span> TO <span>@{{new Date(this.to).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: '2-digit' })}}</span>
                      </span>
                </button>
                <button class="add-btn" @click="seller_title = 'Seller';showAddSeller = true">Add <i class="bx bx-plus"></i></button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Seller" class="input" v-model="search" @input="getSellersbySearch()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Sold Points</th>
                    <th>Unbilled Points</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="sellers && sellers.length > 0" v-for="seller in sellers" :key="seller.id">
                    <td>@{{seller.name}}</td>
                    <td>@{{seller.address}}</td>
                    <td>@{{seller.phone}}</td>
                    <td>@{{seller.sold_points}}</td>
                    <td>@{{seller.unbilled_points}}</td>
                    <td>
                        <div class="btns flex-center">
                            <a class="button primary" :href="`/admin/seller/${seller.id}`"><i class='bx bx-show'></i></a>
                            <button class="button success" @click="handleEditSeller(seller)"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="deleteSeller(seller.id, seller.name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!sellers || sellers.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="6"><h2>There is no Sellsers!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>


    <div class="hide-content" @click="showAddSeller = false" v-if="showAddSeller | showEditSeller | showFilterSellers"></div>
    <div class="pop-up show_request_details_wrapper card" v-if="showAddSeller">
        <h1>Add @{{ seller_title }}</h1>
        <br>
        <div class="form-group">
            <input type="text" name="name" id="name" class="form-control input" v-model="name" placeholder="Name">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="email" id="email" class="form-control input" v-model="email" placeholder="Email">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="phone" id="phone" class="form-control input" v-model="phone" placeholder="Phone">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="address" id="address" class="form-control input" v-model="address" placeholder="address">
        </div>
        <br>
        <div class="form-group">
            <input type="password" name="password" id="password" class="form-control input" v-model="password" placeholder="Password">
        </div>
        <br>
        <div class="form-group">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input" v-model="password_confirmation" placeholder="password_confirmation">
        </div>
        <br>
        <div class="btns flex-center">
            <button class="button secondary" @click="showAddSeller = false;seller_data = null">Cancel</button>
            <button class="button success" @click="addSeller()">Add</button>
        </div>
    </div>
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
    <div class="pop-up show_request_details_wrapper card" v-if="showFilterSellers">
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
            <button class="button secondary" @click="this.showFilterSellers = false">Cancel</button>
            <button class="button success" @click="getSellersFillterByDate()">Choose</button>
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
        showScooters: true,
        showSellers: false,
        showAddSeller: false,
        showFilterSellers: false,
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
        sellers: null,
        search: null,
        sellers_current_page: 1,
        sellers_last_page: 1,
        
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
    handlePrevInSellers () {
        if (this.sellers_current_page > 1) {
            this.sellers_current_page -= 1; 
            if (!this.search)
                this.getSellers()
            else
                this.getSellersbySearch()
        }
        
    },
    handleNextInSellers () {
        if (this.sellers_current_page < this.sellers_last_page) {
            this.sellers_current_page += 1; 
            if (!this.search)
                this.getSellers()
            else
                this.getSellersbySearch()
        }
        
    },
    async getSellers() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.get(`{{ route('get.sellers') }}?page=${this.sellers_current_page}`);
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                $('.loader').fadeOut()
                this.isFillterApplied = false
                this.showFilterSellers = false
                this.sellers = response.data.data.data
                this.sellers_last_page = response.data.data.last_page
                this.sellers_current_page = response.data.data.current_page
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
    async getSellersbySearch() {
    //   $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('search.sellers') }}`, {
                page: this.sellers_current_page,
                search_word: this.search
            });
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                this.isFillterApplied = false;
                this.showFilterSellers = false;
                $('.loader').fadeOut()
                this.sellers = response.data.data.data
                this.sellers_last_page = response.data.data.last_page
                this.sellers_current_page = response.data.data.current_page
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
    async addSeller() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('add.seller') }}`, {
                name: this.name,
                email: this.email,
                phone: this.phone,
                address: this.address,
                password: this.password,
                password_confirmation: this.password_confirmation,
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
    async getSellersFillterByDate() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('fillter.seller') }}`, {
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
                this.sellers = response.data.data.data
                this.sellers_last_page = response.data.data.last_page
                this.sellers_current_page = response.data.data.current_page
                this.isFillterApplied = true
                this.showFilterSellers = false
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
    async deleteSeller(id, name) {
        if (confirm("Are you sure you want to delete " + name + " account")) {
        $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route('seller.delete') }}`, {
                    seller_id: id,
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
    },
  },
  created() {
    this.getSellers()
    $('.loader').fadeOut()
  },
  mounted() {
  },
}).mount('#statistics_wrapper')
</script>
@endsection