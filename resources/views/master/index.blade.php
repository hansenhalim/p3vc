@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-md-8">
          <div class="card">
            <x-card-header><code>unapprove_trx</code></x-card-header>
            <div class="card-body">
              <x-alert></x-alert>
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <form action="{{ route('master.unapprove') }}" method="post">
                @csrf
                @method('PUT')
                <label>transaction_id</label>
                <input type="text" name="transaction_id" value="{{ request()->get('transaction_id') }}">
                <input type="submit" value="send">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
