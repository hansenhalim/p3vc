@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <x-return-button></x-return-button>
          <div class="card">
            <x-card-header>Edit Cluster</x-card-header>
            <form
              class="form-horizontal"
              action="{{ route('clusters.update', $cluster) }}"
              method="post"
            >
              @csrf
              @method('PUT')
              <div class="card-body">
                <x-alert></x-alert>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Nama</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('name') is-invalid @enderror"
                      type="text"
                      name="name"
                      value="{{ old('name', $cluster->name) }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Harga</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('cost') is-invalid @enderror"
                      type="number"
                      name="cost"
                      value="{{ old('cost', $cluster->cost) }}"
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
                      class="custom-select @error('per') is-invalid @enderror"
                    >
                      <option
                        value="sqm"
                        @if (old('per', $cluster->per) === 'sqm') selected @endif
                      >Meter persegi</option>
                      <option
                        value="mth"
                        @if (old('per', $cluster->per) === 'mth') selected @endif
                      >Bulan</option>
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
                  ><i class="cil-save align-text-top"></i> Save</button>
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
