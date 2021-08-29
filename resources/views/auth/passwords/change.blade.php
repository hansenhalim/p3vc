@extends('dashboard.base')

@section('content')

  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-md-8">
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 text-nowrap">Change Password</div>
            </div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
              <form
                action="{{ route('passwords.update') }}"
                method="post"
              >
                @csrf
                <div class="form-group row">
                  <label class="col-md-4 col-form-label">Current Password</label>
                  <div class="col">
                    <input
                      class="form-control @error('current_password') is-invalid @enderror"
                      type="password"
                      name="current_password"
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
                      class="form-control @error('password') is-invalid @enderror"
                      type="password"
                      name="password"
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
                      class="form-control"
                      type="password"
                      name="password_confirmation"
                    >
                  </div>
                </div>
                <button type="submit" class="btn btn-warning font-weight-bold" style="color: black">Save</button>
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
