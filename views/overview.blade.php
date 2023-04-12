@extends('layout.default')

@section('title', $__t('Overview'))

@section('content')
<div class="row d-flex justify-content-center">

	@foreach($rootFolders as $folder)
	<div class="col-12 col-xl-4 pb-2">
		<a href="{{ $U('/?folder='. $loop->index . '&path=/') }}"
			class="discrete-link">
			<div class="card text-center">
				<div class="card-header fs-2">
					{{ $folder['name'] }}
				</div>
				<div class="card-body fs-3">
					@if($folder['foldersCount'] > 0)
					{{ $__n($folder['foldersCount'], '%1$s album', '%1$salbums') }}<br>
					@endif
					@if($folder['picturesCount'] > 0)
					{{ $__n($folder['picturesCount'], '%1$s picture', '%1$s pictures') }}<br>
					@endif
					@if($folder['videosCount'] > 0)
					{{ $__n($folder['videosCount'], '%1$s video', '%1$s videos') }}
					@endif
				</div>
			</div>
		</a>
	</div>
	@endforeach

</div>
@stop
