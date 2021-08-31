<!DOCTYPE html>
<!--
* CoreUI Free Laravel Bootstrap Admin Template
* @version v2.0.1
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">

<head>
  <base href="./">
  <meta charset="utf-8">
  <meta
    http-equiv="X-UA-Compatible"
    content="IE=edge"
  >
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
  >
  <meta
    name="description"
    content="P3VC - Paguyuban Pengelolaan Perumahan Villa Citra"
  >
  <meta
    name="author"
    content="Hansen Halim"
  >
  <meta
    name="keyword"
    content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard"
  >
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link
    rel="apple-touch-icon"
    sizes="57x57"
    href="assets/favicon/apple-icon-57x57.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="60x60"
    href="assets/favicon/apple-icon-60x60.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="72x72"
    href="assets/favicon/apple-icon-72x72.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="76x76"
    href="assets/favicon/apple-icon-76x76.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="114x114"
    href="assets/favicon/apple-icon-114x114.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="120x120"
    href="assets/favicon/apple-icon-120x120.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="144x144"
    href="assets/favicon/apple-icon-144x144.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="152x152"
    href="assets/favicon/apple-icon-152x152.png"
  >
  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="assets/favicon/apple-icon-180x180.png"
  >
  <link
    rel="icon"
    type="image/png"
    sizes="192x192"
    href="assets/favicon/android-icon-192x192.png"
  >
  <link
    rel="icon"
    type="image/png"
    sizes="32x32"
    href="assets/favicon/favicon-32x32.png"
  >
  <link
    rel="icon"
    type="image/png"
    sizes="96x96"
    href="assets/favicon/favicon-96x96.png"
  >
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="assets/favicon/favicon-16x16.png"
  >
  <link
    rel="manifest"
    href="assets/favicon/manifest.json"
  >
  <meta
    name="msapplication-TileColor"
    content="#ffffff"
  >
  <meta
    name="msapplication-TileImage"
    content="assets/favicon/ms-icon-144x144.png"
  >
  <meta
    name="theme-color"
    content="#ffffff"
  >
  <!-- Icons-->
  <link
    href="{{ asset('css/free.min.css') }}"
    rel="stylesheet"
  >
  {{-- <link
    href="{{ asset('css/brand.min.css') }}"
    rel="stylesheet"
  >
  <link
    href="{{ asset('css/flag.min.css') }}"
    rel="stylesheet"
  > --}}
  <!-- Main styles for this application-->
  <link
    href="{{ asset('css/style.css') }}"
    rel="stylesheet"
  >
  <link
    href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"
    rel="stylesheet"
  >

  @yield('css')

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script
    async
    src="https://www.googletagmanager.com/gtag/js?id=G-FY0HC3B1E6"
  ></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-FY0HC3B1E6');
  </script>

  <link
    href="{{ asset('css/coreui-chartjs.css') }}"
    rel="stylesheet"
  >

  <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <style>
    .page-link {
      border: none;
      color: #888888;
      font-size: 80%;
    }

    .page-link:hover {
      color: #3c4b64;
    }

    .page-link:focus {
      box-shadow: 0 0 0 0.2rem rgb(242 176 56 / 25%);
    }

    .page-item.active .page-link {
      background-color: transparent;
      color: #3c4b64;
      font-weight: bold;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #f5f5f5;
    }

    .dropdown-item.active,
    .dropdown-item:active {
      color: black;
      background-color: #f2b038;
    }

    .custom-select:focus {
      border-color: #ffdb9c;
      box-shadow: 0 0 0 0.2rem rgb(242 176 56 / 25%);
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgb(242 176 56 / 25%);
    }

    .c-sidebar .c-sidebar-nav-dropdown-toggle:hover,
    .c-sidebar .c-sidebar-nav-link:hover {
      background: #f9b115;
    }

    .btn-warning, .btn-warning:hover, .btn-warning:active {
      color: black !important;
      font-weight: bold;
    }

    @media screen and (max-width: 576px) {
      li.page-item {
        display: none;
      }

      .page-item:first-child,
      .page-item:nth-child(2),
      .page-item:nth-last-child(2),
      .page-item:last-child,
      .page-item.active,
      .page-item.disabled {
        display: block;
      }
    }

  </style>

</head>



<body class="c-app">
  <div
    class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show"
    id="sidebar"
  >

    @include('dashboard.shared.nav-builder')
    @include('dashboard.shared.header')
    <div class="c-body">
      <main class="c-main">
        @yield('content')
      </main>
      @include('dashboard.shared.footer')
    </div>
  </div>

  <!-- CoreUI and necessary plugins-->
  <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
  <script src="{{ asset('js/coreui-utils.js') }}"></script>
  @yield('javascript')

</body>

</html>
