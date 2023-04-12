<?php

namespace Piksi\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JsonMiddleware extends BaseMiddleware
{
	public function __invoke(Request $request, RequestHandler $handler): Response
	{
		$response = $handler->handle($request);

		if ($response->hasHeader('Content-Disposition'))
		{
			return $response;
		}
		else
		{
			return $response->withHeader('Content-Type', 'application/json');
		}
	}
}
