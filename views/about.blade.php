@extends('layout.default')

@section('title', $__t('About Piksi'))

@section('content')
<div class="row d-flex justify-content-center">
	<div class="col-12 col-md-6 text-center">
		<h2 class="title">@yield('title')</h2>

		<div class="border-top py-2">
			Version <code>{{ $version }}</code><br>
			{{ $__t('Released on') }} <code>{{ $releaseDate }}</code> <time class="timeago timeago-contextual"
				datetime="{{ $releaseDate }}"></time>
		</div>

		<div class="py-2">
			PHP Version <code>{{ $system_info['php_version'] }}</code><br>
			OS <code>{{ $system_info['os'] }}</code><br>
			Client <code>{{ $system_info['client'] }}</code>
		</div>

		<div class="border-top py-2">
			{{ $__t('Do you find Piksi useful?') }}<br>
			<a class="btn btn-sm btn-primary text-white mt-1"
				href="https://berrnd.de/say-thanks?project=Piksi&version={{$version}}"
				target="_blank">{{ $__t('Say thanks') }} <i class="fa-solid fa-heart"></i></a>
		</div>

		<div class="small border-top">
			<div class="small text-muted pt-2">
				Piksi is a project by
				<a href="https://berrnd.de"
					class="text-decoration-none link-dark"
					target="_blank">Bernd Bestel</a><br>
				Created with passion since 2023<br>
				Life runs on code<br>
				<a href="https://github.com/berrnd/piksi"
					class="text-decoration-none link-dark"
					target="_blank">
					<i class="fa-brands fa-github"></i>
				</a>
			</div>
		</div>
	</div>
</div>
@stop
