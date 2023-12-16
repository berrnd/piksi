<?php

namespace Piksi\Helpers;

const REQUIRED_PHP_EXTENSIONS = ['fileinfo', 'ctype', 'intl', 'zlib', 'mbstring'];
const REQUIRED_PHP_VERSION = '8.2.0';

class RequirementsChecker
{
	public function CheckEnvironment()
	{
		$phpVersion = phpversion();
		if (version_compare($phpVersion, REQUIRED_PHP_VERSION, '<'))
		{
			throw new \Exception('PHP ' . REQUIRED_PHP_VERSION . ' is required, you are running ' . $phpVersion);
		}

		foreach (REQUIRED_PHP_EXTENSIONS as $extension)
		{
			if (!in_array($extension, get_loaded_extensions()))
			{
				throw new \Exception("PHP module '{$extension}' not installed, but required");
			}
		}

		if (!file_exists(PIKSI_DATAPATH . '/config.php'))
		{
			throw new \Exception('config.php in data directory (' . PIKSI_DATAPATH . ') not found - have you copied config-dist.php to the data directory and renamed it to config.php?');
		}

		if (!file_exists(__DIR__ . '/../config-dist.php'))
		{
			throw new \Exception('/config-dist.php not found - please do not remove this file');
		}

		if (!file_exists(__DIR__ . '/../packages/autoload.php'))
		{
			throw new \Exception('/packages/autoload.php not found - have you run Composer?');
		}

		if (!is_writable(PIKSI_DATAPATH))
		{
			throw new \Exception('Data directory (' . PIKSI_DATAPATH . ') is not writable');
		}
	}

	public function CheckConfig()
	{
		$allowedModes = ['production', 'dev', 'demo'];
		if (!in_array(PIKSI_MODE, $allowedModes))
		{
			throw new \Exception('Invalid mode "' . PIKSI_MODE . '" set, only ' . join(', ', $allowedModes) . ' allowed');
		}

		if (!file_exists(__DIR__ . '/../localization/' . PIKSI_LOCALE . '.po'))
		{
			throw new \Exception('Invalid locale "' . PIKSI_LOCALE . '" set, file "/localization/' . PIKSI_LOCALE . '.po' . '" doesn\'t exist');
		}

		foreach (PIKSI_FOLDERS as $folder)
		{
			if (!is_dir($folder['path']))
			{
				throw new \Exception('Folder "' . $folder['name'] . '" (' . $folder['path'] . ') doesn\'t exist or is not accessible');
			}
		}
	}
}
