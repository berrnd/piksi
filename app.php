<?php

use DI\Container as DIContainer;
use Piksi\Helpers\UrlManager;
use Piksi\Controllers\ExceptionController;
use Psr\Container\ContainerInterface as Container;
use Slim\Factory\AppFactory;
use Slim\Views\Blade;

// Check base requirements
require_once __DIR__ . '/helpers/RequirementsChecker.php';
$requirementsChecker = new Piksi\Helpers\RequirementsChecker();
try
{
	$requirementsChecker->CheckEnvironment();
}
catch (\Exception $ex)
{
	exit('Unable to run Piksi: ' . $ex->getMessage());
}

// Load composer dependencies
require_once __DIR__ . '/packages/autoload.php';

// Load config files
require_once PIKSI_DATAPATH . '/config.php';
require_once __DIR__ . '/config-dist.php'; // For not in own config defined values we use the default ones

// Check required config options
try
{
	$requirementsChecker->CheckConfig();
}
catch (\Exception $ex)
{
	exit('Unable to run Piksi (config.php check failed): ' . $ex->getMessage());
}

// Error reporting definitions
if (PIKSI_MODE === 'dev')
{
	error_reporting(E_ALL);
}
else
{
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
}

// Create data/viewcache folder if it doesn't exist
if (!file_exists(PIKSI_DATAPATH . '/viewcache'))
{
	mkdir(PIKSI_DATAPATH . '/viewcache');
}

// Setup base application
AppFactory::setContainer(new DIContainer());
$app = AppFactory::create();

$container = $app->getContainer();
$container->set('view', function (Container $container) {
	return new Blade(__DIR__ . '/views', PIKSI_DATAPATH . '/viewcache');
});

$container->set('UrlManager', function (Container $container) {
	return new UrlManager(PIKSI_BASE_URL);
});

// Load routes from separate file
require_once __DIR__ . '/routes.php';

// Set base path if defined
if (!empty(PIKSI_BASE_PATH))
{
	$app->setBasePath(PIKSI_BASE_PATH);
}

define('PIKSI_LOCALE', PIKSI_LOCALE);

// Add default middleware
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->setDefaultErrorHandler(
	new ExceptionController($app, $container)
);

$app->getRouteCollector()->setCacheFile(PIKSI_DATAPATH . '/viewcache/route_cache.php');

ob_clean(); // No response output before here
$app->run();
