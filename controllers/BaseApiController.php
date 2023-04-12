<?php

namespace Piksi\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

class BaseApiController extends BaseController
{
	protected function ApiResponse(Response $response, $data, $cache = false)
	{
		if ($cache)
		{
			$response = $response->withHeader('Cache-Control', 'max-age=2592000');
		}

		$response->getBody()->write(json_encode($data));
		return $response;
	}

	protected function EmptyApiResponse(Response $response, $status = 204)
	{
		return $response->withStatus($status);
	}

	protected function GenericErrorResponse(Response $response, $errorMessage, $status = 400)
	{
		return $response->withStatus($status)->withJson([
			'error_message' => $errorMessage
		]);
	}
}
