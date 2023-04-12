@extends('layout.default')

@if(empty($path) || $path == '/' || $path == '.')
@section('title', PIKSI_FOLDERS[$folderIndex]['name'])
@section('navigationTitle', PIKSI_FOLDERS[$folderIndex]['name'])
@else
@section('title', PIKSI_FOLDERS[$folderIndex]['name'] . str_replace('/', ' / ', $path))
@section('navigationTitle', PIKSI_FOLDERS[$folderIndex]['name'] . str_replace('/', ' / ', $path))
@endif

@push('pageStyles')
<style>
	.card-img-top {
		width: 100%;
		height: 20vw;
		object-fit: cover;
	}
</style>
@endpush

@if((count(PIKSI_FOLDERS) == 1 && $path != '/') || count(PIKSI_FOLDERS) > 1)
@section('navbarAdditional')
<a class="btn btn-light me-auto ms-3"
	href="@if($path == '/') {{ $U('/') }} @else {{ $U('/?folder='. $folderIndex . '&path=' . str_replace('\\', '/', dirname($path))) }}  @endif">
	&larr; {{ $__t('Back') }}
</a>
@stop
@endif

@section('content')
<div class="row d-flex justify-content-center">

	@foreach($items as $item)
	<div class="col-12 col-xl-4 pb-2 @if($item['type'] != 'folder') d-flex justify-content-center @endif">
		@if($item['type'] == 'picture')
		<a class="show-as-dialog-image"
			href="{{ $U('/file?folder='. $folderIndex . '&path=' . $item['relativePath']) }}">
			<img class="img-fluid img-thumbnail"
				loading="lazy"
				src="{{ $U('/file?folder='. $folderIndex . '&path=' . $item['thumbRelativePath']) }}">
		</a>
		@elseif($item['type'] == 'video')
		<a class="show-as-dialog-video"
			href="{{ $U('/file?folder='. $folderIndex . '&path=' . $item['relativePath']) }}">
			<div class="text-center position-relative">
				<button class="btn btn-lg fs-1 btn-primary position-absolute top-50 start-50 translate-middle opacity-75 rounded-pill">
					<i class="fa-regular fa-circle-play"></i>
				</button>
				<video class="img-fluid img-thumbnail"
					preload="metadata"
					<source
					src="{{ $U('/file?folder='. $folderIndex . '&path=' . $item['relativePath']) }}">
				</video>
			</div>
		</a>
		@elseif($item['type'] == 'folder')
		<a href="{{$U('/?folder='. $folderIndex . '&path=' . $item['relativePath'])}}"
			class="discrete-link">
			<div class="card text-center">
				@if(empty($item['coverImagePathRelative']))
				<div class="card-header fs-2">
					{{ $item['name'] }}
				</div>
				@else
				<img class="card-img-top"
					loading="lazy"
					src="{{ $U('/file?folder='. $folderIndex . '&path=' . $item['coverImagePathRelative']) }}">
				@endif
				<div class="card-body fs-3">
					@if($item['foldersCount'] > 0)
					{{ $__n($item['foldersCount'], '%1$s album', '%1$s albums') }}<br>
					@endif
					@if($item['picturesCount'] > 0)
					{{ $__n($item['picturesCount'], '%1$s picture', '%1$s pictures') }}<br>
					@endif
					@if($item['videosCount'] > 0)
					{{ $__n($item['videosCount'], '%1$s video', '%1$s videos') }}
					@endif
				</div>
			</div>
		</a>
		@endif
	</div>
	@endforeach

</div>
@stop
