@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">Transaction List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                </div>
              @endif
              {{-- <form id="search" action="{{ route('transactions.index') }}" method="get">
                <div class="row">
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="sort">
                      <option value="" {{ request('sort') == '' ? 'selected' : '' }}>CIF</option>
                      <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                      <option value="units_count" {{ request('sort') == 'units_count' ? 'selected' : '' }}>Units</option>
                      <option value="phone_number" {{ request('sort') == 'phone_number' ? 'selected' : '' }}>Phone</option>
                    </select>
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="order">
                      <option value="" {{ request('order') == '' ? 'selected' : '' }}>Largest</option>
                      <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Smallest</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-2">
                    <div class="input-group">
                      <input type="text" class="form-control" name="key" value="{{ request('key') }}" placeholder="use # for CIF">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-dark">Search</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form> --}}
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th>CIF</th>
                    <th>Unit</th>
                    <th>Period</th>
                    <th>Approved At</th>
                    <th>More</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($transactions as $transaction)
                    <tr>
                      <th class="align-middle">#{{ $transaction->unit->customer_id }}</th>
                      <td class="align-middle">{{ $transaction->unit->name }}</td>
                      <td class="align-middle">{{ date('F Y', strtotime($transaction->period)) }}</td>
                      <td class="align-middle">{!! $transaction->approved_at ?? '<span class="badge bg-danger text-white">None</span>' !!}</td>
                      <td class="align-middle">
                        <div class="dropdown">
                          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i class="cil-info"></i>&nbsp;Show</a>
                            @if(Auth::user()->hasRole('supervisor') && !$transaction->approved_at)
                              <a class="dropdown-item" href="{{ route('transactions.approve', ['transaction' => $transaction->id]) }}"><i class="cil-check"></i>&nbsp;Approve</a>
                            @endif
                            @if(Auth::user()->hasRole('operator') && $transaction->approved_at)
                              <a class="dropdown-item" href="{{ route('transactions.print') }}"><i class="cil-print"></i>&nbsp;Print</a>
                            @endif
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" style="text-align: center">Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $transactions->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
