@extends('admin.layouts.admin-layout')

@section('title', 'Users')
@section('users_active', 'active')

@section('content')
<div class="users_wrapper" id="users_wrapper">
    <section class="row-2 table_wrapper">
        <div class="head">
            <h1>Users Requests</h1>
            <div class="pagination" v-if="users_requests_last_page > 1">
                <button @click="users_requests_current_page > 1 ? users_requests_current_page -= 1 : ''; this.getUsers('Requests', this.users_requests_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.users_requests_current_page }}</span>
                /
                <span>@{{ this.users_requests_last_page }}</span>
                <button @click="users_requests_current_page < users_requests_last_page ? users_requests_current_page += 1 : '';this.getUsers('Requests', this.users_requests_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search Requests Users" class="input"  v-model="Requests_search_words" @input="this.users_requests_current_page = 1;this.getUsers('Requests', this.users_requests_current_page)">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="usersRequests && usersRequests.length > 0" v-for="user in usersRequests" :key="user.id">
                    <td>@{{user.name}}</td>
                    <td>@{{user.dob}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button" @click="handleShowRequest(user)"><i class='bx bx-show-alt'></i></button>
                            {{-- <button class="button secondary" @click=""><i class='bx bx-envelope'></i></button> --}}
                            <button class="button success" @click="selectedRequest = user;handleShowApprovePopUp()"><i class='bx bx-check-circle' ></i></button>
                            <button class="button danger"  @click="selectedRequest = user;handleShowRejectionReasonPopUp()"><i class='bx bxs-user-x' ></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!usersRequests || usersRequests.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no requests!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper">
        <div class="head">
            <h1>Users List</h1>
            <div class="pagination" v-if="users_list_last_page > 1">
                <button @click="users_list_current_page > 1 ? users_list_current_page -= 1 : '';this.getUsers('Active', this.users_list_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.users_list_current_page }}</span>
                /
                <span>@{{ this.users_list_last_page }}</span>
                <button @click="users_list_current_page < users_list_last_page ? users_list_current_page += 1 : '';this.getUsers('Active', this.users_list_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search Active Users" class="input" v-model="Active_search_words" @input="this.users_list_current_page = 1;this.getUsers('Active', this.users_list_current_page)">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="usersList && usersList.length > 0" v-for="user in usersList" :key="user.id">
                    <td>@{{user.name}}</td>
                    <td>@{{user.dob}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button" @click="selectedRequest = user;showUserProfile=true;"><i class='bx bx-show-alt'></i></button>
                            <button class="button danger"  @click="selectedRequest = user;handleShowBanPopUp()"><i class='bx bxs-user-x' ></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!usersList || usersList.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no Users!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="row-2 table_wrapper" v-if="incompleteUsers && incompleteUsers.length > 0 || Incomplete_search_words" >
        <div class="head">
            <h1>Users Incomplete Registeration</h1>
            <div class="pagination" v-if="users_incomplete_last_page > 1">
                <button @click="users_incomplete_current_page > 1 ? users_incomplete_current_page -= 1 : '';this.getUsers('Incomplete', this.users_incomplete_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.users_incomplete_current_page }}</span>
                /
                <span>@{{ this.users_incomplete_last_page }}</span>
                <button @click="users_incomplete_current_page < users_incomplete_last_page ? users_incomplete_current_page += 1 : '';this.getUsers('Incomplete', this.users_incomplete_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search Incomplete Users" class="input" v-model="Incomplete_search_words" @input="users_incomplete_current_page= 1;this.getUsers('Incomplete', this.users_incomplete_current_page)">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="incompleteUsers && incompleteUsers.length > 0" v-for="user in incompleteUsers" :key="user.id">
                    <td>@{{user.email}}</td>
                    <td>@{{user.phone}}</td>
                    {{-- <td>
                        <div class="btns flex-center">
                            <button class="button secondary" @click=""><i class='bx bx-envelope'></i></button>
                        </div>
                    </td> --}}
                </tr>
                <tr v-if="!incompleteUsers || incompleteUsers.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no users!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="row-2 table_wrapper" v-if="bannedUsers && bannedUsers.length > 0" >
        <div class="head">
            <h1>Banned Users</h1>
            <div class="pagination" v-if="users_banned_last_page > 1">
                <button @click="users_banned_current_page > 1 ? users_banned_current_page -= 1 : '';this.getUsers('Banned', this.users_banned_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.users_banned_current_page }}</span>
                /
                <span>@{{ this.users_banned_last_page }}</span>
                <button @click="users_banned_current_page < users_banned_last_page ? users_banned_current_page += 1 : '';this.getUsers('Banned', this.users_banned_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search Banned Users" class="input" v-model="Banned_search_words" @input="this.users_banned_current_page = 1;getUsers('Banned', this.users_banned_current_page)">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Ban Reason</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="bannedUsers && bannedUsers.length > 0" v-for="user in bannedUsers" :key="user.id">
                    <td>@{{user.name}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.phone}}</td>
                    <td>@{{user.ban_reason}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="selectedRequest = user;handleShowApprovePopUp()"><i class='bx bx-check-circle' ></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!bannedUsers || bannedUsers.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no  banned users!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="row-2 table_wrapper" v-if="rejectedUsers && rejectedUsers.length > 0 || Rejected_search_words" >
        <div class="head">
            <h1>Rejected Users</h1>
            <div class="pagination" v-if="users_banned_last_page > 1">
                <button @click="users_rejected_current_page > 1 ? users_rejected_current_page -= 1 : '';this.getUsers('Rejected', this.users_rejected_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-left-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
                <span>@{{ this.users_rejected_current_page }}</span>
                /
                <span>@{{ this.users_rejected_last_page }}</span>
                <button @click="users_rejected_current_page < users_rejected_last_page ? users_rejected_current_page += 1 : '';this.getUsers('Rejected', this.users_rejected_current_page)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-right-filled" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" stroke-width="0" fill="currentColor" />
                      </svg>
                </button>
            </div>
            <div class="form-group search">
                <input type="text" name="search" id="search" placeholder="Search Rejected Users" class="input" v-model="Rejected_search_words" @input="this.users_rejected_current_page = 1;getUsers('Rejected, this.users_rejected_current_page')">
                <i class='bx bx-search'></i>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Rejected Reason</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="rejectedUsers && rejectedUsers.length > 0" v-for="user in rejectedUsers" :key="user.id">
                    <td>@{{user.name}}</td>
                    <td>@{{user.email}}</td>
                    <td>@{{user.phone}}</td>
                    <td>@{{user.rejection_reason}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button" @click="handleShowRequest(user)"><i class='bx bx-show-alt'></i></button>
                            {{-- <button class="button secondary" @click=""><i class='bx bx-envelope'></i></button> --}}
                            <button class="button success" @click="selectedRequest = user;handleShowApprovePopUp()"><i class='bx bx-check-circle' ></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!rejectedUsers || rejectedUsers.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no rejected users!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>

    <div class="hide-content" @click="showUserProfile = false" v-if="showBannedReasonPopup || showRequestDetails || rejectionReasonPopUp || showRjectionAlert || approvePopUp || showUserProfile"></div>
    <div class="pop-up show_request_details_wrapper card" v-if="selectedRequest && showRequestDetails">
        <div class="head">
            <div class="profile">
                <i class='bx bxs-user'  v-if="!selectedRequest.photo_path"></i>
                <img v-if="selectedRequest.photo_path" :src="'/images/uploads/' + selectedRequest.photo_path">
            </div>
            <div class="identity">
                <img :src="'/images/uploads/' + selectedRequest.identity_path" alt="">
                <a :href="'/images/uploads/' + selectedRequest.identity_path" download="download" class="after">
                    <i class='bx bx-download' ></i>
                </a>
            </div>
        </div>
        <div class="details">
            <div>
                <i class='bx bxs-user' ></i> @{{selectedRequest.name}}
            </div>
            <div>
                <i class='bx bx-envelope' ></i> @{{selectedRequest.email}}
            </div>
            <div>
                <i class='bx bx-phone' ></i> @{{selectedRequest.phone}}
            </div>
        </div>
        <div class="btns flex-center">
            <button class="button secondary" @click="showRequestDetails = false">Cancel</button>
            <button class="button success" @click="handleShowApprovePopUp()">Approve</button>
            <button class="button danger" @click="handleShowRejectionReasonPopUp()">Reject</button>
        </div>
    </div>

    <div class="pop-up card rejection_reason_pop_up" v-if="rejectionReasonPopUp">
        <h2>What is the rejection reason</h2>
        <div class="btns flex-center choices">
            <button :class="rejection_choice == 1 ? 'active' : ''" @click="handleRejectionReason(1, 'Your Identity image is not avilable please Upload it')">Identity not avilable</button>
            <button :class="rejection_choice == 2 ? 'active' : ''" @click="handleRejectionReason(2, 'Your Identity image is not identical please Upload the correct one')">Identity not identical</button>
            <button :class="rejection_choice == 3 ? 'active' : ''" @click="handleRejectionReason(3, '')">Other</button>
        </div>
        <textarea name="reason" id="reason" cols="30" rows="10" class="input" placeholder="Type your reason" v-model="rejection_reason" v-if="rejection_choice == 3">
        </textarea>
        <div class="btns flex-center">
            <button class="button secondary" @click="rejectionReasonPopUp = false">Cancel</button>
            <button class="button danger" @click="handleRejectionAlert()">Reject</button>
        </div>
    </div>

    <div class="pop-up card rejection_reason_pop_up" v-if="showBannedReasonPopup">
        <h3>Are you sure your want to ban @{{ selectedRequest.name }} Account</h3>
        <textarea name="reason" id="reason" cols="30" rows="10" class="input" placeholder="Type your reason" v-model="ban_reason">
        </textarea>
        <div class="btns flex-center">
            <button class="button secondary" @click="showBannedReasonPopup = false">Cancel</button>
            <button class="button danger" @click="banUser(selectedRequest.id)">ban</button>
        </div>
    </div>

    <div class="pop-up card rejection_reason_pop_up" v-if="showRjectionAlert">
        <h3>Are you sure your want to reject @{{ selectedRequest.name }} Account</h3>
        <div class="btns flex-center">
            <button class="button secondary" @click="showRjectionAlert = false">Cancel</button>
            <button class="button danger" @click="rejectUser(selectedRequest.id)">Reject</button>
        </div>
    </div>
    <div class="pop-up card rejection_reason_pop_up" v-if="approvePopUp">
        <h3>Are you sure your want to Approve @{{ selectedRequest.name }} Account</h3>
        <div class="btns flex-center">
            <button class="button secondary" @click="approvePopUp = false">Cancel</button>
            <button class="button success" @click="approveUser(selectedRequest.id)">Approve</button>
        </div>
    </div>
    <div class="pop-up card rejection_reason_pop_up user_profile_pop_up" v-if="showUserProfile">
        <h1>@{{ selectedRequest.name }} - Profile</h1>
        <div>
            <div>
                <div class="identity">
                    <img :src="'/images/uploads/' + selectedRequest.identity_path" alt="">
                    <a :href="'/images/uploads/' + selectedRequest.identity_path" download="download" class="after">
                        <i class='bx bx-download' ></i>
                    </a>
                </div>
                <div class="details">
                    <div>
                        <i class='bx bxs-user' ></i> @{{selectedRequest.name}}
                    </div>
                    <div>
                        <i class='bx bx-envelope' ></i> @{{selectedRequest.email}}
                    </div>
                    <div>
                        <i class='bx bx-phone' ></i> @{{selectedRequest.phone}}
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <h4>Reports</h4>
                    <p>entered the red zone #WE23 - 12/6 - 2:26 pm</p>
                    <p>entered the red zone #WE23 - 12/6 - 2:26 pm</p>
                </div>
                <div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                    <div>
                        <img src="{{ asset('/dashboard/images/destination_icon.png') }}" alt="destination icon">
                        <div>
                            <p>3th wood street <span>12/12</span></p>
                            <p>65 Marlen park <span>-50 points</span></p>
                        </div>                
                    </div>
                </div>
            </div>
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
        usersRequests: null,
        bannedUsers: null,
        usersList: null,
        incompleteUsers: null,
        selectedRequest: null,
        rejectedUsers: null,
        showRequestDetails: false,
        showBannedReasonPopup: false,
        rejectionReasonPopUp: false,
        rejection_reason: "Your Identity image is not avilable please Upload it",
        rejection_choice: 1,
        ban_reason: null,
        showRjectionAlert: false,
        approvePopUp: false,
        showUserProfile: false,

        //pagination
        users_list_current_page: 1,
        users_list_last_page: null,
        Active_search_words: null,

        users_requests_current_page: 1,
        users_requests_last_page: null,
        Requests_search_words: null,

        users_incomplete_current_page: 1,
        users_incomplete_last_page: null,
        Incomplete_search_words: null,

        users_banned_current_page: 1,
        users_banned_last_page: null,
        Banned_search_words: null,

        users_rejected_current_page: 1,
        users_rejected_last_page: null,
        Rejected_search_words: null,
    }
  },
  methods: {
    async getUsers(type = null, page = 1) {
        if (!this.Active_search_words && !this.Requests_search_words && !this.Incomplete_search_words && !this.Banned_search_words && !this.Rejected_search_words)
            $('.loader').fadeIn().css('display', 'flex')

        let options = type ? {
            getJustUsersWithType: type,
            page: page,
            Active_search_words: this.Active_search_words,
            Requests_search_words: this.Requests_search_words,
            Incomplete_search_words: this.Incomplete_search_words,
            Banned_search_words: this.Banned_search_words,
            Rejected_search_words: this.Rejected_search_words,
        } : {};

        try {
            const response = await axios.post(`{{ route('get.users') }}`,
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

                // active users 
                if (response.data.data.usersList) {
                    this.usersList = response.data.data.usersList.data
                    this.users_list_current_page = response.data.data.usersList.current_page
                    this.users_list_last_page = response.data.data.usersList.last_page
                }

                if (response.data.data.usersRequests) {
                    this.usersRequests = response.data.data.usersRequests.data
                    this.users_requests_current_page = response.data.data.usersRequests.current_page
                    this.users_requests_last_page = response.data.data.usersRequests.last_page
                }

                if (response.data.data.incompleteUsers) {
                    this.incompleteUsers = response.data.data.incompleteUsers.data
                    this.users_incomplete_current_page = response.data.data.incompleteUsers.current_page
                    this.users_incomplete_last_page = response.data.data.incompleteUsers.last_page
                }

                if (response.data.data.bannedUsers) {
                    this.bannedUsers = response.data.data.bannedUsers.data
                    this.users_banned_current_page = response.data.data.bannedUsers.current_page
                    this.users_banned_last_page = response.data.data.bannedUsers.last_page
                }
                if (response.data.data.rejectedUsers) {
                    this.rejectedUsers = response.data.data.rejectedUsers.data
                    this.users_rejected_current_page = response.data.data.rejectedUsers.current_page
                    this.users_rejected_last_page = response.data.data.rejectedUsers.last_page
                }
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
    async approveUser(id) {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('user.approve') }}`, {
                id: id,
            },
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
    async banUser(id) {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('user.ban') }}`, {
                id: id,
                ban_reason: this.ban_reason,
            },
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
    async rejectUser(id) {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('user.reject') }}`, {
                id: id,
                rejection_reason: this.rejection_reason
            },
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
    handleShowRejectionReasonPopUp() {
        this.showRequestDetails = false
        this.rejectionReasonPopUp = true;
    },
    handleShowBanPopUp () {
        this.showBannedReasonPopup = true;
        this.showRequestDetails = false
    },
    handleShowApprovePopUp() {
        this.approvePopUp = true;
        this.showRequestDetails = false
    },
    handleRejectionReason(choice, reason) {
        this.rejection_choice = choice
        this.rejection_reason = reason
    },
    handleRejectionAlert() {
        if (!this.rejection_reason) {
            $('.loader').fadeOut()
            document.getElementById('errors').innerHTML = ''
                let error = document.createElement('div')
                error.classList = 'error'
                error.innerHTML = "please enter rejection reason"
                document.getElementById('errors').append(error)
            $('#errors').fadeIn('slow')
            setTimeout(() => {
                $('input').css('outline', 'none')
                $('#errors').fadeOut('slow')
            }, 3000);
        } else {
            this.showRjectionAlert = true; 
            this.rejectionReasonPopUp = false
        }

    },
    handleShowRequest(user) {
        this.selectedRequest = user;
        this.showRequestDetails = true;
    }
  },
  created() {
    $('.loader').fadeOut()
    this.getUsers()
  },
  mounted() {
  },
}).mount('#users_wrapper')
</script>
@endsection