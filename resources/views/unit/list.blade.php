@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="h4 m-0 text-nowrap">Unit List</div>
              <div class="d-flex align-items-center">
                <div title="{{ $unitsLastSync->setTimezone('Asia/Jakarta')->toDateTimeString() }}">{{ $unitsLastSync->diffForHumans() }}</div>
                <button
                  form="sync"
                  onclick="this.previousElementSibling.innerHTML='loading&hellip;';this.disabled=true; this.form.submit();"
                  style="color: #3c4b64"
                  class="btn btn-link px-0 ml-2"
                >
                  <i class="cil-sync align-text-top"></i>
                </button>
                <a
                  href="{{ route('units.create') }}"
                  class="btn btn-sm btn-light font-weight-bold ml-2"
                ><i class="cil-library-add align-text-top"></i>&nbsp;Create</a>
              </div>
            </div>
            <div class="card-body pb-2">
              <x-alert></x-alert>

              <form
                id="filter"
                action="{{ route('units.index') }}"
              ></form>

              <form
                id="sync"
                action="{{ route('units.sync') }}"
                method="post"
              >@csrf</form>

              <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                <div class="input-group w-auto mb-3 rounded">
                  <input
                    type="text"
                    name="search"
                    form="filter"
                    class="form-control border-0"
                    style="background-color: rgba(0,0,21,.05);"
                    value="{{ request('search') }}"
                    placeholder="Cari blok"
                  >
                  <div class="input-group-append">
                    <button
                      type="submit"
                      class="btn btn-warning"
                      form="filter"
                    ><i class="cil-search align-text-top"></i></button>
                  </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                  <select
                    onchange="this.form.submit()"
                    class="custom-select custom-select-sm ml-0 ml-md-2"
                    name="sortBy"
                    form="filter"
                  >
                    <option
                      value=""
                      {{ request('sortBy') == '' ? 'selected' : '' }}
                    >Sort by</option>
                    <option
                      value="customer_id"
                      {{ request('sortBy') == 'customer_id' ? 'selected' : '' }}
                    >CIF</option>
                    <option
                      value="name"
                      {{ request('sortBy') == 'name' ? 'selected' : '' }}
                    >Blok</option>
                    <option
                      value="idlink"
                      {{ request('sortBy') == 'idlink' ? 'selected' : '' }}
                    >IdLink</option>
                    <option
                      value="area_sqm"
                      {{ request('sortBy') == 'area_sqm' ? 'selected' : '' }}
                    >Luas (m2)</option>
                    <option
                      value="balance"
                      {{ request('sortBy') == 'balance' ? 'selected' : '' }}
                    >Saldo</option>
                    <option
                      value="debt"
                      {{ request('sortBy') == 'debt' ? 'selected' : '' }}
                    >Hutang</option>
                    <option
                      value="months_count"
                      {{ request('sortBy') == 'months_count' ? 'selected' : '' }}
                    >Jml Bulan
                    </option>
                    <option
                      value="months_total"
                      {{ request('sortBy') == 'months_total' ? 'selected' : '' }}
                    >Tunggakan
                    </option>
                    <option
                      value="credit"
                      {{ request('sortBy') == 'credit' ? 'selected' : '' }}
                    >Iuran</option>
                  </select>

                  <select
                    onchange="this.form.submit()"
                    class="custom-select custom-select-sm ml-2 w-auto"
                    name="sortDirection"
                    form="filter"
                  >
                    <option
                      value=""
                      {{ request('sortDirection') == '' ? 'selected' : '' }}
                    >Smallest</option>
                    <option
                      value="desc"
                      {{ request('sortDirection') == 'desc' ? 'selected' : '' }}
                    >Largest</option>
                  </select>

                  <div class="border-left ml-2">
                    <div class="dropdown">
                      <button
                        class="btn btn-warning ml-2 dropdown-toggle"
                        type="button"
                        data-toggle="dropdown"
                      >
                        <i class="cil-data-transfer-down align-text-bottom"></i>&nbsp;Export
                      </button>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a
                          class="dropdown-item font-weight-bold"
                          href="{{ route('units.export', 'linkaja') }}"
                        ><i class="cil-dollar align-text-top"></i>&nbsp;LinkAja (.xlsx)</a>
                        <a
                          class="dropdown-item font-weight-bold"
                          href="{{ route('units.export', 'report') }}"
                        ><i class="cil-house align-text-top"></i>&nbsp;Unit (.xlsx)</a>
                        {{-- <div class="dropdown-divider"></div>
                        <p
                          class="dropdown-item font-weight-bold"
                          href="{{ route('units.export', 'recapitulation') }}"
                        ><i class="cil-note-add align-text-top"></i>&nbsp;Rekapitulasi (.pdf)</p> --}}
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <table class="table table-responsive table-striped table-borderless text-nowrap m-0">
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
                    <th class="text-right">month_count</th>
                    <th class="text-right">month_total</th>
                    <th></th>
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
                      <td class="text-right">{{ $unit->paid_months_count }}</td>
                      <td class="text-right">{{ number_format($unit->paid_months_total) }}</td>
                      <td class="align-middle text-right">
                        <div class="btn-group">
                          <button
                            class="btn btn-warning btn-sm dropdown-toggle"
                            type="button"
                            data-toggle="dropdown"
                          >Action</button>
                          <div class="dropdown-menu">
                            <a
                              class="dropdown-item font-weight-bold"
                              href="{{ route('units.show', $unit->id) }}"
                            ><i class="cil-description"></i>&nbsp;View</a>
                            <a
                              class="dropdown-item font-weight-bold"
                              href="{{ route('units.edit', $unit->id) }}"
                            ><i class="cil-pencil"></i>&nbsp;Edit</a>
                            <form
                              action="{{ route('units.destroy', $unit->id) }}"
                              method="post"
                            >
                              @csrf
                              @method('DELETE')
                              <button
                                type="submit"
                                class="dropdown-item font-weight-bold text-danger"
                              ><i class="cil-trash"></i>&nbsp;Delete</button>
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td
                        colspan="11"
                        class="text-center p-4"
                      >Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
                @if ($units->onFirstPage() && $units->first())
                  <thead class="border-top">
                    <tr>
                      <th colspan="5">Totals</th>
                      <th class="text-right">{{ number_format($totals->balance) }}</th>
                      <th class="text-right">{{ number_format($totals->debt) }}</th>
                      <th class="text-right">{{ $totals->months_count }}</th>
                      <th class="text-right">{{ number_format($totals->months_total) }}</th>
                      <th class="text-right">{{ number_format($totals->credit) }}</th>
                      <th class="text-right">{{ $totals->paid_months_count }}</th>
                      <th class="text-right">{{ number_format($totals->paid_months_total) }}</th>
                    </tr>
                  </thead>
                @endif
              </table>

              <div class="d-flex justify-content-center mt-4 mb-0">
                {{ $units->appends(request()->input())->links() }}
              </div>

              <small class="text-muted">
                Showing {{ $units->count() }} of <a
                  href="{{ substr($units->url(1), 0, -1) . 'all' }}"
                  class="text-muted"
                >{{ $units->total() }}</a>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
