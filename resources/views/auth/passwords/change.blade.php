@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-md-8">
          <x-return-button></x-return-button>
          <div class="card">
            <x-card-header>Change Password</x-card-header>
            <div class="card-body">
              <x-alert></x-alert>
              <form
                action="{{ route('passwords.update') }}"
                method="post"
              >
                @csrf
                <input class="d-none" type="email" name="email">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">Current Password</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('current_password') is-invalid @enderror"
                      type="password"
                      name="current_password"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('current_password')
                      <div class="invalid-feedback">Invalid old password.</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">New Password</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('password') is-invalid @enderror"
                      type="password"
                      name="password"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">Confirm Password</label>
                  <div class="col">
                    <input
                      class="form-control border-0"
                      type="password"
                      name="password_confirmation"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                  </div>
                </div>
                <div class="d-flex justify-content-end">
                  <button
                    type="submit"
                    class="btn btn-warning"
                  ><i class="cil-save align-text-top"></i> Save</button>
                </div>
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
