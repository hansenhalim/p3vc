@extends('dashboard.base')

@section('content')
<div class="container-fluid">
  <div class="fade-in">
    <x-return-button></x-return-button>
    <div class="row">
      <div class="col-xl-4 col-md-6">
        <div class="card">
          <x-card-header>Cluster Show</x-card-header>
          <div class="card-body">

            <div class="form-group row">
              <label class="col-md-3 col-form-label">Name</label>
              <div class="col">
                <input
                  class="form-control"
                  type="text"
                  value="{{ $cluster->name }}"
                  disabled
                >
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 col-form-label">Price</label>
              <div class="col">
                <input
                  class="form-control"
                  type="text"
                  value="{{ number_format($cluster->cost) }} / {{ $cluster->per }}"
                  disabled
                >
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    @isset ($units)
    <div class="row">
      <div class="col-xl-9">
        <div class="card">
          <x-card-header>Unit List</x-card-header>
          <div class="card-body">
            <table class="table table-responsive-md">
              <thead class="thead-dark">
                <tr>
                  <th class="text-center">#</th>
                  <th>Blok</th>
                  <th>Customer</th>
                  <th colspan="2">Cluster</th>
                  <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th>
                  <th class="text-right">Hutang</th>
                  <th class="text-right">Saldo</th>
                  <th class="text-right">Iuran</th>
                </tr>
              </thead>
              @foreach ($units as $unit)
              <tbody>
                <tr class="table-light">
                  <th class="text-center">{{ ($latestUnits->currentpage() - 1) * $latestUnits->perpage() + $loop->iteration }}</th>
                  <th>{{ $unit->name }}</th>
                  <th>{{ $unit->customer->name }}</th>
                  <th colspan="2">{{ $cluster->name }}</th>
                  <td class="text-right">{{ $unit->area_sqm }}</td>
                  <td class="text-right">
                    @if ($unit->debt == 0)
                    {{ number_format($unit->debt) }}
                    @else
                    <a
                      href="{{ route('units.debt', $unit) }}"
                      style="color:red;"
                    >{{ number_format($unit->debt) }}</a>
                    @endif
                  </td>
                  <td class="text-right">{{ number_format($unit->balance) }}</td>
                  <td class="text-right">
                    {{ number_format($cluster->cost * ($cluster->per === 'mth' ?: $unit->area_sqm)) }}
                  </td>
                  <input
                    type="hidden"
                    name="units[{{ $loop->index }}][unit_id]"
                    value="{{ $unit->id }}"
                  >
                  <input
                    type="hidden"
                    name="units[{{ $loop->index }}][unit_previous_id]"
                    value="{{ $unit->previous_id }}"
                  >
                </tr>
              </tbody>
              @endforeach
            </table>
            <div class="d-flex justify-content-center mt-4 mb-0">
              {{ $latestUnits->appends(request()->input())->links() }}
            </div>

            <small class="text-muted">
              Showing {{ $latestUnits->count() }} of {{ $latestUnits->total() }}
            </small>
          </div>
        </div>
      </div>
    </div>
    @endisset
  </div>
</div>


@endsection

@section('javascript')

@endsection