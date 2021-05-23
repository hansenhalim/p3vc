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
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
              <form class="form-horizontal" action="{{ route('customers.store') }}" method="post">
                @csrf
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Name</label>
                  <div class="col-md-9">
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}">
                    @error('name')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Id Link</label>
                  <div class="col-md-9">
                    <input class="form-control @error('idlink') is-invalid @enderror" type="text" name="idlink" value="{{ old('idlink') }}">
                    @error('idlink')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Phone</label>
                  <div class="col-md-9">
                    <input class="form-control @error('phone_number') is-invalid @enderror" type="text" name="phone_number" value="{{ old('phone_number') }}">
                    @error('phone_number')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col-md-9 form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck" name="stay" checked>
                    <label class="form-check-label" for="gridCheck">
                      Submit another
                    </label>
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
