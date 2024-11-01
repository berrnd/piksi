-----

<div align="center">
<img alt="Logo" height="50" src="https://raw.githubusercontent.com/berrnd/piksi/main/public/img/logo.svg?sanitize=true" />
<h2>A web-based self-hosted media gallery focused on simplicity. It displays photos, videos and audios from an album based folder structure in an easy to use yet beautiful way.
<em><h4>This is a hobby project by <a href="https://berrnd.de">Bernd Bestel</a></h4></em>
</div>

-----

## Give it a try

- Public demo of the latest stable version (`release` branch) &rarr; [https://piksi-demo.berrnd.xyz](https://piksi-demo.berrnd.xyz)

## Features / Motivation

Lately I've been digitizing a large number of photos and wanted to make them accessible to older family members. There are a lot of web-based image galleries out there and I kind of tried them all, but none of them met my expectations regarding a simple user interface that would work for people who didn't grow up with computers.

I wanted to have a tool that

- displays media files from an existing folder structure (one album per folder, unlimited subfolders are supported)
- uses the folder name for albums or alternatively a cover image (configurable naming convention)
- uses pre-generated thumbnails (from a subfolder per album, again configurable naming convention)
- has a strong focus on simplictiy - that explicitly means:
  - no file management, sharing or similar gallery organization options (it's just a viewer)
  - any technical information is hidden by default, optionally filenames can be shown
  - it doesn't have user management (means no authentication, so run this in a trusted local network only)

to make those family memories of yesterday accessible on devices of today, but without the overly complex stuff which practically often discourages said target audience to have fun with modern things. I haven't found that, so this is Piksi.

## Questions / Help / Bug Reports / Feature Requests

Please use the [Issue Tracker](https://github.com/berrnd/piksi/issues/new/choose) for any requests.

## How to install

Piksi is technically a pretty simple PHP application, so the basic notes to get it running are:

- Unpack the [latest release ZIP](https://github.com/berrnd/piksi/releases/latest)
- Copy `config-dist.php` to `data/config.php` + edit to your needs (all config options should be self-explanatory based on the comments there)
- Ensure that the `data` directory is writable
- The webserver root should point to the `public` directory
- Include `try_files $uri /index.php$is_args$query_string;` in your location block if you use nginx
  - Or disable URL rewriting (see the option `DISABLE_URL_REWRITING` in `data/config.php`)

Alternatively clone this repository (the `release` branch always references the latest released version) and install Composer and Yarn dependencies manually.

### Platform support

- PHP 8.2 or 8.3
  - Required PHP extensions: `fileinfo`, `ctype`, `intl`, `zlib`, `mbstring`
- Recent Firefox, Chrome or Edge

## How to update

- Overwrite everything with the [latest release ZIP](https://github.com/berrnd/piksi/releases/latest) while keeping the `data` directory
- Check `config-dist.php` for new configuration options and add them to your `data/config.php` where appropriate (the default values from `config-dist.php` will be used for not in `data/config.php` defined settings)
- Empty the `data/viewcache` directory

## Localization

Piksi is fully localizable - the default language is English (integrated into code), a German localization is always maintained by me.

You can easily help translating Piksi:

- Copy `localization/en.po` to e.g. `localization/it.po` to start the italian translation
- Translate all strings (lines starting with `msgstr`) in the `.po` file
- Test it by changing the option `LOCALE` in `data/config.php`
- Submit a pull request if you want to have the translations included for everyone

## Things worth to know

### Adding your own CSS or JS without to have to modify the application itself

- When the file `data/custom_js.html` exists, the contents of the file will be added just before `</body>` (end of body) on every page
- When the file `data/custom_css.html` exists, the contents of the file will be added just before `</head>` (end of head) on every page

## Contributing / Say Thanks

Any help is welcome, feel free to contribute anything which comes to your mind or see [https://berrnd.de/say-thanks](https://berrnd.de/say-thanks?project=Piksi) if you just want to say thanks.

## Roadmap

There is none. The progress of a specific bug/enhancement is always tracked in the corresponding issue, at least by commit comment references.

## Screenshots

![overview](https://github.com/berrnd/piksi/raw/main/.github/publication_assets/overview.png "overview")

![cover_albums](https://github.com/berrnd/piksi/raw/main/.github/publication_assets/cover_albums.png "cover_albums")

![gallery](https://github.com/berrnd/piksi/raw/main/.github/publication_assets/gallery.png "gallery")

![gallery_with_videos](https://github.com/berrnd/piksi/raw/main/.github/publication_assets/gallery_with_videos.png "gallery_with_videos")

![lightbox](https://github.com/berrnd/piksi/raw/main/.github/publication_assets/lightbox.png "lightbox")

## License

The MIT License (MIT)
