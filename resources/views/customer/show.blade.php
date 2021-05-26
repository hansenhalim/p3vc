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
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Cluster</th>
                    <th scope="col">Area&nbsp;(m<sup>2</sup>)</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Credit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($units as $unit)
                    <tr>
                      <th scope="row">
                        {{ ($units->currentpage() - 1) * $units->perpage() + $loop->iteration }}
                      </th>
                      <td>{!! $unit->customer->name ?? '<span class="badge bg-danger text-white">NONE</span>' !!}</td>
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
