@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <a
            class="btn btn-sm btn-secondary font-weight-bold mb-2"
            href="{{ route('clusters.index') }}"
          ><i class="cil-chevron-circle-left-alt align-text-top"></i> Return</a>
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 my-1 text-nowrap">Create Cluster</div>
            </div>
            <form
              class="form-horizontal"
              action="{{ route('clusters.store') }}"
              method="post"
            >
              @csrf
              <div class="card-body">
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show">
                    {!! session('status') !!}
                    <button
                      type="button"
                      class="close"
                      data-dismiss="alert"
                    >
                      <span>&times;</span>
                    </button>
                  </div>
                @endif

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
                  <label class="col-md-3 col-form-label">Price</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('cost') is-invalid @enderror"
                      type="text"
                      name="cost"
                      value="{{ old('cost') }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('cost')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Per</label>
                  <div class="col">
                    <select
                      name="per"
                      class="custom-select"
                    >
                      <option value="sqm">Meter persegi</option>
                      <option value="mth">Bulan</option>
                    </select>
                    @error('per')
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
