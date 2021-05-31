@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header">Customer List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                </div>
              @endif
              <a class="btn btn-primary mb-2" href="{{ route('customers.create') }}">Create Customer</a>
              <form id="search" action="{{ route('customers.index') }}" method="get">
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
              </form>
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th>CIF</th>
                    <th>Name</th>
                    <th>Units</th>
                    <th>Phone</th>
                    <th>More</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($customers as $customer)
                    <tr>
                      <th class="align-middle">{{ $customer->id }}</th>
                      <td class="align-middle">{{ $customer->name }}</td>
                      <td class="align-middle">{{ $customer->units_count }}</td>
                      <td class="align-middle">{!! $customer->phone_number ?? '<span class="badge bg-danger text-white">None</span>' !!}</td>
                      <td class="align-middle">
                        <div class="dropdown">
                          <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Action
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('customers.show', ['customer' => $customer->id]) }}"><i class="cil-info"></i>&nbsp;View</a>
                            <a class="dropdown-item" href="{{ route('customers.edit', ['customer' => $customer->id]) }}"><i class="cil-pencil"></i>&nbsp;Edit</a>
                            <div class="dropdown-divider"></div>
                            <form class="form-inline" action="{{ route('customers.destroy', ['customer' => $customer->id]) }}" method="post">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="dropdown-item"><i class="cil-trash"></i>&nbsp;Delete</button>
                            </form>
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
                {{ $customers->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} entries
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
