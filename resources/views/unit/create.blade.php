@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-md-6">
            <a class="btn btn-link text-dark mb-2" href="{{ route('units.index') }}"><i class="cil-caret-left"></i>Return</a>
            <div class="card">
            <div class="card-header">Unit Create</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
              <form class="form-horizontal" action="{{ route('units.store') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Name</label>
                  <div class="col-md-9">
                    <input class="form-control" type="text" name="name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Area</label>
                  <div class="col-md-9">
                    <input class="form-control" type="text" name="area_sqm">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Cluster</label>
                  <div class="col-md-9">
                    <select class="form-control" name="cluster_id">
                      <option value="">- PLEASE SELECT -</option>
                      @foreach ($clusters as $cluster)
                        <option value="{{ $cluster->id }}">{{ $cluster->name }} ({{ number_format($cluster->prices->last()->cost) }}/{{ $cluster->prices->last()->per }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Customer</label>
                  <div class="col-md-9">
                    <select class="form-control" name="customer_id">
                      <option value="">- PLEASE SELECT -</option>
                      @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-dark" type="submit"> Submit</button>
              <button class="btn btn-link text-dark" type="reset"> Reset</button>
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
