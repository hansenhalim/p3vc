@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <x-return-button></x-return-button>
          <div class="card">
            <x-card-header>Create Customer</x-card-header>
            <form
              class="form-horizontal"
              action="{{ route('customers.store') }}"
              method="post"
            >
              @csrf
              <div class="card-body">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Name</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('name') is-invalid @enderror"
                      type="text"
                      name="name"
                      value="{{ old('name') }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Phone</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('phone_number') is-invalid @enderror"
                      type="text"
                      name="phone_number"
                      value="{{ old('phone_number') }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('phone_number')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="d-flex justify-content-end">
                  <button
                    type="submit"
                    class="btn btn-warning"
                  ><i class="cil-paper-plane align-text-top"></i>&nbsp;Submit</button>
                </div>
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
