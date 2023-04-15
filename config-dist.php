<?php

// Settings can also be overwritten:
//
// First priority:
// An environment variable with the same name as the setting and prefix "PIKSI_"
// so for example "PIKSI_BASE_URL"
//
// Second priority:
// The settings defined here below

// Either "production", "dev" or "demo"
Setting('MODE', 'production');

// The localization to use, must exist as a file "<locale>.po"
// in the /localization directory (e.g. "en" or "de")
Setting('LOCALE', 'en');

// When running in a subdirectory, this should be set to the relative path, otherwise empty
// It needs to be set to the part (of the URL) AFTER the document root,
// if URL rewriting is disabled, including index.php
// Example with URL Rewriting support:
//   Root URL = https://example.com/piksi
//   => BASE_PATH = /piksi
// Example without URL Rewriting support:
//   Root URL = https://example.com/piksi/public/index.php/
//   => BASE_PATH = /piksi/public/index.php
Setting('BASE_PATH', '');

// The base URL of your installation,
// should be just "/" when running directly under the root of a (sub)domain
// or for example "https://example.com/piksi" when using a subdirectory
Setting('BASE_URL', '/');

// If, however, your webserver does not support URL rewriting, set this to true
Setting('DISABLE_URL_REWRITING', false);

// The folders to show / scan for media files
// For deviating sorting: When next to a folder a (text) file "<original folder name>.sort" exists,
// the content of this file is used for sorting instead of the folder name
Setting('FOLDERS', [
	[
		'name' => 'Folder1',
		'path' => '/var/data/pictures/folder1',
		'sort' => 'desc' // "asc" (ascending) or "desc" (descending), defaults to "asc" when omitted
	],
	[
		'name' => 'Folder2',
		'path' => '/var/data/pictures/folder2',
		'show_filenames' => true // Whether to show filenames or not, defaults to "SHOW_FILENAMES" (see below) when omitted
	]
]);

// File extensions (without leading dot) of photos to show
// Leave empty to not show photos
Setting('PICTURE_FILEEXT', ['png', 'jpg']);

// File extensions (without leading dot) of videos to show
// Leave empty to not show videos
Setting('VIDEO_FILEEXT', ['mp4']);

// File extensions (without leading dot) of audios to show
// Leave empty to not show audios
Setting('AUDIO_FILEEXT', ['mp3']);

// When this file exists per album folder, it will be shown instead of the folder name
Setting('ALBUM_COVER_FILENAME', '__album.jpg');

// When in this subfolder (per album folder) a same named picture file exists,
// it is used as the thumbnail (only for pictures)
Setting('THUMBS_FOLDER_NAME', '__thumbs');

// Whether to play videos inline (=> true) or only in a lightbox (=> false)
Setting('PLAY_VIDEOS_INLINE', false);

// Whether to show filenames or not by default
// (this can also be set per folder, see above)
Setting('SHOW_FILENAMES', false);

// When this is set, this is used / shown as the application title (instead of "Piksi")
Setting('OVERWRITE_TITLE', '');
