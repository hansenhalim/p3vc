@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-md-7">
          <x-return-button></x-return-button>
          <div class="card">
            <x-card-header>Edit Unit</x-card-header>
            <form
              class="form-horizontal"
              action="{{ route('units.update', $unit) }}"
              method="post"
            >
              @csrf
              @method('PUT')
              <div class="card-body">
                @if (session('status'))
                  <div
                    class="alert alert-success"
                    role="alert"
                  >
                    {{ session('status') }}
                  </div>
                @endif

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Blok</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('name') is-invalid @enderror"
                      type="text"
                      name="name"
                      value="{{ old('name', $unit->name) }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Id Link</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('idlink') is-invalid @enderror"
                      type="text"
                      name="idlink"
                      value="{{ old('idlink', $unit->idlink) }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('idlink')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Luas&nbsp;(m<sup>2</sup>)</label>
                  <div class="col">
                    <input
                      class="form-control border-0 @error('area_sqm') is-invalid @enderror"
                      type="number"
                      step="0.10"
                      name="area_sqm"
                      value="{{ old('area_sqm', $unit->area_sqm) }}"
                      style="background-color: rgba(0,0,21,.05);"
                    >
                    @error('area_sqm')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Cluster</label>
                  <div class="col">
                    <select
                      name="cluster_id"
                      class="custom-select @error('cluster_id') is-invalid @enderror"
                    >
                      @foreach ($clusters as $cluster)
                        <option
                          value="{{ $cluster->previous_id }}"
                          @if (old('cluster_id', $unit->cluster_id) == $cluster->previous_id) selected @endif
                        >
                          {{ $cluster->name }} ({{ number_format($cluster->cost) }}/{{ $cluster->per }})
                        </option>
                      @endforeach
                    </select>
                    @error('cluster_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Customer</label>
                  <div class="col">
                    <select
                      name="customer_id"
                      class="custom-select @error('customer_id') is-invalid @enderror"
                    >
                      @foreach ($customers as $customer)
                        <option
                          value="{{ $customer->previous_id }}"
                          @if (old('customer_id', $unit->customer_id) == $customer->previous_id) selected @endif
                        >
                          {{ $customer->name }} (
                          @foreach ($customer->units as $item)
                            {{ $item->name }} @if (!$loop->last)|@endif
                          @endforeach)
                        </option>
                      @endforeach
                    </select>
                    @error('customer_id')
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

  @endsection

  @section('javascript')

  @endsection
