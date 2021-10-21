@extends('dashboard.authBase')

@section('content')

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card mx-4">
          <div class="card-body p-4">
            <form
              method="POST"
              action="{{ route('register') }}"
            >
              @csrf
              <h1>{{ __('Register') }}</h1>
              <p class="text-muted">Create your account</p>
              <div class="row">
                <div class="col">
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <svg class="c-icon">
                          <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                        </svg>
                      </span>
                    </div>
                    <input
                      class="form-control"
                      type="text"
                      placeholder="{{ __('Name') }}"
                      name="name"
                      value="{{ old('name') }}"
                      required
                      autofocus
                    >
                  </div>
                </div>
                <div class="col-4">
                  <select name="role" class="custom-select mb-4">
                    <option value="supervisor">Supervisor</option>
                    <option value="operator">Operator</option>
                    <option value="master" disabled>Master</option>
                  </select>
                </div>
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <svg class="c-icon">
                      <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-envelope-open"></use>
                    </svg>
                  </span>
                </div>
                <input
                  class="form-control"
                  type="text"
                  placeholder="{{ __('E-Mail Address') }}"
                  name="email"
                  value="{{ old('email') }}"
                  required
                >
              </div>

              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <svg class="c-icon">
                      <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-lock-locked"></use>
                    </svg>
                  </span>
                </div>
                <input
                  class="form-control"
                  type="password"
                  placeholder="{{ __('Password') }}"
                  name="password"
                  required
                >
              </div>

              <div class="input-group mb-4">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <svg class="c-icon">
                      <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-lock-locked"></use>
                    </svg>
                  </span>
                </div>
                <input
                  class="form-control"
                  type="password"
                  placeholder="{{ __('Confirm Password') }}"
                  name="password_confirmation"
                  required
                >
              </div>

              <button
                class="btn btn-block btn-warning"
                type="submit"
              >{{ __('Register') }}</button>
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
