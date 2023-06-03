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
	href="@if($path == '/') {{ $U('/') }} @else {{ $U('/?folder='. $folderIndex . '&path=' . urlencode(str_replace('\\', '/', dirname($path)))) }}  @endif">
	<i class="fa-solid fa-left-long"></i> {{ $__t('Back') }}
</a>
@stop
@endif

@section('content')
<div class="row d-flex justify-content-center">

	@foreach($items as $item)
	<div class="col-12 col-xl-4 @if($item['type'] == 'folder' || !$item['showFilename']) pb-2 @endif @if($item['type'] != 'folder') d-flex justify-content-center @endif">
		@include('item', [
		'item' => $item
		])
	</div>
	@endforeach

</div>
@stop
