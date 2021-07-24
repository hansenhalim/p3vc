@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-9">
          <div class="card">
            <div class="card-header">Unit List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
              {{-- <a class="btn btn-primary mb-2" href="{{ route('units.create') }}">Create Unit</a> --}}
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
                      <input type="text" class="form-control" name="key" value="{{ request('key') }}">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-dark">Search</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <table class="table table-responsive-sm table-striped text-nowrap">
                <thead class="thead-dark">
                  <tr>
                    <th>CIF</th>
                    <th>Unit</th>
                    <th>Name</th>
                    {{-- <th>Cluster</th> --}}
                    {{-- <th class="text-right">Area&nbsp;(m<sup>2</sup>)</th> --}}
                    <th class="text-right">Balance</th>
                    <th class="text-right">Debt</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Tunggakan</th>
                    <th class="text-right">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($units as $unit)
                    <tr>
                      <th>{{ $unit->customer->id }}</th>
                      <td>{{ $unit->name }}</td>
                      <td>{{ $unit->customer->name }}</td>
                      {{-- <td>{{ $unit->cluster->name }}</td> --}}
                      {{-- <td class="text-right">{{ number_format($unit->area_sqm) }}</td> --}}
                      <td class="text-right">{{ number_format($unit->balance) }}</td>
                      <td class="text-right">{{ number_format($unit->debt) }}</td>
                      <td class="text-right">{{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}</td>
                      <td class="text-right">{{ number_format($unit->months_total) }}</td>
                      {{-- <td class="text-right">{{ number_format(0) }}</td> --}}
                      <td class="text-right">{{ $unit->months_count }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" style="text-align: center">Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
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
