<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>Dashboard | {{ SettingHelper::siteTitle() }}</title>

	<link rel="shortcut icon" href="{{ SettingHelper::siteFavicon() }}">

	<!-- General CSS Files -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	<!-- Template CSS -->
	<link rel="stylesheet" href="{{ asset('/stisla/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('/stisla/css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset('/stisla/css/components.css') }}">

	<!-- CSS Libraries -->
	@stack('styles')

</head>

<body>
	@include('sweetalert::alert')
	
	<div id="app">
		<div class="main-wrapper @yield('wrapper')">

			@include('layouts.admin.topbar')

			@include('layouts.admin.sidebar')

			<!-- Main Content -->
			<div class="main-content">
				<section class="section">
					@yield('content_header')
					@yield('content_body')
				</section>
			</div>

			@include('layouts.admin.footer')

		</div>
	</div>

	@yield('bottom')
	
	<!-- General JS Scripts -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

	<!-- JS Libraries -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	<script src="{{ asset('/vendor/jsvalidation/js/jsvalidation.min.js') }}"></script>
	
	<!-- Stisla Script -->
	<script src="{{ asset('/stisla/js/stisla.js') }}"></script>
	<script src="{{ asset('/stisla/js/scripts.js') }}"></script>

	<!-- Custom Script -->
	<script src="{{ asset('/stisla/js/custom.js') }}"></script>

	<script>
		var profile_route = {
			'get': '{{ route('admin.profile.index') }}',
			'get_active': '{{ route('admin.profile.get_active') }}',
			'update_active': '{{ route('admin.profile.update_active') }}',
			'get_preview': '{{ route('admin.profile.get_preview') }}',
			'update_preview': '{{ route('admin.profile.update_preview') }}',
			'store': '{{ route('admin.profile.store') }}',
			'update': '{{ route('admin.profile.update') }}',
			'destroy': '{{ route('admin.profile.destroy') }}',
		}
	</script>

	@stack('scripts')

</body>
</html>