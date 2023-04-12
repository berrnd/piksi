<?php

namespace Piksi\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface as Logger;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Throwable;

class ExceptionController extends BaseApiController
{
	public function __construct(\Slim\App $app, \DI\Container $container)
	{
		parent::__construct($container);
		$this->app = $app;
	}

	private $app;

	public function __invoke(Request $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails, ?Logger $logger = null)
	{
		$response = $this->app->getResponseFactory()->createResponse();
		$isApiRoute = string_starts_with($request->getUri()->getPath(), '/api/');

		if ($isApiRoute)
		{
			$status = 500;
			if ($exception instanceof HttpException)
			{
				$status = $exception->getCode();
			}

			$data = [
				'error_message' => $exception->getMessage()
			];

			if ($displayErrorDetails)
			{
				$data['error_details'] = [
					'stack_trace' => $exception->getTraceAsString(),
					'file' => $exception->getFile(),
					'line' => $exception->getLine()
				];
			}

			return $this->ApiResponse($response->withStatus($status), $data);
		}

		if ($exception instanceof HttpNotFoundException)
		{
			return $this->render($response->withStatus(404), 'errors/404', [
				'exception' => $exception
			]);
		}

		return $this->render($response->withStatus(500), 'errors/500', [
			'exception' => $exception,
			'system_info' => $this->getApplicationService()->GetSystemInfo()
		]);
	}
}
