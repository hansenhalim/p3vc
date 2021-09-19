@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <x-card-header>Approval Details</x-card-header>
            <div class="card-body pb-2">
              <div class="row">
                @switch($approval->operation)
                  @case('DEL')
                    <div class="col-md mt-2">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md">
                          <input
                            disabled
                            value="{{ $approval->name }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Area</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->area_sqm }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cluster</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->cluster->name }} ({{ number_format($approval->cluster->cost) }}/{{ $approval->cluster->per }})"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cust.</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->customer->name }} ( @foreach ($approval->customer->units as $unit){{ $unit->name }}@if (!$loop->last) |@endif @endforeach)"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                    </div>
                  @break
                  @case('MOD')
                    <div class="col-md mt-2">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md">
                          <input
                            disabled
                            value="{{ $approval->original->name }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Area</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->original->area_sqm }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cluster</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->original->cluster->name }} ({{ number_format($approval->original->cluster->cost) }}/{{ $approval->original->cluster->per }})"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cust.</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->original->customer->name }} ( @foreach ($approval->original->customer->units as $unit){{ $unit->name }}@if (!$loop->last) |@endif @endforeach)"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                    </div>
                  @break
                  @default
                    <div class="col-md mb-4 mb-md-0">
                      <div
                        class="rounded-lg d-flex flex-column justify-content-center align-items-center h-100 py-4"
                        style="border: solid 2px #ced2d8; border-style: dashed;"
                      >
                        <i class="cil-library-add h2 text-secondary"></i>
                        <h6 class="m-0 font-weight-bold text-secondary">Create New</h6>
                      </div>
                    </div>
                @endswitch
                <div class="col-md-1 d-flex justify-content-center align-items-center">
                  <i class="cil-chevron-double-down h1 d-md-none"></i>
                  <i class="cil-chevron-double-right h1 d-none d-md-block"></i>
                </div>
                @switch($approval->operation)
                  @case('DEL')
                    <div class="col-md mt-4 mt-md-0">
                      <div
                        class="rounded-lg d-flex flex-column justify-content-center align-items-center h-100 py-4"
                        style="border: solid 2px #ced2d8; border-style: dashed;"
                      >
                        <i class="cil-trash h2 text-secondary"></i>
                        <h6 class="m-0 font-weight-bold text-secondary">Delete Forever</h6>
                      </div>
                    </div>
                  @break
                  @default
                    <div class="col-md mt-2">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md">
                          <input
                            disabled
                            value="{{ $approval->name }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Area</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->area_sqm }}"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cluster</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->cluster->name }} ({{ number_format($approval->cluster->cost) }}/{{ $approval->cluster->per }})"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Cust.</label>
                        <div class="col">
                          <input
                            disabled
                            value="{{ $approval->customer->name }} ( @foreach ($approval->customer->units as $unit){{ $unit->name }}@if (!$loop->last) |@endif @endforeach)"
                            class="form-control border-0"
                            style="background-color: rgba(0,0,21,.05);"
                          >
                        </div>
                      </div>
                    </div>
                @endswitch
              </div>
            </div>
            <form
              action="{{ route('approvals.approve', [$approval->type, $approval]) }}"
              method="POST"
            >
              @csrf
              <div class="card-footer d-flex justify-content-between">
                <button
                  value="false"
                  name="approval"
                  type="submit"
                  class="btn btn-link text-danger font-weight-bold"
                ><i class="cil-thumb-down align-text-top"></i>&nbsp;Reject</button>
                <button
                  value="true"
                  name="approval"
                  type="submit"
                  class="btn btn-warning font-weight-bold"
                ><i class="cil-thumb-up align-text-top"></i>&nbsp;Approve</button>
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
