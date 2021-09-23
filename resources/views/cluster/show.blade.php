@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <x-return-button href="{{ route('clusters.index') }}"></x-return-button>
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <div class="card">
            <x-card-header>Cluster Show</x-card-header>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col">
                  <input
                    class="form-control"
                    type="text"
                    value="{{ $cluster->name }}"
                    disabled
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Price</label>
                <div class="col">
                  <input
                    class="form-control"
                    type="text"
                    value="{{ number_format($cluster->cost) }} / {{ $cluster->per }}"
                    disabled
                  >
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('javascript')

@endsection
