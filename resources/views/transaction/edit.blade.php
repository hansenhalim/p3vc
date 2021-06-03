@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-md-7">
          <a class="btn btn-link mb-2" href="{{ route('customers.index') }}">&lt;&lt; Return</a>
          <div class="card">
            <div class="card-header">Customer Create</div>
            <form class="form-horizontal" action="{{ route('customers.update', ['customer' => $customer->id]) }}" method="post">
              @csrf
              @method('PUT')
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show">
                    {!! session('status') !!}
                    <button type="button" class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                  </div>
                @endif
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Name <span class="text-danger">*</span></label>
                  <div class="col">
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                      value="{{ old('name', $customer->name) }}">
                    @error('name')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Phone <span class="text-danger">*</span></label>
                  <div class="col">
                    <input class="form-control @error('phone_number') is-invalid @enderror" type="text"
                      name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}">
                    @error('phone_number')
                      <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3"></div>
                  <div class="col form-check">
                    <input class="form-check-input ml-0" type="checkbox" id="stay" name="stay" @if (session('stay') || old('stay')) checked @endif>
                    <label class="form-check-label ml-3" for="stay">Submit another</label>
                  </div>
                </div>
              </div>
              <div class="card-footer d-flex justify-content-end">
                <button class="btn btn-link text-dark" type="reset">Reset</button>
                <button class="btn btn-primary" type="submit">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
