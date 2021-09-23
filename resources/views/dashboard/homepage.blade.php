@extends('dashboard.base')

@section('content')

          <div class="container-fluid">
            <div class="fade-in">
              <h1><strong>Hello,</strong> {{ Auth::user()->name }}</h1>
              <h4>More features coming soon! Have a nice day.</h4>
            </div>
          </div>

@endsection

@section('javascript')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
