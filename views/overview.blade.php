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
					<span class="badge bg-info fs-3">
						{{ $folder['badgeText'] }}
					</span>
					@endif
				</div>
				<div class="card-body fs-3">
					@if($folder['foldersCount'] > 0)
					<span class="folder-item-count">{{ $__n($folder['foldersCount'], '%1$s album', '%1$s albums') }}</span>
					@endif
					@if($folder['picturesCount'] > 0)
					<span class="folder-item-count">{{ $__n($folder['picturesCount'], '%1$s picture', '%1$s pictures') }}</span>
					@endif
					@if($folder['videosCount'] > 0)
					<span class="folder-item-count">{{ $__n($folder['videosCount'], '%1$s video', '%1$s videos') }}</span>
					@endif
					@if($folder['audiosCount'] > 0)
					<span class="folder-item-count">{{ $__n($folder['audiosCount'], '%1$s audio', '%1$s audios') }}</span>
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
	<div class="col-12 col-xl-4 d-flex justify-content-center @if(!$item['showFilename']) pb-2 @endif">
		@include('item', [
		'item' => $item
		])
	</div>
	@endforeach
</div>
@endif
@stop
