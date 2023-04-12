<?php

namespace Piksi\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

class PicturesController extends BaseController
{
	public function Overview(Request $request, Response $response, array $args)
	{
		if (!isset($request->getQueryParams()['folder']))
		{
			if (count(PIKSI_FOLDERS) == 1)
			{
				// Directly jump to the first folder when there is only 1 configured
				return $response->withRedirect($this->AppContainer->get('UrlManager')->ConstructUrl('/?folder=0&path=/'));
			}
			else
			{
				return $this->render($response, 'overview', [
					'rootFolders' => $this->getPictureService()->GetRootFolderInfo()
				]);
			}
		}
		else
		{
			if (isset($request->getQueryParams()['folder']) && filter_var($request->getQueryParams()['folder'], FILTER_VALIDATE_INT) !== false)
			{
				$folderIndex = $request->getQueryParams()['folder'];
				$path = $request->getQueryParams()['path'];

				return $this->render($response, 'folder', [
					'folderIndex' => $folderIndex,
					'path' => $path,
					'items' => $this->getPictureService()->GetFolder($folderIndex, $path)
				]);
			}
			else
			{
				return $response->withStatus(404);
			}
		}
	}

	public function ServeFile(Request $request, Response $response, array $args)
	{
		if (isset($request->getQueryParams()['folder']) && filter_var($request->getQueryParams()['folder'], FILTER_VALIDATE_INT) !== false)
		{
			$folderPath = realpath(PIKSI_FOLDERS[$request->getQueryParams()['folder']]['path']);
			$filePath = realpath($folderPath . '/' . ltrim($request->getQueryParams()['path'], '/'));

			if (!string_starts_with($filePath, $folderPath) || !file_exists($filePath))
			{
				return $response->withStatus(404);
			}
			else
			{
				$response = $response->withHeader('Cache-Control', 'max-age=2592000');
				$response = $response->withHeader('Content-Type', mime_content_type($filePath));
				$response = $response->withHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
				return $response->withBody(new Stream(fopen($filePath, 'rb')));
			}
		}
		else
		{
			return $response->withStatus(404);
		}
	}
}
