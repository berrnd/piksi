<?php

namespace Piksi\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SystemController extends BaseController
{
	public function About(Request $request, Response $response, array $args)
	{
		return $this->render($response, 'about', [
			'systemInfo' => $this->getApplicationService()->GetSystemInfo(),
			'versionInfo' => $this->getApplicationService()->GetInstalledVersion()
		]);
	}
}
