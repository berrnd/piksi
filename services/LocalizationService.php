<?php

namespace Piksi\Services;

use Gettext\Translation;
use Gettext\Translations;
use Gettext\Translator;

class LocalizationService
{
	public function __construct(string $locale)
	{
		$this->Locale = $locale;

		$this->LoadLocalizations($locale);
	}

	protected $Po;
	protected $Pot;
	protected $Translator;
	private static $instanceMap = [];

	public function CheckAndAddMissingTranslationToPot($text)
	{
		if (PIKSI_MODE === 'dev')
		{
			if ($this->Pot->find('', $text) === false && empty($text) === false)
			{
				$translation = new Translation('', $text);
				$this->Pot[] = $translation;
				$this->Pot->toPoFile(__DIR__ . '/../localization/strings.pot');
			}
		}
	}

	public function GetPluralCount()
	{
		if ($this->Po->getHeader(Translations::HEADER_PLURAL) !== null)
		{
			return intval($this->Po->getPluralForms()[0]);
		}
		else
		{
			return 2;
		}
	}

	public function GetPluralDefinition()
	{
		if ($this->Po->getHeader(Translations::HEADER_PLURAL) !== null)
		{
			return $this->Po->getPluralForms()[1];
		}
		else
		{
			return '(n != 1)';
		}
	}

	public function GetPoAsJsonString()
	{
		return $this->Po->toJsonString();
	}

	public function __n($number, $singularForm, $pluralForm)
	{
		$this->CheckAndAddMissingTranslationToPot($singularForm);

		if (empty($pluralForm))
		{
			$pluralForm = $singularForm;
		}

		return sprintf($this->Translator->ngettext($singularForm, $pluralForm, abs(floatval($number))), $number);
	}

	public function __t($text, ...$placeholderValues)
	{
		$this->CheckAndAddMissingTranslationToPot($text);

		if (func_num_args() === 1)
		{
			return $this->Translator->gettext($text);
		}
		else
		{
			if (is_array(...$placeholderValues))
			{
				return vsprintf($this->Translator->gettext($text), ...$placeholderValues);
			}
			else
			{
				return sprintf($this->Translator->gettext($text), array_shift($placeholderValues));
			}
		}
	}

	public static function getInstance(string $locale)
	{
		if (!in_array($locale, self::$instanceMap))
		{
			self::$instanceMap[$locale] = new self($locale);
		}

		return self::$instanceMap[$locale];
	}

	private function LoadLocalizations()
	{
		$locale = $this->Locale;

		if (PIKSI_MODE === 'dev')
		{
			$this->Pot = Translations::fromPoFile(__DIR__ . '/../localization/strings.pot');
		}

		$this->Po = Translations::fromPoFile(__DIR__ . "/../localization/$locale.po");

		$this->Translator = new Translator();
		$this->Translator->loadTranslations($this->Po);
	}
}
