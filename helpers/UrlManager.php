<?php

namespace Piksi\Helpers;

class UrlManager
{
	public function __construct(string $basePath)
	{
		if ($basePath === '/')
		{
			$this->BasePath = $this->GetBaseUrl();
		}
		else
		{
			$this->BasePath = $basePath;
		}
	}

	protected $BasePath;

	public function ConstructUrl($relativePath, $isResource = false)
	{
		if (PIKSI_DISABLE_URL_REWRITING === false || $isResource === true)
		{
			return rtrim($this->BasePath, '/') . $relativePath;
		}
		else
		{
			// Is not a resource and URL rewriting is disabled
			return rtrim($this->BasePath, '/') . '/index.php' . $relativePath;
		}
	}

	private function GetBaseUrl()
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
		{
			$_SERVER['HTTPS'] = 'on';
		}

		return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
	}
}
