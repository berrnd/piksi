<!DOCTYPE html>
<html lang="{{ PIKSI_LOCALE }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport"
		content="width=device-width, initial-scale=1">

	<meta name="robots"
		content="noindex,nofollow">

	<link rel="icon"
		type="image/png"
		sizes="32x32"
		href="{{ $U('/img/icon-32.png?v=', true) }}{{ $version }}">
	<link rel="manifest"
		href="{{ $U('/manifest.json?v=', true) }}{{ $version }}">

	<title>@yield('title') | {{$title}}</title>

	<link href="{{ $U('/node_modules/@fontsource/noto-sans/latin.css?v=', true) }}{{ $version }}"
		rel="stylesheet">
	<link href="{{ $U('/node_modules/bootstrap/dist/css/bootstrap.min.css?v=', true) }}{{ $version }}"
		rel="stylesheet">
	<link href="{{ $U('/node_modules/@fortawesome/fontawesome-free/css/all.min.css?v=', true) }}{{ $version }}"
		rel="stylesheet">
	<link href="{{ $U('/piksi.css?v=', true) }}{{ $version }}"
		rel="stylesheet">

	@stack('pageStyles')

	@if(file_exists(PIKSI_DATAPATH . '/custom_css.html'))
	@php include PIKSI_DATAPATH . '/custom_css.html' @endphp
	@endif

	<script>
		var Piksi = { };
		Piksi.Mode = '{{ PIKSI_MODE }}';
		Piksi.BaseUrl = '{{ $U('/') }}';
		Piksi.LocalizationStrings = {!! $LocalizationStrings !!};
	</script>
</head>

<body class="@if($embedded) embedded @else bg-light @endif">
	@if(!($embedded))
	<nav id="mainNav"
		class="navbar navbar sticky-top navbar-dark bg-secondary">

		<div class="container-fluid">
			<a class="navbar-brand mb-0 h1"
				href="{{ $U('/') }}">
				{{$title}}
			</a>

			<span class="navbar-text text-white @sectionMissing('navbarAdditional') me-auto @endif">
				@yield('navigationTitle')
			</span>

			@yield('navbarAdditional')

			<span id="about-link"
				class="navbar-text small">
				<a class="show-as-dialog-link text-decoration-none"
					href="{{ $U('/about?embedded') }}">{{ $__t('About') }}</a>
			</span>
		</div>

	</nav>
	@endif

	<div class="content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div id="page-content"
					class="col pt-2">

					@yield('content')
				</div>
			</div>
		</div>
	</div>

	<script src="{{ $U('/node_modules/jquery/dist/jquery.min.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/node_modules/bootbox/dist/bootbox.min.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/node_modules/sprintf-js/dist/sprintf.min.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/node_modules/gettext-translator/dist/translator.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/node_modules/moment/min/moment.min.js?v=', true) }}{{ $version }}"></script>
	@if(!empty($__t('moment_locale') && $__t('moment_locale') != 'x'))<script src="{{ $U('/node_modules', true) }}/moment/locale/{{ $__t('moment_locale') }}.js?v={{ $version }}"></script>@endif
	<script src="{{ $U('/extensions.js?v=', true) }}{{ $version }}"></script>
	<script src="{{ $U('/piksi.js?v=', true) }}{{ $version }}"></script>

	@stack('pageScripts')

	@if(file_exists(PIKSI_DATAPATH . '/custom_js.html'))
	@php include PIKSI_DATAPATH . '/custom_js.html' @endphp
	@endif
</body>

</html>
