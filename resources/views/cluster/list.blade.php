@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-5 col-lg-9">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="h4 m-0 my-1 text-nowrap">Cluster List</div>
              <a
                  href="{{ route('clusters.create') }}"
                  class="btn btn-sm btn-light font-weight-bold ml-2"
                ><i class="cil-library-add align-text-top"></i>&nbsp;Create</a>
            </div>
            <div class="card-body pb-2">
              <x-alert></x-alert>
              
              <form
                id="filter"
                action="{{ route('clusters.index') }}"
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
                    placeholder="Cari cluster"
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
                    >Sort by</option>
                    <option
                      value="name"
                      {{ request('sortBy') == 'name' ? 'selected' : '' }}
                    >Name</option>
                    {{-- <option
                      value="units_count"
                      {{ request('sortBy') == 'units_count' ? 'selected' : '' }}
                    >Units</option> --}}
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
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th class="text-right">Units</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($clusters as $cluster)
                    <tr>
                      <th class="align-middle">{{ ($latestClusters->currentpage() - 1) * $latestClusters->perpage() + $loop->iteration }}</th>
                      <td class="align-middle">{{ $cluster->name }}</td>
                      <td class="align-middle">{{ number_format($cluster->cost) }} / {{ $cluster->per }}</td>
                      <td class="align-middle text-right">{{ number_format($cluster->units_count) }}</td>
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
                              href="{{ route('clusters.show', $cluster->id) }}"
                            ><i class="cil-description"></i>&nbsp;View</a>
                            <a
                              class="dropdown-item font-weight-bold"
                              href="{{ route('clusters.edit', $cluster->id) }}"
                            ><i class="cil-pencil"></i>&nbsp;Edit</a>
                            <form
                              action="{{ route('clusters.destroy', $cluster->id) }}"
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
                {{ $latestClusters->appends(request()->input())->links() }}
              </div>

              <small class="text-muted">
                Showing {{ $latestClusters->count() }} of <a
                  href="{{ substr($latestClusters->url(1), 0, -1) . 'all' }}"
                  class="text-muted"
                >{{ $latestClusters->total() }}</a>
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
