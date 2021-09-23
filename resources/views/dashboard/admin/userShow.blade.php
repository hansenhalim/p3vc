@extends('dashboard.base')

@section('content')

  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3">
          <div class="card">
            <x-card-header>User Show</x-card-header>
            <div class="card-body">
              <h4>Name: {{ $user->name }}</h4>
              <h4>E-mail: {{ $user->email }}</h4>
              <a
                href="{{ route('users.index') }}"
                class="btn btn-block btn-primary"
              >{{ __('Return') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection


@section('javascript')

@endsection
