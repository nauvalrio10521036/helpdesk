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
							<h5 class="box-title mr-b-0">Input Users Data</h5>
	            <p class="text-muted " >Form users</p>

							<form action="{{ url('store-user') }}" method="POST">
    @csrf

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Name</label>
        <div class="col-sm-9">
            <input name="name" class="form-control" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Username</label>
        <div class="col-sm-9">
            <input name="username" class="form-control" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Password</label>
        <div class="col-sm-9">
            <input name="password" type="password" class="form-control" required>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-sm-3">Role</label>
        <div class="col-sm-9">
            <select name="role" class="form-control" required>
                <option value="">Pilih</option>
                <option value="resepsionis">Resepsionis</option>
                <option value="it_manager">IT Manager</option>
                <option value="hotel_manager">Hotel Manager</option>
            </select>
        </div>
    </div>

    <div class="ml-auto col-sm-9 no-padding">
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</form>

						</div><!-- /.widget-body -->
					</div><!-- /.widget-bg -->
				</div><!-- /.widget-holder -->

			</div><!-- /.row -->
		</div><!-- /.widget-list -->
	</main><!-- /.main-wrappper -->

    @include('partials.footer')
</body>
</html>