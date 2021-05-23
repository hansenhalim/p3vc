@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-lg-9">
          <div class="card">
            <div class="card-header">Customer List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif
              <a class="btn btn-primary mb-2" href="{{ route('customers.create') }}">Create Customer</a>
              <form id="search" action="{{ route('customers.index') }}" method="get">
                <div class="row">
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="sort">
                      <option value="" {{ request('sort') == '' ? 'selected' : '' }}>CIF</option>
                      <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                      <option value="idlink" {{ request('sort') == 'idlink' ? 'selected' : '' }}>Id Link</option>
                      <option value="phone_number" {{ request('sort') == 'phone_number' ? 'selected' : '' }}>Phone</option>
                    </select>
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="order">
                      <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Largest</option>
                      <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Smallest</option>
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
                    <th scope="col">Id&nbsp;Link</th>
                    <th scope="col">Phone</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($customers as $customer)
                    <tr>
                      <th scope="row">{{ $customer->id }}</th>
                      <td>{{ $customer->name }}</td>
                      <td>{!! $customer->idlink ?? '-' !!}</td>
                      <td>{!! $customer->phone_number ?? '-' !!}</td>
                      <td>
                        <a href="{{ route('customers.show', ['customer' => $customer->id]) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('customers.edit', ['customer' => $customer->id]) }}" class="btn btn-danger">Edit</a>
                      </td>
                      <td>
                        <form class="form-inline" action="{{ route('customers.destroy', ['customer' => $customer->id]) }}" method="post">
                          @method('DELETE')
                          @csrf
                          <button type="submit" class="btn btn-link text-danger">Delete</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $customers->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-left">
                  Showing {{ $customers->count() }} of {{ $customers->total() }}
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
