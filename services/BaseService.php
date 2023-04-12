<?php

namespace Piksi\Services;

class BaseService
{
	private static $instances = [];

	public static function getInstance()
	{
		$className = get_called_class();
		if (!isset(self::$instances[$className]))
		{
			self::$instances[$className] = new $className();
		}

		return self::$instances[$className];
	}
}
