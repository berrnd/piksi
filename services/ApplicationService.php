<?php

namespace Piksi\Services;

class ApplicationService extends BaseService
{
	private $InstalledVersion;

	public function GetInstalledVersion()
	{
		if ($this->InstalledVersion == null)
		{
			$this->InstalledVersion = json_decode(file_get_contents(__DIR__ . '/../version.json'));
		}

		return $this->InstalledVersion;
	}

	public function GetSystemInfo()
	{
		return [
			'piksi_version' => $this->GetInstalledVersion(),
			'php_version' => phpversion(),
			'os' => php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('v') . ' ' . php_uname('m'),
			'client' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown'
		];
	}
}
