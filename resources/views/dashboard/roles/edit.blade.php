@extends('dashboard.base')

@section('content')


  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <x-card-header>Edit Role</x-card-header>
            <div class="card-body">
              @if (Session::has('message'))
                <div
                  class="alert alert-success"
                  role="alert"
                >{{ Session::get('message') }}</div>
              @endif
              <form
                method="POST"
                action="{{ route('roles.update', $role->id) }}"
              >
                @csrf
                @method('PUT')
                <input
                  type="hidden"
                  name="id"
                  value="{{ $role->id }}"
                />
                <table class="table table-bordered datatable">
                  <tbody>
                    <tr>
                      <th>
                        Name
                      </th>
                      <td>
                        <input
                          class="form-control"
                          name="name"
                          value="{{ $role->name }}"
                          type="text"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
                <button
                  class="btn btn-primary"
                  type="submit"
                >Save</button>
                <a
                  class="btn btn-primary"
                  href="{{ route('roles.index') }}"
                >Return</a>
              </form>
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
