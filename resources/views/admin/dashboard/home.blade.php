@extends('admin.layouts.admin-layout')

@section('title', 'Home')
@section('home_active', 'active')

@section('content')
<div class="home_wrapper" id="home_wrapper">
    <section class="row-1">
        <div class="statistics">
            <div class="card">
                <h1>
                    <span>+</span> <br>
                    Collected earning <br>
                    <span>2050</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    <span>-</span> <br>
                    Requested earning <br>
                    <span>1500</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    Total trips <br>
                    <span>9500</span>                    
                </h1>
            </div>
            <div class="card">
                <h1>
                    <span>+</span> <br>
                    Sold points <br>
                    <span>15000</span>                    
                </h1>
            </div>
            <button class="button">Quick access </button>
            <button class="button">Quick access </button>
        </div>
        <div class="card notification_wrapper">
            <input type="text" name="" id="title" class="input" placeholder="title" v-model="msg_title">
            <textarea name="" id="msg" cols="30" rows="10" class="input" placeholder="Message" v-model="msg"></textarea>
            <button class="button" @click="pushNotification()">Push Notification</button>
        </div>
    </section>
    <section class="row-2 table_wrapper">
        <h1>Coupon Codes</h1>
        <table  class="normal_table">
            <thead>
                <tr>

                    <th>
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" placeholder="Title" class="input" v-model="title" style="min-width: 170px">
                    </th>
                    <th>
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code" placeholder="Code" class="input" v-model="code" style="min-width: 170px">
                    </th>
                    <th>
                        <label for="gift">Gift</label>
                        <input type="number" name="gift" id="gift" placeholder="Gift Coins" class="input" v-model="gift" style="min-width: 170px">
                    </th>
                    <th>
                        <label for="start">Start in</label>
                        <input type="date" name="start" id="start" placeholder="Start in" class="input" v-model="start_in">
                    </th>
                    <th>
                        <label for="end">End in</label>
                        <input type="date" name="end" id="end" placeholder="End in" class="input" v-model="end_in">
                    </th>
                    <th>
                        <label for="">Controls</label>
                        <button class="button" @click="addCoupon()">Add</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $coupons = App\Models\Coupon::all();
                @endphp
                @if ($coupons->count() > 0)
                    @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->title }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->gift }}</td>
                        <td>{{ $coupon->start_in }}</td>
                        <td>{{ $coupon->end_in }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper" v-if="notifications && notifications.length > 0 || notifications_search_words" >
        <div class="head">
            <h1>Notifications List</h1>
            <div class="pagination" v-if="notifications_last_page > 1">
                <button @click="notifications_current_page > 1 ? notifications_current_page -= 1 : '';this.getNotifications(this.type, this.notifications_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.notifications_current_page }}</span>
                /
                <span>@{{ this.notifications_last_page }}</span>
                <button @click="notifications_current_page < notifications_last_page ? notifications_current_page += 1 : '';this.getNotifications(this.type, this.notifications_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search notifications" class="input" v-model="notifications_search_words" @input="this.notifications_current_page = 1;getNotifications(this.type, this.notifications_current_page)">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table" style="display: table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Date</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="notifications && notifications.length > 0" v-for="notification in notifications" :key="notification.id">
                    <td>@{{notification.title}}</td>
                    <td>@{{notification.body}}</td>
                    <td>@{{new Date(notification.created_at).toLocaleDateString('en-US', {   
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric', 
                        hour: 'numeric', 
                        minute: 'numeric', 
                        hour12: true  })}}</td>
                    <td>
                        <div class="btns flex-center" style="max-width: 200px">
                            <a href="" class="button secondary" @click.prevent="resendNotification(notification.id, notification.title)">Resend</a>
                            <a href="" class="button danger" @click.prevent="deleteNotification(notification.id, notification.title)">
                                <i class="bx bx-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <tr v-if="!notifications || notifications.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no notification yet!</h2></td>
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
        title: null,
        code: null,
        gift: null,
        start_in: null,
        end_in: null,
        msg_title: null,
        msg: null,
        notifications_search_words: null,
        type: "Main",
        notifications_current_page: 1,
        notifications_last_page: null,
        notifications: null,
    }
  },
  methods: {
    async deleteNotification(id, title) {
        if (confirm("Are you sure you want to delete " + title + " notification")) {
        $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route('notification.delete') }}`, {
                    notification_id: id,
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
    async resendNotification(id, title) {
        if (confirm("Are you sure you want to resend " + title + " notification")) {
        $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route('notification.resend') }}`, {
                    notification_id: id,
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
    async getNotifications(type = null, page = 1) {
        let options = type ? {
            type: type,
            page: page,
            search_words: this.notifications_search_words,
        } : {};

        try {
            const response = await axios.post(`{{ route('notification.get') }}`,
            options,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            );
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                $('.loader').fadeOut()
                this.notifications = response.data.data.data
                this.notifications_current_page = response.data.data.current_page
                this.notifications_last_page = response.data.data.last_page
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
    async addCoupon() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('coupon.put') }}`, {
                title: this.title,
                code: this.code,
                gift: this.gift,
                start_in: this.start_in,
                end_in: this.end_in,
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
    async pushNotification() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('notification.push') }}`, {
                msg_title: this.msg_title,
                msg: this.msg,
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
  },
  created() {
    $('.loader').fadeOut()
    this.getNotifications(this.type, 1)
  },
  mounted() {
  },
}).mount('#home_wrapper')
</script>
@endsection