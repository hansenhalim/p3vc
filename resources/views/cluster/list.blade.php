@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header"><i class="fa fa-align-justify"></i> Cluster List</div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif
              <form id="search" action="{{ route('clusters.index') }}" method="get">
                <div class="row">
                  <div class="col-md-3 col-6 mb-2">
                    <select class="custom-select" name="sort">
                      <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Sort by</option>
                      <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                      <option value="units_count" {{ request('sort') == 'units_count' ? 'selected' : '' }}>
                        Units</option>
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
                        <button type="submit" class="btn btn-outline-dark">Search</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Units</th>
                    <th scope="col">Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($clusters as $cluster)
                    <tr>
                      <th scope="row">
                        {{ ($clusters->currentpage() - 1) * $clusters->perpage() + $loop->iteration }}
                      </th>
                      <td>{{ $cluster->name }}</td>
                      <td>{{ $cluster->units_count }}</td>
                      <td>{{ number_format($cluster->prices->last()->cost) }} / {{ $cluster->prices->last()->per }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                {{ $clusters->appends(request()->input())->links() }}
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-left">
                  Showing {{ $clusters->count() }} of {{ $clusters->total() }}
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
