<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('/dashboard/images/icon.png.jpg') }}?v={{time()}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('/dashboard/css/admin.css') }}?v={{time()}}" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Dashboard | @yield('title')</title>
      <style>
    * {
      font-family: 'Cairo', sans-serif !important;
    }
    .left-sidebar {
      border-left: 1px solid rgb(229,234,239);
    }
    img {
      max-width: 300px
    }
    .pop-up {
      margin: auto;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 99999999;
    }
    .hide-content {
      width: 100vw;
      height: 100vh;
      background-color: #0000004d;
      position: fixed;
      top: 0;
      left: 0;
      z-index: calc(99999993 - 1);
    }
    #errors {
      position: fixed;
      top: 1.25rem;
      right: 1.25rem;
      display: flex;
      flex-direction: column;
      max-width: calc(100% - 1.25rem * 2);
      gap: 1rem;
      z-index: 99999999999999999999;

    }
    #errors >* {
      width: 100%;
      color: #fff;
      font-size: 1.1rem;
      padding: 1rem;
      border-radius: 1rem;
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

    #errors .error {
      background: #e41749;
    }
    #errors .success {
      background: #12c99b;
    }
    .loader {
      width: 100vw;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      justify-content: center;
      align-items: center;
      z-index: 9999999999999999999999999999999999 !important;
      background: #fafafa !important;
      backdrop-filter: blur(1px);
      display: flex
    }
    .custom-loader {
      --d:22px;
      width: 4px;
      height: 4px;
      border-radius: 50%;
      color: #365FA0;
      box-shadow: 
        calc(1*var(--d))      calc(0*var(--d))     0 0,
        calc(0.707*var(--d))  calc(0.707*var(--d)) 0 1px,
        calc(0*var(--d))      calc(1*var(--d))     0 2px,
        calc(-0.707*var(--d)) calc(0.707*var(--d)) 0 3px,
        calc(-1*var(--d))     calc(0*var(--d))     0 4px,
        calc(-0.707*var(--d)) calc(-0.707*var(--d))0 5px,
        calc(0*var(--d))      calc(-1*var(--d))    0 6px;
      animation: s7 1s infinite steps(8);
    }

    @keyframes s7 {
      100% {transform: rotate(1turn)}
    }

    .show {
      display: block !important;
    }
  </style>
</head>
<body>
    <div id="errors"></div>
    <div class="loader">
        <div class="custom-loader"></div>
    </div>

    @if(Auth::guard("admin")->user()->role === "Master")
    <nav>
        <a href="{{ route('admin.home') }}" class="@yield('home_active')"><img src="{{ asset('/dashboard/images/adaptive-icon.png') }}" alt="fentec logo"></a>
        <a href="{{ route('scooters.manage') }}" class="@yield('scooters_active')">
            <svg class="scooter_icon" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M53.3333 56.6667C53.3333 58.4348 54.0357 60.1305 55.2859 61.3807C56.5362 62.631 58.2319 63.3333 60 63.3333C61.7681 63.3333 63.4638 62.631 64.714 61.3807C65.9643 60.1305 66.6666 58.4348 66.6666 56.6667C66.6666 54.8986 65.9643 53.2029 64.714 51.9526C63.4638 50.7024 61.7681 50 60 50C58.2319 50 56.5362 50.7024 55.2859 51.9526C54.0357 53.2029 53.3333 54.8986 53.3333 56.6667ZM13.3333 56.6667C13.3333 58.4348 14.0357 60.1305 15.2859 61.3807C16.5362 62.631 18.2319 63.3333 20 63.3333C21.7681 63.3333 23.4638 62.631 24.714 61.3807C25.9643 60.1305 26.6666 58.4348 26.6666 56.6667C26.6666 54.8986 25.9643 53.2029 24.714 51.9526C23.4638 50.7024 21.7681 50 20 50C18.2319 50 16.5362 50.7024 15.2859 51.9526C14.0357 53.2029 13.3333 54.8986 13.3333 56.6667Z" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M26.6666 56.6667H43.3333C43.9883 52.4816 45.9553 48.6126 48.9506 45.6173C51.9459 42.622 55.8149 40.6551 60 40V23.3334C60 21.5652 59.2976 19.8696 58.0473 18.6193C56.7971 17.3691 55.1014 16.6667 53.3333 16.6667H50" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        <a href="{{ route('prev.users') }}" class="@yield('users_active')">
            <svg  viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M28.0018 38.0024C31.1847 38.0024 34.2373 36.738 36.4879 34.4873C38.7386 32.2367 40.003 29.1841 40.003 26.0012C40.003 22.8183 38.7386 19.7657 36.4879 17.5151C34.2373 15.2644 31.1847 14 28.0018 14C24.8189 14 21.7663 15.2644 19.5157 17.5151C17.265 19.7657 16.0006 22.8183 16.0006 26.0012C16.0006 29.1841 17.265 32.2367 19.5157 34.4873C21.7663 36.738 24.8189 38.0024 28.0018 38.0024ZM55.0045 38.0024C57.3917 38.0024 59.6811 37.0541 61.3691 35.3661C63.0571 33.6781 64.0054 31.3887 64.0054 29.0015C64.0054 26.6143 63.0571 24.3249 61.3691 22.6369C59.6811 20.9489 57.3917 20.0006 55.0045 20.0006C52.6173 20.0006 50.3279 20.9489 48.6399 22.6369C46.9519 24.3249 46.0036 26.6143 46.0036 29.0015C46.0036 31.3887 46.9519 33.6781 48.6399 35.3661C50.3279 37.0541 52.6173 38.0024 55.0045 38.0024ZM17.5008 44.003C13.3603 44.003 10 47.3633 10 51.5037C10 51.5037 10 65.0051 28.0018 65.0051C42.2712 65.0051 45.2295 56.5203 45.8416 53.0039C46.0036 52.0858 46.0036 51.5037 46.0036 51.5037C46.0036 47.3633 42.6433 44.003 38.5029 44.003H17.5008ZM51.9802 53.604C51.9111 54.6918 51.7301 55.7696 51.4401 56.8203C51.0621 58.1584 50.438 59.7606 49.3879 61.4107C51.2292 61.8233 53.1117 62.0226 54.9985 62.0048C70 62.0048 70 51.5037 70 51.5037C70 47.3633 66.6397 44.003 62.4992 44.003H49.724C51.1641 46.1512 51.9982 48.7255 51.9982 51.5037V53.0039L51.9802 53.604Z" fill="white"/>
            </svg>
        </a>
        <a href="{{ route("statistics.manage") }}" class="@yield('reports_active')">
            <svg  viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M70 19.375C70 21.4844 68.3242 23.4297 65.5 25C62.0898 26.8867 57.0039 28.2227 51.168 28.6211C50.7344 28.4102 50.3008 28.2109 49.8438 28.0352C45.2266 26.1016 39.0859 25 32.5 25C31.5273 25 30.5781 25.0234 29.6289 25.0703L29.5 25C26.6758 23.4297 25 21.4844 25 19.375C25 14.1953 35.0781 10 47.5 10C59.9219 10 70 14.1953 70 19.375ZM28.832 28.8789C30.0273 28.7969 31.2578 28.75 32.5 28.75C39.7891 28.75 46.2578 30.1914 50.3711 32.4297C53.2773 34.0117 55 35.9805 55 38.125C55 38.5938 54.918 39.0508 54.7539 39.4961C54.2148 41.043 52.7617 42.4609 50.6523 43.6562C50.6406 43.668 50.6172 43.668 50.6055 43.6797C50.5703 43.7031 50.5352 43.7148 50.5 43.7383C46.3984 46.0117 39.8594 47.4883 32.5 47.4883C25.5156 47.4883 19.2695 46.1641 15.1328 44.0781C14.9102 43.9727 14.6992 43.8555 14.4883 43.7383C11.6758 42.1797 10 40.2344 10 38.125C10 34.0469 16.2578 30.5664 25 29.2891C26.2305 29.1133 27.5078 28.9727 28.832 28.8789ZM58.75 38.125C58.75 35.5586 57.5078 33.4492 55.9258 31.8672C59.2422 31.3516 62.2773 30.5312 64.8555 29.4648C66.7656 28.668 68.5469 27.6836 70 26.4766V30.625C70 32.8867 68.0664 34.9727 64.8672 36.5898C63.1562 37.457 61.0703 38.1953 58.7266 38.7578C58.7383 38.5469 58.75 38.3477 58.75 38.1367V38.125ZM55 49.375C55 51.4844 53.3242 53.4297 50.5 55C50.2891 55.1172 50.0781 55.2227 49.8555 55.3398C45.7305 57.4258 39.4844 58.75 32.5 58.75C25.1406 58.75 18.6016 57.2734 14.5 55C11.6758 53.4297 10 51.4844 10 49.375V45.2266C11.4648 46.4336 13.2344 47.418 15.1445 48.2148C19.7734 50.1484 25.9141 51.25 32.5 51.25C39.0859 51.25 45.2266 50.1484 49.8555 48.2148C50.7695 47.8398 51.6484 47.4062 52.4805 46.9375C53.1953 46.5391 53.8633 46.0938 54.4961 45.625C54.6719 45.4961 54.8359 45.3555 55 45.2266V49.375ZM58.75 49.375V42.5898C60.9766 42.0977 63.0273 41.4766 64.8555 40.7148C66.7656 39.918 68.5469 38.9336 70 37.7266V41.875C70 43.1055 69.4141 44.3359 68.2539 45.4961C66.3438 47.4062 62.9805 48.9766 58.7266 49.9961C58.7383 49.7969 58.75 49.5859 58.75 49.375ZM32.5 62.5C39.0859 62.5 45.2266 61.3984 49.8555 59.4648C51.7656 58.668 53.5469 57.6836 55 56.4766V60.625C55 65.8047 44.9219 70 32.5 70C20.0781 70 10 65.8047 10 60.625V56.4766C11.4648 57.6836 13.2344 58.668 15.1445 59.4648C19.7734 61.3984 25.9141 62.5 32.5 62.5Z" fill="white"/>
            </svg>
        </a>
        <a href="{{ route('admins.manage') }}" class="@yield('admins_active')">
            <svg  viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M52.2946 54.8865C53.554 54.8865 54.625 54.4452 55.5077 53.5626C56.3903 52.68 56.8306 51.6099 56.8286 50.3525C56.8286 49.0931 56.3873 48.022 55.5046 47.1394C54.622 46.2568 53.552 45.8165 52.2946 45.8185C51.0351 45.8185 49.9641 46.2598 49.0815 47.1424C48.1989 48.0251 47.7586 49.0951 47.7606 50.3525C47.7606 51.6119 48.2019 52.683 49.0845 53.5656C49.9671 54.4482 51.0371 54.8885 52.2946 54.8865ZM52.2946 63.9545C53.8563 63.9545 55.292 63.5887 56.6019 62.8572C57.9117 62.1258 58.9696 61.1565 59.7757 59.9494C58.6673 59.2945 57.4835 58.7908 56.224 58.4381C54.9646 58.0855 53.6548 57.9092 52.2946 57.9092C50.9344 57.9092 49.6246 58.0855 48.3651 58.4381C47.1057 58.7908 45.9218 59.2945 44.8135 59.9494C45.6195 61.1585 46.6775 62.1288 47.9873 62.8603C49.2971 63.5918 50.7329 63.9565 52.2946 63.9545ZM52.2946 69.9998C48.1132 69.9998 44.5485 68.5257 41.6004 65.5776C38.6523 62.6295 37.1793 59.0658 37.1813 54.8865C37.1813 50.7051 38.6553 47.1404 41.6034 44.1923C44.5515 41.2442 48.1152 39.7712 52.2946 39.7732C56.4759 39.7732 60.0406 41.2472 62.9887 44.1953C65.9368 47.1434 67.4099 50.7072 67.4079 54.8865C67.4079 59.0678 65.9338 62.6326 62.9857 65.5807C60.0376 68.5288 56.4739 70.0018 52.2946 69.9998ZM37.1813 69.9998C30.1788 68.2366 24.3974 64.2184 19.8373 57.9454C15.2771 51.6724 12.998 44.7082 13 37.0528V22.7707C13 21.5113 13.3657 20.3778 14.0972 19.3702C14.8287 18.3627 15.7728 17.6322 16.9295 17.1788L35.0654 10.3778C35.7707 10.1259 36.476 10 37.1813 10C37.8866 10 38.5918 10.1259 39.2971 10.3778L57.4331 17.1788C58.5918 17.6322 59.5369 18.3627 60.2683 19.3702C60.9998 20.3778 61.3646 21.5113 61.3625 22.7707V35.7682C60.0527 35.1133 58.5787 34.6095 56.9404 34.2568C55.3021 33.9042 53.7535 33.7279 52.2946 33.7279C46.4508 33.7279 41.4634 35.7934 37.3324 39.9243C33.2014 44.0553 31.136 49.0427 31.136 54.8865C31.136 58.0099 31.7284 60.8311 32.9133 63.3499C34.0982 65.8688 35.5964 68.0602 37.408 69.9242C37.3576 69.9242 37.3203 69.9373 37.2961 69.9635C37.272 69.9897 37.2337 70.0018 37.1813 69.9998Z" fill="white"/>
            </svg>
        </a>
    </nav>
    @endif
    <main>
        @yield('content')
    </main>
      <script src="{{ asset('/libs/vue.js') }}"></script>
    <script src="{{ asset('/libs/jquery.js') }}"></script>
    <script src="{{ asset('/libs/swiper.js') }}"></script>
    <script src="{{ asset('/libs/axios.js') }}"></script>
    @yield('scripts')
</body>
</html>