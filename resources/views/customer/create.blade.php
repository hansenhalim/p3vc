@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-md-6">
          <a class="btn btn-link mb-2" href="{{ route('customers.index') }}">&lt;&lt; Return</a>
          <div class="card">
            <div class="card-header">Customer Create</div>
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
                  <label class="col-md-3 col-form-label">Id Link</label>
                  <div class="col-md-9">
                    <input class="form-control" type="text" name="idlink">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Phone</label>
                  <div class="col-md-9">
                    <input class="form-control" type="text" name="area_sqm">
                  </div>
                </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary" type="submit"> Submit</button>
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
