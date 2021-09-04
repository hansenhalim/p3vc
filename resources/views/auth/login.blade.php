@extends('dashboard.authBase')

@section('content')

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
              <h1>Login</h1>
              <p class="text-muted">Sign In to your account</p>
              <form
                method="POST"
                action="{{ route('login') }}"
              >
                @csrf
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning btn-warning border-0">
                      <svg class="c-icon">
                        <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                      </svg>
                    </span>
                  </div>
                  <input
                    class="form-control border-0"
                    style="background-color: rgba(0,0,21,.05);"
                    type="text"
                    placeholder="{{ __('E-Mail Address') }}"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                  >
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning btn-warning border-0">
                      <svg class="c-icon">
                        <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-lock-locked">
                        </use>
                      </svg>
                    </span>
                  </div>
                  <input
                    class="form-control border-0"
                    style="background-color: rgba(0,0,21,.05);"
                    type="password"
                    placeholder="{{ __('Password') }}"
                    name="password"
                    required
                  >
                </div>
                <div class="d-flex justify-content-end">
                  <button
                    type="submit"
                    class="btn btn-warning px-4 flex-grow-1 flex-md-grow-0"
                  ><i class="cil-room align-text-top"></i>&nbsp;Login</button>
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
