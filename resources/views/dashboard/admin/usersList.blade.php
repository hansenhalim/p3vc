@extends('dashboard.base')

@section('content')

  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <x-card-header>User List</x-card-header>
            <div class="card-body">
              <a href="{{ route('register') }}" class="btn btn-primary mb-2">Register</a>
              <table class="table table-responsive-sm table-striped">
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>E-mail</th>
                    <th>Roles</th>
                    <th>Email verified at</th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->menuroles }}</td>
                      <td>{{ $user->email_verified_at }}</td>
                      <td>
                        <a href="{{ url('/users/' . $user->id) }}" class="btn btn-block btn-primary">View</a>
                      </td>
                      <td>
                        <a href="{{ url('/users/' . $user->id . '/edit') }}" class="btn btn-block btn-primary">Edit</a>
                      </td>
                      <td>
                        @if ($you->id !== $user->id)
                          <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-block btn-danger">Delete</button>
                          </form>
                        @endif
                      </td>
                    </tr>
                  @endforeach
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
