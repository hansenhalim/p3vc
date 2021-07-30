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
                <div>{{ $unitsLastSync->diffForHumans() }}</div>
                <button form="sync" onclick="this.previousElementSibling.innerHTML='loading&hellip;';this.disabled=true; this.form.submit();" style="color: #3c4b64" class="btn btn-link px-0 ml-2">
                  <i class="cil-sync align-text-top"></i>
                </button>
                {{-- <a class="btn" style="background: #e6ad4a" href="{{ route('units.report.print', request()->input()) }}" target="_blank" rel="noopener noreferrer"><i class="cil-cloud-download align-text-top"></i></a> --}}
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
              <form id="sync" action="{{ route('units.sync') }}" method="post">@csrf</form>

              <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                <div class="input-group shadow w-auto mb-3 rounded">
                  <input type="text" class="form-control border-0" name="search" form="filter" value="{{ request('search') }}" placeholder="use # for CIF">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-dark" form="filter"><i class="cil-search align-text-top"></i></button>
                  </div>
                </div>

                <div class="d-flex">
                  <select onchange="this.form.submit()" class="custom-select mb-3 ml-0 ml-md-2 border-0 text-dark font-weight-bold bg-light" name="sortBy" form="filter">
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

                  <select onchange="this.form.submit()" class="custom-select w-auto mb-3 ml-2 border-0 text-dark font-weight-bold bg-light" name="sortDirection" form="filter">
                    <option value="" {{ request('sortDirection') == '' ? 'selected' : '' }}>Smallest</option>
                    <option value="desc" {{ request('sortDirection') == 'desc' ? 'selected' : '' }}>Largest</option>
                  </select>

                  <div class="border-left ml-2 mb-3">
                    <div class="dropdown">
                      <button class="btn btn-dark ml-2 font-weight-bold dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="cil-data-transfer-down align-text-bottom"></i>&nbsp;Download
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="shadow rounded overflow-hidden">
                <table class="table table-responsive-xxl table-striped table-borderless text-nowrap m-0">
                  <thead class="thead-dark">
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
                  <tbody>
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
                        <td colspan="42" style="text-align: center">Oops, nothing found here :(</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <div class="d-flex justify-content-center mt-4 mb-0">
                {{ $units->appends(request()->input())->links() }}
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-left">
                  Showing {{ $units->count() }} of <a href="{{ substr($units->url(1), 0, -1) . 'all' }}" style="color: #3c4b64">{{ $units->total() }}</a>
                </div>
              </div>
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
      color: #3c4b64;
    }

    .page-item.active .page-link {
      background-color: #636f83;
      border-color: #636f83;
    }

    .form-control:focus, .btn:focus, .custom-select:focus {
      box-shadow: none;
    }

  </style>

@endsection

@section('javascript')

@endsection
