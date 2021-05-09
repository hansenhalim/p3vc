@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header"><i class="fa fa-align-justify"></i> Unit List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
              <form id="search" action="{{ route('units.index') }}" method="get">
                <div class="row mb-2">
                  <div class="col-3">
                    <select class="custom-select" name="sort">
                      <option value="" {{ request('sort') == '' ? 'selected' : '' }}>{{ __('Sort by') }}</option>
                      <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ __('Name') }}
                      </option>
                      <option value="units_count" {{ request('sort') == 'units_count' ? 'selected' : '' }}>
                        {{ __('Units') }}</option>
                    </select>
                  </div>
                  <div class="col-3">
                    <select class="custom-select" name="order">
                      <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>{{ __('Smallest') }}
                      </option>
                      <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>{{ __('Largest') }}
                      </option>
                    </select>
                  </div>
                  <div class="col-6">
                    <div class="input-group">
                      <input type="text" class="form-control" name="key" value="{{ request('key') }}">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">{{ __('Search') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th>CIF</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Cluster</th>
                    <th>Area (m<sup>2</sup>)</th>
                    <th>Balance</th>
                    <th>Credit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($units as $unit)
                    <tr class='clickable-row' data-href="{{ route('units.show', $unit->id) }}">
                      <th scope="row">{{ $unit->id }}</th>
                      <td>{{ $unit->customer->name }}</td>
                      <td>{{ $unit->name }}</td>
                      <td>{{ $unit->cluster->name }}</td>
                      <td>{{ $unit->area_sqm }}</td>
                      <td>{{ $unit->balance }}</td>
                      <td>
                        {{ $unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1) }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $units->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-left">
                  Showing {{ $units->count() }} of {{ $units->total() }}
                </div>
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
