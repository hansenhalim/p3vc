@extends('dashboard.base')

@section('content')


  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <x-card-header>Create Menu Element</x-card-header>
            <div class="card-body">
              @if (Session::has('message'))
                <div
                  class="alert alert-success"
                  role="alert"
                >{{ Session::get('message') }}</div>
              @endif

              <form
                action="{{ route('menu.menu.update') }}"
                method="POST"
              >
                @csrf
                <input
                  type="hidden"
                  name="id"
                  value="{{ $menulist->id }}"
                  id="menuElementId"
                />
                <table class="table table-striped table-bordered datatable">
                  <tbody>
                    <tr>
                      <th>
                        Name
                      </th>
                      <td>
                        <input
                          type="text"
                          name="name"
                          class="form-control"
                          value="{{ $menulist->name }}"
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
                  href="{{ route('menu.menu.index') }}"
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
