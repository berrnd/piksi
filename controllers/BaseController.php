<?php

namespace Piksi\Controllers;

use Piksi\Services\ApplicationService;
use Piksi\Services\LocalizationService;
use Piksi\Services\MediaFolderService;

class BaseController
{
	public function __construct(\DI\Container $container)
	{
		$this->AppContainer = $container;
		$this->View = $container->get('view');
	}

	protected $AppContainer;

	protected function getApplicationservice()
	{
		return ApplicationService::getInstance();
	}

	protected function getLocalizationService()
	{
		if (!defined('PIKSI_LOCALE'))
		{
			define('PIKSI_LOCALE', PIKSI_LOCALE);
		}

		return LocalizationService::getInstance(PIKSI_LOCALE);
	}

	protected function getMediaFolderService()
	{
		return MediaFolderService::getInstance();
	}

	protected function render($response, $page, $data = [])
	{
		$container = $this->AppContainer;

		$versionInfo = $this->getApplicationService()->GetInstalledVersion();
		$this->View->set('version', $versionInfo->Version);

		$localizationService = $this->getLocalizationService();
		$this->View->set('__t', function (string $text, ...$placeholderValues) use ($localizationService) {
			return $localizationService->__t($text, $placeholderValues);
		});
		$this->View->set('__n', function ($number, $singularForm, $pluralForm) use ($localizationService) {
			return $localizationService->__n($number, $singularForm, $pluralForm);
		});
		$this->View->set('LocalizationStrings', $localizationService->GetPoAsJsonString());

		$this->View->set('U', function ($relativePath, $isResource = false) use ($container) {
			return $container->get('UrlManager')->ConstructUrl($relativePath, $isResource);
		});

		$embedded = false;
		if (isset($_GET['embedded']))
		{
			$embedded = true;
		}
		$this->View->set('embedded', $embedded);

		$title = 'Piksi';
		if (!empty(PIKSI_OVERWRITE_TITLE))
		{
			$title = PIKSI_OVERWRITE_TITLE;
		}
		$this->View->set('title', $title);

		return $this->View->render($response, $page, $data);
	}
}
