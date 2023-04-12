<?php

function ExternalSettingValue(string $value)
{
	$tvalue = rtrim($value, "\r\n");
	$lvalue = strtolower($tvalue);

	if ($lvalue === 'true')
	{
		return true;
	}
	elseif ($lvalue === 'false')
	{
		return false;
	}

	return $tvalue;
}

function Setting(string $name, $value)
{
	if (!defined('PIKSI_' . $name))
	{
		if (getenv('PIKSI_' . $name) !== false)
		{
			// An environment variable with the same name and prefix PIKSI_ overwrites the given setting
			define('PIKSI_' . $name, ExternalSettingValue(getenv('PIKSI_' . $name)));
		}
		else
		{
			define('PIKSI_' . $name, $value);
		}
	}
}

function string_starts_with($haystack, $needle)
{
	return (substr($haystack, 0, strlen($needle)) === $needle);
}
