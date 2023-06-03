@extends('layout.default')

@section('title', $__t('Overview'))

@section('content')
<div class="row d-flex justify-content-center">

	@foreach($rootFolders as $folder)
	<div class="col-12 col-xl-4 pb-2">
		<a href="{{ $U('/?folder='. $loop->index . '&path=' . urlencode('/')) }}"
			class="discrete-link">
			<div class="card text-center">
				<div class="card-header fs-2 fw-semibold">
					{{ $folder['name'] }}

					@if(!empty($folder['badgeText']))
					<span class="position-absolute top-0 end-0 badge mt-2 me-2 bg-info fs-3">
						{{ $folder['badgeText'] }}
					</span>
					@endif
				</div>
				<div class="card-body fs-3">
					@if($folder['foldersCount'] > 0)
					{{ $__n($folder['foldersCount'], '%1$s album', '%1$s albums') }}<br>
					@endif
					@if($folder['picturesCount'] > 0)
					{{ $__n($folder['picturesCount'], '%1$s picture', '%1$s pictures') }}<br>
					@endif
					@if($folder['videosCount'] > 0)
					{{ $__n($folder['videosCount'], '%1$s video', '%1$s videos') }}<br>
					@endif
					@if($folder['audiosCount'] > 0)
					{{ $__n($folder['audiosCount'], '%1$s audio', '%1$s audios') }}
					@endif
				</div>
			</div>
		</a>
	</div>
	@endforeach

</div>

@if(count($specialItems) > 0)
<div class="row d-flex justify-content-center">
	@foreach($specialItems as $item)
	<div class="col-12 col-xl-4 d-flex justify-content-center @if(!$item['show_filename']) pb-2 @endif">
		@include('item', [
		'item' => $item
		])
	</div>
	@endforeach
</div>
@endif
@stop
