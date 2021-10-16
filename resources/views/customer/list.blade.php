@extends('dashboard.base')

@section('content')
<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-xl-6 col-lg-10">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="h4 m-0 my-1 text-nowrap">Customer List</div>
            <a
              href="{{ route('customers.create') }}"
              class="btn btn-sm btn-light font-weight-bold"
            ><i class="cil-user-plus align-text-top"></i>&nbsp;Create</a>
          </div>
          <div class="card-body pb-2">
            <x-alert></x-alert>

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
                  ><i class="cil-search align-text-top"></i></button>
                </div>
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
                  <th class="align-middle">#{{ $customer->previous_id }}</th>
                  <td class="align-middle">{{ $customer->name }}</td>
                  <td class="align-middle">{!! $customer->phone_number ?? '<span class="badge badge-dark">None</span>' !!}</td>
                  <td class="align-middle text-right">{{ $customer->units_count }}</td>
                  <td class="align-middle text-right">
                    <div class="btn-group">
                      <button
                        class="btn btn-warning btn-sm dropdown-toggle"
                        type="button"
                        data-toggle="dropdown"
                      >Action
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item font-weight-bold"
                          href="{{ route('customers.show', $customer) }}"
                        ><i class="cil-description"></i>&nbsp;View</a>
                        <a
                          class="dropdown-item font-weight-bold"
                          href="{{ route('customers.edit', $customer) }}"
                        ><i class="cil-pencil"></i>&nbsp;Edit</a>
                        <form
                          action="{{ route('customers.destroy', $customer) }}"
                          method="post"
                        >
                          @csrf
                          @method('DELETE')
                          <button
                            type="submit"
                            class="dropdown-item font-weight-bold text-danger"
                          ><i class="cil-trash"></i>&nbsp;Delete
                          </button>
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
                  >Oops, nothing found here :(
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4 mb-0">
              {{ $latestCustomers->appends(request()->input())->links() }}
            </div>

            <small class="text-muted">
              Showing {{ $latestCustomers->count() }} of <a
                href="{{ substr($latestCustomers->url(1), 0, -1) . 'all' }}"
                class="text-muted"
              >{{ $latestCustomers->total() }}</a>
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