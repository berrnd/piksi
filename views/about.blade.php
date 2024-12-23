@extends('layout.default')

@section('title', $__t('About Piksi'))

@section('content')
<div class="row d-flex justify-content-center">
	<div class="col-12 col-md-6 text-center">
		<h2 class="title">@yield('title')</h2>

		<div class="border-top py-2">
			<table class="table table-borderless table-responsive table-sm text-start text-nowrap">
				<tr>
					<td class="text-end">Version</td>
					<td><code>{{ $versionInfo->Version }}</code></td>
				</tr>
				<tr>
					<td class="text-end">{{ $__t('Released on') }}</td>
					<td><code>{{ $versionInfo->ReleaseDate }}</code> <time class="timeago timeago-contextual"
							datetime="{{ $versionInfo->ReleaseDate }}"></time></td>
				</tr>
				<tr>
					<td class="text-end">PHP Version</td>
					<td><code>{{ $systemInfo['php_version'] }}</code></td>
				</tr>
				<tr>
					<td class="text-end">OS</td>
					<td><code>{{ $systemInfo['os'] }}</code></td>
				</tr>
				<tr>
					<td class="text-end">Client</td>
					<td><code>{{ $systemInfo['client'] }}</code></td>
				</tr>
			</table>
		</div>

		<div class="border-top py-2">
			{{ $__t('Do you find Piksi useful?') }}<br>
			<a class="btn btn-sm btn-primary text-white mt-1"
				href="https://berrnd.de/say-thanks?project=Piksi&version={{$version}}"
				target="_blank">{{ $__t('Say thanks') }} <i class="fa-solid fa-heart"></i></a>
		</div>

		<div class="small border-top">
			<div class="small text-muted pt-2">
				Piksi is a hobby project by
				<a href="https://berrnd.de"
					class="text-decoration-none link-dark"
					target="_blank">Bernd Bestel</a><br>
				Created with passion since 2023<br>
				Life runs on Code
			</div>
		</div>
	</div>
</div>
@stop
