@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">Unit List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
              <a class="btn btn-outline-dark mb-2" href="{{ route('units.create') }}">Create Unit</a>
              <form id="search" action="{{ route('units.index') }}" method="get">
                <div class="row">
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="sort">
                      <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Sort by</option>
                      <option value="customers" {{ request('sort') == 'customers' ? 'selected' : '' }}>Name</option>
                      <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Units</option>
                    </select>
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="order">
                      <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Smallest</option>
                      <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Largest</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-2">
                    <div class="input-group">
                      <input type="text" class="form-control" name="key" value="{{ request('key') }}" placeholder="use # for CIF">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-dark">Search</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th scope="col">CIF</th>
                    <th scope="col">Name</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Cluster</th>
                    <th scope="col">Area (m<sup>2</sup>)</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Credit</th>
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
                        {{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}
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
