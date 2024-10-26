@if($item['type'] != 'folder' && ($item['showFilename'] || !empty($item['badgeText'])))
<figure class="figure @if($item['type'] == 'audio') w-100 @endif">
	@endif

	@if($item['type'] == 'picture')
	<a class="show-as-dialog-image"
		href="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath'])) }}">
		<img class="img-fluid img-thumbnail"
			loading="lazy"
			src="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['thumbRelativePath'])) }}">
	</a>
	@elseif($item['type'] == 'video' && PIKSI_PLAY_VIDEOS_INLINE)
	<video controls
		class="img-fluid img-thumbnail video-inline"
		preload="metadata">
		<source src="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath'])) }}">
	</video>
	@elseif($item['type'] == 'video' && !PIKSI_PLAY_VIDEOS_INLINE)
	<a class="show-as-dialog-video"
		href="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath'])) }}">
		<div class="text-center position-relative">
			<button class="btn btn-lg fs-1 btn-primary position-absolute top-50 start-50 translate-middle opacity-75 rounded-pill">
				<i class="fa-regular fa-circle-play"></i>
			</button>
			<video class="img-fluid img-thumbnail"
				preload="metadata">
				<source src="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath'])) }}">
			</video>
		</div>
	</a>
	@elseif($item['type'] == 'audio')
	<audio class="img-fluid img-thumbnail w-100"
		style="height: 50px;"
		controls
		preload="metadata">
		<source src="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath'])) }}">
	</audio>
	@elseif($item['type'] == 'folder')
	<a href="{{$U('/?folder='. $item['folderIndex'] . '&path=' . urlencode($item['relativePath']))}}"
		class="discrete-link">
		<div class="card text-center">
			@if(empty($item['coverImagePathRelative']))
			<div class="card-header fs-2 fw-semibold">
				{{ $item['name'] }}

				@if(!empty($item['badgeText']) && empty($item['coverImagePathRelative']))
				<span class="badge bg-info fs-3">
					{{ $item['badgeText'] }}
				</span>
				@endif
			</div>
			@else
			<img class="card-img-top"
				loading="lazy"
				src="{{ $U('/file?folder='. $item['folderIndex'] . '&path=' . urlencode($item['coverImagePathRelative'])) }}">
			@endif
			<div class="card-body fs-3">
				@if(!empty($item['badgeText']) && !empty($item['coverImagePathRelative']))
				<span class="position-absolute top-0 end-0 badge mt-2 me-2 bg-info fs-3">
					{{ $item['badgeText'] }}
				</span>
				@endif

				@if($item['foldersCount'] > 0)
				<span class="folder-item-count">{{ $__n($item['foldersCount'], '%1$s album', '%1$s albums') }}</span>
				@endif
				@if($item['picturesCount'] > 0)
				<span class="folder-item-count">{{ $__n($item['picturesCount'], '%1$s picture', '%1$s pictures') }}</span>
				@endif
				@if($item['videosCount'] > 0)
				<span class="folder-item-count">{{ $__n($item['videosCount'], '%1$s video', '%1$s videos') }}</span>
				@endif
				@if($item['audiosCount'] > 0)
				<span class="folder-item-count">{{ $__n($item['audiosCount'], '%1$s audio', '%1$s audios') }}</span>
				@endif
			</div>
		</div>
	</a>
	@endif

	@if($item['type'] != 'folder' && ($item['showFilename'] || !empty($item['badgeText'])))
	<figcaption class="figure-caption text-center">
		@if($item['showFilename'])
		{{ $item['name'] }}
		@endif

		@if(!empty($item['badgeText']))
		@if($item['showFilename'])
		<br>
		@endif
		<span class="badge bg-info fs-4 @if($item['showFilename']) mt-1 @endif">
			{{ $item['badgeText'] }}
		</span>
		@endif
	</figcaption>
</figure>
@endif
