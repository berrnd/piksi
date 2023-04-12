<?php

namespace Piksi\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SystemApiController extends BaseApiController
{
	public function LogMissingLocalization(Request $request, Response $response, array $args)
	{
		if (PIKSI_MODE === 'dev')
		{
			try
			{
				$requestBody = $request->getParsedBody();
				$this->getLocalizationService()->CheckAndAddMissingTranslationToPot($requestBody['text']);
				return $this->EmptyApiResponse($response);
			}
			catch (\Exception $ex)
			{
				return $this->GenericErrorResponse($response, $ex->getMessage());
			}
		}
	}
}
