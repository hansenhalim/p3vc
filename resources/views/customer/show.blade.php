@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-4 col-md-6">
          <a class="btn btn-link mb-2" href="{{ route('customers.index') }}">&lt;&lt; Return</a>
          <div class="card">
            <div class="card-header">Customer Show</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {!! session('status') !!}
                </div>
              @endif
              <div class="form-group row">
                <label class="col-md-3 col-form-label">CIF</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->id }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Name</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->name }}" disabled>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Phone</label>
                <div class="col">
                  <input class="form-control" type="text" value="{{ $customer->phone_number }}" disabled>
                  @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">Unit List</div>
            <div class="card-body">
              <table class="table table-responsive-xl table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Cluster</th>
                    <th>Area&nbsp;(m<sup>2</sup>)</th>
                    <th>Balance</th>
                    <th>Credit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($units as $unit)
                    <tr>
                      <th>
                        {{ ($units->currentpage() - 1) * $units->perpage() + $loop->iteration }}
                      </th>
                      <td><a href="{{ route('units.show', ['unit' => $unit->id]) }}">{{ $unit->name }}</a></td>
                      <td><a href="{{ route('customers.show', ['customer' => $unit->customer->id]) }}">{{ $unit->customer->name }}</a></td>
                      <td><a href="{{ route('clusters.show', ['cluster' => $unit->cluster->id]) }}">{{ $unit->cluster->name }}</a></td>
                      <td>{{ $unit->area_sqm }}</td>
                      <td>{{ $unit->balance }}</td>
                      <td>
                        {{ number_format($unit->cluster->prices->last()->cost * ($unit->cluster->prices->last()->per == 'sqm' ? $unit->area_sqm : 1)) }}
                      </td>
                    </tr>
                    <thead class="thead-dark">
                      <tr>
                        <th></th>
                        <th></th>
                        <th style="text-align: right">#</th>
                        <th>Periode</th>
                        <th>Iuran</th>
                        <th>Denda</th>
                        <th>Tagihan</th>
                      </tr>
                    </thead>
                    <tbody style="margin-bottom: 1rem">
                      @forelse ($unit->months as $month)
                        <tr>
                          <td></td>
                          <td></td>
                          <th style="text-align: right">{{ $loop->iteration }}</th>
                          <td>{{ $month['period']->format('M Y') }}</td>
                          <td style="text-align: right">{{ number_format($month['credit']) }}</td>
                          <td style="text-align: right">{{ number_format($month['fine']) }}</td>
                          <td>{{ number_format($month['credit']+$month['fine']) }}</td>
                        </tr>
                      @empty
                          <tr class="table-success">
                            <td colspan="7" style="text-align: center">No tunggak tunggak club :)</td>
                          </tr>
                      @endforelse
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
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
