@extends('layouts.app_master_admin')
@section('content')
	<section class="content-header">
		<h1>
			403 Permission
		</h1>
	</section>
	<section class="content">

		<div class="error-page">
			<h2 class="headline text-red">403</h2>

			<div class="error-content">
				<h3><i class="fa fa-warning text-red"></i> Sorry! You do not have access .</h3>

				<p>Please contact the admin for access</p>

{{--				<form class="search-form">--}}
{{--					<div class="input-group">--}}
{{--						<input type="text" name="search" class="form-control" placeholder="Search">--}}

{{--						<div class="input-group-btn">--}}
{{--							<button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i>--}}
{{--							</button>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</form>--}}
			</div>
		</div>
		<!-- /.error-page -->
	</section>
@stop