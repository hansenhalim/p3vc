@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-6 col-lg-10">
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 my-1 text-nowrap">Customer List</div>
            </div>
            <div class="card-body pb-2">
              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                  {!! session('status') !!}
                  <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                  >
                    <span>&times;</span>
                  </button>
                </div>
              @endif

              {{-- <a class="btn btn-primary mb-2" href="{{ route('customers.create') }}">Create Customer</a> --}}

              <form
                id="filter"
                action="{{ route('customers.index') }}"
              ></form>

              <div class="d-flex flex-column-reverse flex-md-row justify-content-between">
                <div class="input-group w-auto mb-3 rounded">
                  <input
                    type="text"
                    name="search"
                    form="filter"
                    class="form-control border-0"
                    style="background-color: rgba(0,0,21,.05);"
                    value="{{ request('search') }}"
                    placeholder="Cari customer"
                  >
                  <div class="input-group-append">
                    <button
                      type="submit"
                      class="btn btn-warning"
                      form="filter"
                    ><i
                        class="cil-search align-text-top"
                      ></i></button>
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
                    >CIF</option>
                    <option
                      value="name"
                      {{ request('sortBy') == 'name' ? 'selected' : '' }}
                    >Name</option>
                    <option
                      value="units_count"
                      {{ request('sortBy') == 'units_count' ? 'selected' : '' }}
                    >Units</option>
                    <option
                      value="phone_number"
                      {{ request('sortBy') == 'phone_number' ? 'selected' : '' }}
                    >Phone</option>
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
                </div>
              </div>

              <table class="table table-responsive-sm table-striped table-borderless text-nowrap m-0">
                <thead class="border-bottom">
                  <tr>
                    <th>CIF</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th class="text-right">Units</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($customers as $customer)
                    <tr>
                      <th class="align-middle">#{{ $customer->id }}</th>
                      <td class="align-middle">{{ $customer->name }}</td>
                      <td class="align-middle">{!! $customer->phone_number ?? '<span class="badge badge-dark">None</span>' !!}</td>
                      <td class="align-middle text-right">{{ $customer->units_count }}</td>
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
                              href="{{ route('customers.show', $customer->id) }}"
                            ><i class="cil-description"></i>&nbsp;View</a>
                            <a
                              class="dropdown-item font-weight-bold"
                              href="{{ route('customers.edit', $customer->id) }}"
                            ><i class="cil-pencil"></i>&nbsp;Edit</a>
                            <form
                              action="{{ route('customers.destroy', $customer->id) }}"
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
                        colspan="5"
                        class="text-center p-4"
                      >Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>

              <div class="d-flex justify-content-center mt-4 mb-0">
                {{ $customers->appends(request()->input())->links() }}
              </div>

              <small class="text-muted">
                Showing {{ $customers->count() }} of <a
                  href="{{ substr($customers->url(1), 0, -1) . 'all' }}"
                  class="text-muted"
                >{{ $customers->total() }}</a>
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
