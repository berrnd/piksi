<?php

use Piksi\Middleware\JsonMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('', function (RouteCollectorProxy $group) {
	$group->get('/', '\Piksi\Controllers\MediaFolderController:Overview');
	$group->get('/file', '\Piksi\Controllers\MediaFolderController:ServeFile');

	$group->get('/about', '\Piksi\Controllers\SystemController:About');
});

if (PIKSI_MODE == 'dev')
{
	$app->group('/api', function (RouteCollectorProxy $group) {
		$group->post('/system/log-missing-localization', '\Piksi\Controllers\SystemApiController:LogMissingLocalization');
	})->add(JsonMiddleware::class);
}
