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
  <!-- Icons-->
  <link
    href="{{ asset('css/free.min.css') }}"
    rel="stylesheet"
  >
  <!-- Main styles for this application-->
  <link
    href="{{ asset('css/style.css') }}"
    rel="stylesheet"
  >

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

</head>

<body class="c-app flex-row align-items-center">

  @yield('content')

  <!-- CoreUI and necessary plugins-->
  <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>

  @yield('javascript')

</body>

</html>
