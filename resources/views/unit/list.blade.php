@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-11">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="h4 m-0 text-nowrap">Unit List</div>
              <div class="d-flex align-items-center">
                <div title="{{ $unitsLastSync->toDateTimeString() }}">{{ $unitsLastSync->diffForHumans() }}</div>
                <button form="sync" onclick="this.previousElementSibling.innerHTML='loading&hellip;';this.disabled=true; this.form.submit();" style="color: #3c4b64" class="btn btn-link px-0 ml-2">
                  <i class="cil-sync align-text-top"></i>
                </button>
              </div>
            </div>
            <div class="card-body pb-2">
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif
              {{-- <a class="btn btn-primary mb-2" href="{{ route('units.create') }}">Create Unit</a> --}}
              <form id="filter" action="{{ route('units.index') }}" method="get"></form>
              <form id="sync" action="units/sync" method="post">@csrf</form>

              <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                <div class="input-group w-auto mb-3 rounded">
                  <input type="text" name="search" form="filter" class="form-control border-0" style="background-color: rgba(0,0,21,.05);" value="{{ request('search') }}" placeholder="Cari Blok">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-warning" form="filter"><i class="cil-search align-text-top" style="color: black"></i></button>
                  </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                  <select onchange="this.form.submit()" class="custom-select custom-select-sm ml-0 ml-md-2" name="sortBy" form="filter">
                    <option value="" {{ request('sortBy') == '' ? 'selected' : '' }}>Sort by</option>
                    <option value="customer_id" {{ request('sortBy') == 'customer_id' ? 'selected' : '' }}>CIF</option>
                    <option value="name" {{ request('sortBy') == 'name' ? 'selected' : '' }}>Blok</option>
                    <option value="idlink" {{ request('sortBy') == 'idlink' ? 'selected' : '' }}>IdLink</option>
                    <option value="area_sqm" {{ request('sortBy') == 'area_sqm' ? 'selected' : '' }}>Luas (m2)</option>
                    <option value="balance" {{ request('sortBy') == 'balance' ? 'selected' : '' }}>Saldo</option>
                    <option value="debt" {{ request('sortBy') == 'debt' ? 'selected' : '' }}>Hutang</option>
                    <option value="months_count" {{ request('sortBy') == 'months_count' ? 'selected' : '' }}>Jml Bulan
                    </option>
                    <option value="months_total" {{ request('sortBy') == 'months_total' ? 'selected' : '' }}>Tunggakan
                    </option>
                    <option value="credit" {{ request('sortBy') == 'credit' ? 'selected' : '' }}>Iuran</option>
                  </select>

                  <select onchange="this.form.submit()" class="custom-select custom-select-sm ml-2 w-auto" name="sortDirection" form="filter">
                    <option value="" {{ request('sortDirection') == '' ? 'selected' : '' }}>Smallest</option>
                    <option value="desc" {{ request('sortDirection') == 'desc' ? 'selected' : '' }}>Largest</option>
                  </select>

                  <div class="border-left ml-2">
                    <div class="dropdown">
                      <button class="btn btn-warning ml-2 font-weight-bold dropdown-toggle" style="color: black" type="button" data-toggle="dropdown">
                        <i class="cil-data-transfer-down align-text-bottom"></i>&nbsp;Export
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('units.export', 'linkaja') }}">Report LinkAja (.xlsx)</a>
                        <a class="dropdown-item" href="{{ route('units.export', 'report') }}">Report Unit (.xlsx)</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('units.export', 'recapitulation') }}">Rekapitulasi (.pdf)</a>
                      </div>
                    </div>
                  </div>

                </div>
              </div>


              <table class="table table-responsive-xxl table-striped table-borderless text-nowrap m-0">
                <thead class="border-bottom">
                  <tr>
                    <th>CIF</th>
                    <th>Blok</th>
                    <th>Nama</th>
                    <th>IdLink</th>
                    <th class="text-right">Luas&nbsp;(m<sup>2</sup>)</th>
                    <th class="text-right">Saldo</th>
                    <th class="text-right">Hutang</th>
                    <th class="text-right">Jml Bulan</th>
                    <th class="text-right">Tunggakan</th>
                    <th class="text-right">Iuran</th>
                  </tr>
                </thead>
                <tbody class="border-bottom">
                  @forelse ($units as $unit)
                    <tr>
                      <th>#{{ $unit->customer_id }}</th>
                      <td>{{ $unit->name }}</td>
                      <td>{{ $unit->customer_name }}</td>
                      <td>{{ $unit->idlink }}</td>
                      <td class="text-right">{{ number_format($unit->area_sqm, 1) }}</td>
                      <td class="text-right">{{ number_format($unit->balance) }}</td>
                      <td class="text-right">{{ number_format($unit->debt) }}</td>
                      <td class="text-right">{{ $unit->months_count }}</td>
                      <td class="text-right">{{ number_format($unit->months_total) }}</td>
                      <td class="text-right">{{ number_format($unit->credit) }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="42" class="text-center p-4">Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
                @if ($units->onFirstPage() && $units->first())
                  <thead>
                    <tr>
                      <th colspan="5">Totals</th>
                      <th class="text-right">{{ number_format($totals->balance) }}</th>
                      <th class="text-right">{{ number_format($totals->debt) }}</th>
                      <th class="text-right">{{ $totals->months_count }}</th>
                      <th class="text-right">{{ number_format($totals->months_total) }}</th>
                      <th class="text-right">{{ number_format($totals->credit) }}</th>
                    </tr>
                  </thead>
                @endif
              </table>


              <div class="d-flex justify-content-center mt-4 mb-0">
                {{ $units->appends(request()->input())->links() }}
              </div>
              <small class="text-muted">
                Showing {{ $units->count() }} of <a href="{{ substr($units->url(1), 0, -1) . 'all' }}" class="text-muted">{{ $units->total() }}</a>
              </small>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    @media screen and (max-width: 576px) {
      li.page-item {
        display: none;
      }

      .page-item:first-child,
      .page-item:nth-child(2),
      .page-item:nth-last-child(2),
      .page-item:last-child,
      .page-item.active,
      .page-item.disabled {
        display: block;
      }
    }

    .page-link {
      border: none;
      color: #888888;
      font-size: 80%;
    }

    .page-item.active .page-link {
      background-color: transparent;
      color: #3c4b64;
      font-weight: bold;
    }

    .btn:focus,
    .form-control:focus,
    .dropdown-toggle:focus,
    .custom-select:focus {
      box-shadow: none !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #f5f5f5;
    }

  </style>

@endsection

@section('javascript')

@endsection
