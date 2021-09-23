@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <x-return-button href="{{ route('units.index') }}"></x-return-button>
      <div class="row">
        <div class="col-xl-5 col-md-7">
          <div class="card">
            <x-card-header>Unit Show</x-card-header>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Blok</label>
                <div class="col">
                  <input
                    disabled
                    class="form-control border-0"
                    value="{{ $unit->name }}"
                    style="background-color: rgba(0,0,21,.05);"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Id Link</label>
                <div class="col">
                  <input
                    disabled
                    class="form-control border-0"
                    value="{{ $unit->idlink }}"
                    style="background-color: rgba(0,0,21,.05);"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Luas&nbsp;(m<sup>2</sup>)</label>
                <div class="col">
                  <input
                    disabled
                    class="form-control border-0"
                    value="{{ $unit->area_sqm }}"
                    style="background-color: rgba(0,0,21,.05);"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Cluster</label>
                <div class="col">
                  <input
                    disabled
                    class="form-control border-0"
                    value="{{ $unit->cluster->name }}"
                    style="background-color: rgba(0,0,21,.05);"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer</label>
                <div class="col">
                  <input
                    disabled
                    class="form-control border-0"
                    value="{{ $unit->customer->name }} ( @foreach ($unit->customer->units as $unit){{ $unit->name }}@if (!$loop->last) |@endif @endforeach)"
                    style="background-color: rgba(0,0,21,.05);"
                  >
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

  @endsection

  @section('javascript')

  @endsection
