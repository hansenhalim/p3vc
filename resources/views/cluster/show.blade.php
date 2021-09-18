@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <a
        class="btn btn-sm btn-secondary font-weight-bold mb-2"
        href="{{ route('clusters.index') }}"
      ><i class="cil-chevron-circle-left-alt align-text-top"></i> Return</a>
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 my-1 text-nowrap">Cluster Show</div>
            </div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
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
