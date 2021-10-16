@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-7 col-lg-11">
          <div class="card">
            <x-card-header>Approval List</x-card-header>
            <div class="card-body pb-2">
              <x-alert></x-alert>
              <table class="table table-responsive-md table-striped table-borderless text-nowrap m-0">
                <thead class="border-bottom">
                  <tr>
                    <th>Type</th>
                    <th>Operation</th>
                    <th>Name</th>
                    <th>Modified</th>
                    <th>Requester</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($approvals as $approval)
                    <tr>
                      <th class="align-middle"><i class="@switch($approval->type) @case('customer') cil-people @break @case('cluster') cil-factory @break @default cil-house @endswitch align-text-top"></i></th>
                      <td class="align-middle">
                        <span class="badge @switch($approval->operation) @case('INS') badge-success @break @case('MOD') badge-info @break @default badge-danger @endswitch">{{ $approval->operation }}</span>
                      </td>
                      <td class="align-middle">{{ $approval->name }}</td>
                      <td class="align-middle">{{ $approval->updated_at->diffForHumans() }}</td>
                      <td class="align-middle">{{ $approval->user->name }}</td>
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
                              href="{{ route('approvals.show', [$approval->type, $approval]) }}"
                            ><i class="cil-description"></i>&nbsp;View</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td
                        colspan="6"
                        class="text-center p-4"
                      >Oops, nothing found here :(</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('javascript')

@endsection
