<?php

namespace Piksi\Middleware;

use Piksi\Services\ApplicationService;
use DI\Container;

class BaseMiddleware
{
	public function __construct(Container $container)
	{
		$this->AppContainer = $container;
		$this->ApplicationService = ApplicationService::getInstance();
	}

	protected $AppContainer;

	protected $ApplicationService;
}
