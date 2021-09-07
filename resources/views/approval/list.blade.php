@extends('dashboard.base')

@section('content')
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header">
              <div class="h4 m-0 my-1 text-nowrap">Approval List</div>
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

              <table class="table table-responsive-md table-striped table-borderless text-nowrap m-0">
                <thead class="border-bottom">
                  <tr>
                    <th>Type</th>
                    <th>Operation</th>
                    <th>Description</th>
                    <th>Created</th>
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
                      <td class="align-middle">{{ $approval->created_at->diffForHumans() }}</td>
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
