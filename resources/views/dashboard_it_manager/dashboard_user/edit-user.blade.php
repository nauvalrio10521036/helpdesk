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
                    <h6 class="page-title-heading mr-0 mr-r-5">Form Users</h6>
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
                    <div class="col-md-6 mx-auto widget-holder">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <h5 class="box-title mr-b-0">Edit Users Data</h5>
                                <p class="text-muted">Form edit user</p>

                                <form action="{{ url('update-user/'.$user->user_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Name</label>
                                        <div class="col-sm-9">
                                            <input name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Username</label>
                                        <div class="col-sm-9">
                                            <input name="username" class="form-control" value="{{ $user->username }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Password</label>
                                        <div class="col-sm-9">
                                            <input name="password" type="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Role</label>
                                        <div class="col-sm-9">
                                            <select name="role" class="form-control" required>
                                                <option value="">Pilih</option>
                                                <option value="resepsionis" {{ $user->role == 'resepsionis' ? 'selected' : '' }}>Resepsionis</option>
                                                <option value="it_manager" {{ $user->role == 'it_manager' ? 'selected' : '' }}>IT Manager</option>
                                                <option value="hotel_manager" {{ $user->role == 'hotel_manager' ? 'selected' : '' }}>Hotel Manager</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ml-auto col-sm-9 no-padding">
                                        <button class="btn btn-warning" type="submit">Update</button>
                                    </div>
                                </form>

                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-bg -->
                    </div><!-- /.widget-holder -->
                </div><!-- /.row -->
            </div><!-- /.widget-list -->
        </main><!-- /.main-wrapper -->

        @include('partials.footer')
    </div><!-- /#wrapper -->
</body>
</html>
