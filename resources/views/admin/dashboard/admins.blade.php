@extends('admin.layouts.admin-layout')

@section('title', 'Admins')
@section('admins_active', 'active')

@section('content')
<div class="admins_wrapper" id="admins_wrapper">
    <div class="switchs">
        <button :class="showMasters ? 'active' : ''" @click="showMasters = true;showTechnicians = false; showAccountant = false; ShowModerators = false">
            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M96 128a128 128 0 1 0 256 0A128 128 0 1 0 96 128zm94.5 200.2l18.6 31L175.8 483.1l-36-146.9c-2-8.1-9.8-13.4-17.9-11.3C51.9 342.4 0 405.8 0 481.3c0 17 13.8 30.7 30.7 30.7H162.5c0 0 0 0 .1 0H168 280h5.5c0 0 0 0 .1 0H417.3c17 0 30.7-13.8 30.7-30.7c0-75.5-51.9-138.9-121.9-156.4c-8.1-2-15.9 3.3-17.9 11.3l-36 146.9L238.9 359.2l18.6-31c6.4-10.7-1.3-24.2-13.7-24.2H224 204.3c-12.4 0-20.1 13.6-13.7 24.2z"/></svg>
            Masters
        </button>
        <button :class="showTechnicians ? 'active' : ''" @click="showMasters = false;showTechnicians = true; showAccountant = false; ShowModerators = false">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M23.75 36.25C18.1875 36.25 7.08334 39.0417 7.08334 44.5833V46.6667C7.08334 47.8125 8.02084 48.75 9.16668 48.75H38.3333C39.4792 48.75 40.4167 47.8125 40.4167 46.6667V44.5833C40.4167 39.0417 29.3125 36.25 23.75 36.25ZM14.875 23.75H32.6458C33.2083 23.75 33.6667 23.2917 33.6667 22.7292V22.6875C33.6667 22.4168 33.5591 22.1571 33.3677 21.9657C33.1762 21.7742 32.9166 21.6667 32.6458 21.6667H32.0833C32.0833 18.5833 30.3958 15.9375 27.9167 14.4792V16.4583C27.9167 17.0417 27.4583 17.5 26.875 17.5C26.2917 17.5 25.8333 17.0417 25.8333 16.4583V13.625C25.1667 13.4583 24.4792 13.3333 23.75 13.3333C23.0208 13.3333 22.3333 13.4583 21.6667 13.625V16.4583C21.6667 17.0417 21.2083 17.5 20.625 17.5C20.0417 17.5 19.5833 17.0417 19.5833 16.4583V14.4792C17.1042 15.9375 15.4167 18.5833 15.4167 21.6667H14.875C14.741 21.6667 14.6082 21.6931 14.4844 21.7444C14.3605 21.7957 14.248 21.8709 14.1532 21.9657C14.0584 22.0605 13.9832 22.173 13.9319 22.2968C13.8806 22.4207 13.8542 22.5534 13.8542 22.6875V22.75C13.8542 23.2917 14.3125 23.75 14.875 23.75ZM23.75 32.0833C27.625 32.0833 30.8542 29.4167 31.7917 25.8333H15.7083C16.6458 29.4167 19.875 32.0833 23.75 32.0833ZM50.7917 17.9792L52.7292 16.25L51.1667 13.5417L48.6875 14.3542C48.3958 14.125 48.0625 13.9375 47.7083 13.7917L47.1875 11.25H44.0625L43.5417 13.7917C43.1875 13.9375 42.8542 14.125 42.5417 14.3542L40.0833 13.5417L38.5208 16.25L40.4583 17.9792C40.4167 18.3333 40.4167 18.7083 40.4583 19.0625L38.5208 20.8333L40.0833 23.5417L42.5833 22.75C42.8542 22.9583 43.1667 23.125 43.4792 23.2708L44.0625 25.8333H47.1875L47.75 23.2917C48.0833 23.1458 48.375 22.9792 48.6667 22.7708L51.1458 23.5625L52.7083 20.8542L50.7708 19.0833C50.8333 18.6875 50.8125 18.3333 50.7917 17.9792ZM45.625 21.1458C44.9343 21.1458 44.272 20.8715 43.7836 20.3831C43.2952 19.8947 43.0208 19.2323 43.0208 18.5417C43.0208 17.851 43.2952 17.1886 43.7836 16.7002C44.272 16.2119 44.9343 15.9375 45.625 15.9375C46.3157 15.9375 46.9781 16.2119 47.4664 16.7002C47.9548 17.1886 48.2292 17.851 48.2292 18.5417C48.2292 19.2323 47.9548 19.8947 47.4664 20.3831C46.9781 20.8715 46.3157 21.1458 45.625 21.1458ZM45.4167 27.4792L43.6458 28.0625C43.4375 27.8958 43.2083 27.7708 42.9583 27.6667L42.5833 25.8333H40.3542L39.9792 27.6458C39.7292 27.75 39.4792 27.8958 39.2708 28.0417L37.5208 27.4583L36.3958 29.3958L37.7708 30.625C37.75 30.8958 37.75 31.1458 37.7708 31.3958L36.3958 32.6667L37.5208 34.6042L39.3125 34.0417C39.5208 34.1875 39.7292 34.3125 39.9583 34.4167L40.3333 36.25H42.5625L42.9583 34.4375C43.1875 34.3333 43.4167 34.2083 43.625 34.0625L45.3958 34.625L46.5208 32.6875L45.1458 31.4167C45.1667 31.1458 45.1667 30.8958 45.1458 30.6458L46.5208 29.4167L45.4167 27.4792ZM41.4583 32.8958C40.4375 32.8958 39.6042 32.0625 39.6042 31.0417C39.6042 30.0208 40.4375 29.1875 41.4583 29.1875C42.4792 29.1875 43.3125 30.0208 43.3125 31.0417C43.3125 32.0625 42.4792 32.8958 41.4583 32.8958Z"/>
            </svg>
            Technician
        </button>
        <button :class="showAccountant ? 'active' : ''" @click="showMasters = false;showTechnicians = false; showAccountant = true; ShowModerators = false">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_120_9)">
                <path d="M47.5 7.5C48.7614 7.4996 49.9764 7.97602 50.9014 8.83374C51.8263 9.69147 52.3929 10.8671 52.4875 12.125L52.5 12.5V47.5C52.5004 48.7614 52.024 49.9764 51.1663 50.9014C50.3085 51.8263 49.1329 52.3929 47.875 52.4875L47.5 52.5H12.5C11.2386 52.5004 10.0236 52.024 9.09863 51.1663C8.17368 50.3085 7.60711 49.1329 7.5125 47.875L7.5 47.5V12.5C7.4996 11.2386 7.97602 10.0236 8.83374 9.09863C9.69147 8.17368 10.8671 7.60711 12.125 7.5125L12.5 7.5H47.5ZM15.9475 33.4475C15.4788 33.9163 15.2155 34.5521 15.2155 35.215C15.2155 35.8779 15.4788 36.5137 15.9475 36.9825L17.715 38.75L15.9475 40.5175C15.7087 40.7481 15.5183 41.024 15.3872 41.329C15.2562 41.634 15.1873 41.962 15.1844 42.294C15.1815 42.6259 15.2447 42.9551 15.3704 43.2624C15.4961 43.5696 15.6818 43.8488 15.9165 44.0835C16.1512 44.3182 16.4304 44.5039 16.7376 44.6296C17.0449 44.7553 17.3741 44.8185 17.706 44.8156C18.038 44.8127 18.366 44.7438 18.671 44.6128C18.976 44.4817 19.2519 44.2913 19.4825 44.0525L21.25 42.285L23.0175 44.0525C23.489 44.5079 24.1205 44.7599 24.776 44.7542C25.4315 44.7485 26.0585 44.4856 26.522 44.022C26.9856 43.5585 27.2485 42.9315 27.2542 42.276C27.2599 41.6205 27.0079 40.989 26.5525 40.5175L24.785 38.75L26.5525 36.9825C27.0079 36.511 27.2599 35.8795 27.2542 35.224C27.2485 34.5685 26.9856 33.9415 26.522 33.478C26.0585 33.0144 25.4315 32.7515 24.776 32.7458C24.1205 32.7401 23.489 32.9921 23.0175 33.4475L21.25 35.215L19.4825 33.4475C19.0137 32.9788 18.3779 32.7155 17.715 32.7155C17.0521 32.7155 16.4163 32.9788 15.9475 33.4475ZM42.5 39.375H35C34.3628 39.3757 33.7499 39.6197 33.2866 40.0571C32.8232 40.4945 32.5444 41.0924 32.5071 41.7285C32.4697 42.3646 32.6767 42.9909 33.0857 43.4796C33.4946 43.9682 34.0748 44.2822 34.7075 44.3575L35 44.375H42.5C43.1372 44.3743 43.7501 44.1303 44.2134 43.6929C44.6768 43.2555 44.9556 42.6576 44.9929 42.0215C45.0303 41.3854 44.8233 40.7591 44.4143 40.2704C44.0054 39.7818 43.4252 39.4678 42.7925 39.3925L42.5 39.375ZM42.5 33.125H35C34.3628 33.1257 33.7499 33.3697 33.2866 33.8071C32.8232 34.2445 32.5444 34.8424 32.5071 35.4785C32.4697 36.1146 32.6767 36.7409 33.0857 37.2296C33.4946 37.7182 34.0748 38.0322 34.7075 38.1075L35 38.125H42.5C43.1372 38.1243 43.7501 37.8803 44.2134 37.4429C44.6768 37.0055 44.9556 36.4076 44.9929 35.7715C45.0303 35.1354 44.8233 34.5091 44.4143 34.0204C44.0054 33.5318 43.4252 33.2178 42.7925 33.1425L42.5 33.125ZM38.75 16.25C38.087 16.25 37.4511 16.5134 36.9822 16.9822C36.5134 17.4511 36.25 18.087 36.25 18.75V20H35C34.337 20 33.7011 20.2634 33.2322 20.7322C32.7634 21.2011 32.5 21.837 32.5 22.5C32.5 23.163 32.7634 23.7989 33.2322 24.2678C33.7011 24.7366 34.337 25 35 25H36.25V26.25C36.25 26.913 36.5134 27.5489 36.9822 28.0178C37.4511 28.4866 38.087 28.75 38.75 28.75C39.413 28.75 40.0489 28.4866 40.5178 28.0178C40.9866 27.5489 41.25 26.913 41.25 26.25V25H42.5C43.163 25 43.7989 24.7366 44.2678 24.2678C44.7366 23.7989 45 23.163 45 22.5C45 21.837 44.7366 21.2011 44.2678 20.7322C43.7989 20.2634 43.163 20 42.5 20H41.25V18.75C41.25 18.087 40.9866 17.4511 40.5178 16.9822C40.0489 16.5134 39.413 16.25 38.75 16.25ZM25 20H17.5C16.8628 20.0007 16.2499 20.2447 15.7866 20.6821C15.3232 21.1195 15.0444 21.7174 15.0071 22.3535C14.9697 22.9896 15.1767 23.6159 15.5857 24.1046C15.9946 24.5932 16.5748 24.9072 17.2075 24.9825L17.5 25H25C25.6372 24.9993 26.2501 24.7553 26.7134 24.3179C27.1768 23.8805 27.4556 23.2826 27.4929 22.6465C27.5303 22.0104 27.3233 21.3841 26.9143 20.8954C26.5054 20.4068 25.9252 20.0928 25.2925 20.0175L25 20Z"/>
                </g>
                <defs>
                <clipPath id="clip0_120_9">
                <rect width="60" height="60" fill="white"/>
                </clipPath>
                </defs>
            </svg>
            Accountant
        </button>
        <button :class="ShowModerators ? 'active' : ''" @click="showMasters = false;showTechnicians = false; showAccountant = false; ShowModerators = true">
           <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M50.2084 17C49.4638 17.0002 48.7295 17.1741 48.0638 17.5077C47.3982 17.8413 46.8195 18.3255 46.3736 18.9218C45.9278 19.5181 45.6271 20.2102 45.4954 20.9431C45.3638 21.6759 45.4048 22.4294 45.6153 23.1436L38.1836 30.5742C37.2035 30.2477 36.144 30.2477 35.1639 30.5742L29.6544 25.0648C29.8357 24.3573 29.8529 23.6178 29.7045 22.9027C29.5562 22.1877 29.2463 21.516 28.7986 20.939C28.3508 20.3621 27.7771 19.8952 27.1212 19.5739C26.4654 19.2527 25.7447 19.0857 25.0145 19.0857C24.2842 19.0857 23.5635 19.2527 22.9077 19.5739C22.2518 19.8952 21.6781 20.3621 21.2303 20.939C20.7826 21.516 20.4727 22.1877 20.3244 22.9027C20.1761 23.6178 20.1932 24.3573 20.3745 25.0648L11.1601 34.2729C10.1371 33.97 9.04241 34.0168 8.04896 34.4057C7.05552 34.7947 6.22003 35.5036 5.67456 36.4205C5.12909 37.3374 4.90475 38.4099 5.037 39.4686C5.16925 40.5272 5.65055 41.5116 6.40482 42.2661C7.15909 43.0206 8.14331 43.5022 9.20191 43.6348C10.2605 43.7674 11.3331 43.5434 12.2502 42.9982C13.1672 42.4531 13.8764 41.6178 14.2657 40.6245C14.655 39.6311 14.7021 38.5364 14.3996 37.5133L23.4973 28.4167C24.4774 28.7434 25.537 28.7434 26.517 28.4167L32.0275 33.9271C31.8462 34.6346 31.8291 35.3741 31.9774 36.0892C32.1258 36.8042 32.4356 37.4759 32.8834 38.0529C33.3311 38.6298 33.9048 39.0968 34.5607 39.418C35.2166 39.7392 35.9372 39.9062 36.6675 39.9062C37.3978 39.9062 38.1184 39.7392 38.7743 39.418C39.4301 39.0968 40.0039 38.6298 40.4516 38.0529C40.8993 37.4759 41.2092 36.8042 41.3575 36.0892C41.5059 35.3741 41.4888 34.6346 41.3075 33.9271L48.8547 26.3841C49.5136 26.5785 50.2066 26.6288 50.8866 26.5318C51.5667 26.4348 52.2179 26.1926 52.7962 25.8217C53.3744 25.4508 53.8661 24.9599 54.2379 24.3822C54.6096 23.8046 54.8528 23.1537 54.9509 22.4738C55.049 21.7939 54.9997 21.1008 54.8063 20.4416C54.6129 19.7825 54.28 19.1726 53.8302 18.6534C53.3804 18.1342 52.8241 17.7178 52.1992 17.4326C51.5743 17.1473 50.8953 16.9998 50.2084 17Z"/>
            </svg>
            Moderators
        </button>
    </div>
    <section class="row-2 table_wrapper" v-if="showMasters" >
        <div class="head">
            <h1>Masters List</h1>
            <div class="pagination"></div>
            <div class="flex-center">
                <button class="add-btn" @click="admin_title = 'Master';showAddAdmin = true">Add <i class="bx bx-plus"></i></button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Master" class="input" v-model="Master_search_words" @input="getAdmins()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="masters && masters.length > 0" v-for="admin in masters" :key="admin.id">
                    <td>@{{admin.full_name}}</td>
                    <td>@{{admin.email}}</td>
                    <td>@{{admin.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="getEditValues(admin);admin_data = admin;showEditAdmin = true;"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="this.delete(admin.id, admin.full_name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!masters || masters.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no Masters!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper" v-if="showTechnicians" >
        <div class="head">
            <h1>Technicians List</h1>
            <div class="pagination"></div>
            <div class="flex-center">
                <button class="add-btn" @click="admin_title = 'Technician';showAddAdmin = true">Add <i class="bx bx-plus"></i></button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Technician" class="input" v-model="Technician_search_words" @input="getAdmins()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="technicians && technicians.length > 0" v-for="admin in technicians" :key="admin.id">
                    <td>@{{admin.full_name}}</td>
                    <td>@{{admin.email}}</td>
                    <td>@{{admin.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="getEditValues(admin);admin_data = admin;showEditAdmin = true;"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="this.delete(admin.id, admin.full_name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!technicians || technicians.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no technicians!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper" v-if="showAccountant" >
        <div class="head">
            <h1>Accountants List</h1>
            <div class="pagination"></div>
            <div class="flex-center">
                <button class="add-btn" @click="admin_title = 'Accountant';showAddAdmin = true">Add <i class="bx bx-plus"></i></button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Accountant" class="input" v-model="Accountant_search_words" @input="getAdmins()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="accountant && accountant.length > 0" v-for="admin in accountant" :key="admin.id">
                    <td>@{{admin.full_name}}</td>
                    <td>@{{admin.email}}</td>
                    <td>@{{admin.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="getEditValues(admin);admin_data = admin;showEditAdmin = true;"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="this.delete(admin.id, admin.full_name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!accountant || accountant.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no accountant!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section class="row-2 table_wrapper" v-if="ShowModerators" >
        <div class="head">
            <h1>Moderators List</h1>
            <div class="pagination"></div>
            <div class="flex-center">
                <button class="add-btn" @click="admin_title = 'Moderator';showAddAdmin = true">Add <i class="bx bx-plus"></i></button>
                <div class="form-group search">
                    <input type="text" name="search" id="search" placeholder="Search Moderator" class="input" v-model="Moderator_search_words" @input="getAdmins()">
                    <i class='bx bx-search'></i>
                </div>
            </div>
        </div>
        <table class="normal_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Controls</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr v-if="moderators && moderators.length > 0" v-for="admin in moderators" :key="admin.id">
                    <td>@{{admin.full_name}}</td>
                    <td>@{{admin.email}}</td>
                    <td>@{{admin.phone}}</td>
                    <td>
                        <div class="btns flex-center">
                            <button class="button success" @click="getEditValues(admin);admin_data = admin;showEditAdmin = true;"><i class='bx bx-edit'></i></button>
                            <button class="button danger" @click="this.delete(admin.id, admin.full_name)"><i class='bx bx-trash'></i></button>
                        </div>
                    </td>
                </tr>
                <tr v-if="!moderators || moderators.length == 0" style="font-size: 20px; font-weight: 700; text-align: center">
                    <td colspan="5"><h2>There is no moderators!</h2></td>
                </tr>
            </tbody>
        </table>
    </section>


    <div class="hide-content" @click="showAddAdmin = false" v-if="showAddAdmin | showEditAdmin"></div>
    <div class="pop-up show_request_details_wrapper card" v-if="showAddAdmin">
        <h1>Add @{{ admin_title }}</h1>
        <br>
        <div class="form-group">
            <input type="text" name="name" @keydown.enter="add" id="name" class="form-control input" v-model="name" placeholder="Name">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="email" @keydown.enter="add" id="email" class="form-control input" v-model="email" placeholder="Email">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="phone" @keydown.enter="add" id="phone" class="form-control input" v-model="phone" placeholder="Phone">
        </div>
        <br>
        <div class="form-group">
            <input type="text" name="password" @keydown.enter="add" id="password" class="form-control input" v-model="password" placeholder="Password">
        </div>
        <br>
        <div class="btns flex-center">
            <button class="button secondary" @click="showAddAdmin = false;admin_data = null">Cancel</button>
            <button class="button success" @click="add()">Add</button>
        </div>
    </div>
    <div class="pop-up show_request_details_wrapper card" v-if="showEditAdmin && admin_data">
        <h1>edit @{{ admin_data.full_name }} information</h1>
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
            <select name="role" id="role" class="form-control input" v-model="to_edit_role" placeholder="Role">
                <option value="Master">Master</option>
                <option value="Technician">Technician</option>
                <option value="Accountant">Accountant</option>
                <option value="Moderators">Moderators</option>
            </select>
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
            <button class="button secondary" @click="showEditAdmin = false">Cancel</button>
            <button class="button success" @click="edit()">edit</button>
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
        masters: null,
        technicians: null,
        accountant: null,
        moderators: null,
        showMasters: true,
        showTechnicians: false,
        showAccountant: false,
        ShowModerators: false,
        showAddAdmin: false,
        admin_title: null,
        name: null,
        email: null,
        phone: null,
        password: null,
        to_edit_name: null,
        to_edit_email: null,
        to_edit_phone: null,
        to_edit_password: null,
        to_edit_id: null,
        to_edit_password_confirmation: null,
        showEditAdmin: false,
        admin_data: null,

        Master_search_words: null,
        Technician_search_words: null,
        Accountant_search_words: null,
        Moderator_search_words: null,
    }
  },
  methods: {
    getEditValues(admin) {
        this.to_edit_name = admin.full_name;
        this.to_edit_email = admin.email;
        this.to_edit_phone = admin.phone;
        this.to_edit_role = admin.role;
        this.to_edit_id = admin.id;
    },
    async getAdmins() {
        if(!this.Master_search_words && !this.Technician_search_words && !this.Accountant_search_words && !this.Moderator_search_words)
            $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('get.admins') }}`,{
                Master_search_words: this.Master_search_words,
                Technician_search_words: this.Technician_search_words,
                Accountant_search_words: this.Accountant_search_words,
                Moderator_search_words: this.Moderator_search_words,
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            );
            if (response.data.status === true) {
                document.getElementById('errors').innerHTML = ''
                $('.loader').fadeOut()
                this.masters = response.data.data.masters
                this.technicians = response.data.data.technicians
                this.accountant = response.data.data.accountant
                this.moderators = response.data.data.moderators
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
    async add() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('admin.add') }}`, {
                name: this.name,
                email: this.email,
                phone: this.phone,
                password: this.password,
                role: this.admin_title,
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
    async edit() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('admin.update') }}`, {
                admin_id: this.to_edit_id,
                full_name: this.to_edit_name,
                email: this.to_edit_email,
                phone: this.to_edit_phone,
                password: this.to_edit_password,
                password_confirmation: this.to_edit_password_confirmation,
                role: this.to_edit_role,
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
    async delete(id, name) {
        if (confirm("Are you sure you want to delete " + name + " account")) {
        $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route('admin.delete') }}`, {
                    admin_id: id,
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
    this.getAdmins()
    $('.loader').fadeOut()
  },
  mounted() {
  },
}).mount('#admins_wrapper')
</script>
@endsection