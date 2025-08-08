<!DOCTYPE html>
<html lang="en">
@include('partials.header')

<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
        @include('partials.nav')
        @include('partials.sidebar')

        <main class="main-wrapper clearfix">
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Pengelolaan Pengguna</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block"></p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
            </div>

            <div class="widget-list">
                <div class="row">
                    <div class="m-3 mr-t-40">
                        <a href="{{ route('dashboard_it_manager.create-user') }}"
                            class="btn btn-primary btn-block btn-xl px-4 my-0 fs-16 ml-auto">
                            <span><i class="material-icons">add_box</i> Add User</span>
                        </a>
                    </div>

                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-heading clearfix">
                                <h5>Tabel Daftar Pengguna</h5>
                            </div>

                            <div class="widget-body clearfix">
                                <table class="table table-editable table-responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Role</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>{{ $user->user_id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->username }}</td>
                                                <td>••••••••</td>
                                                <td>{{ $user->role }}</td>
                                                <td>{{ $user->created_at }}</td>
                                                <td>{{ $user->updated_at }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('dashboard_it_manager.edit-user', $user->user_id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="material-icons">create</i> Edit
                                                    </a>

                                                    <form action="{{ route('user.destroy', $user->user_id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Hapus user ini?')">
                                                            <i class="material-icons">delete_sweep</i> Delete
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">No users found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-bg -->
                    </div><!-- /.widget-holder -->
                </div>
            </div>
        </main>

    </div>
    @include('partials.footer')
</body>

</html>
