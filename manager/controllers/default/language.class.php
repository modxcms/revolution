<?php

use MODX\Revolution\modParsedManagerController;

/**
 * Switches the current manger language to requested.
 *
 * Class LanguageManagerController
 */
class LanguageManagerController extends modParsedManagerController
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('language');
    }

    public function process(array $scriptProperties = [])
    {
        $targetLanguage = $this->modx->getOption('switch', $scriptProperties, 'en');

        if (!in_array($targetLanguage, $this->modx->lexicon->getLanguageList())) {
            return;
        }

        $targetProperties = [];
        foreach ($scriptProperties as $key => $value) {
            if (strpos($key, 'target_') === 0) {
                $key = substr($key, strlen('target_'));
                $targetProperties[$key] = $value;
            }
        }
        $_SESSION['manager_language'] = $targetLanguage;

        $this->modx->sendRedirect(MODX_MANAGER_URL . (($targetProperties) ? '?' . http_build_query($targetProperties) : ''));
    }

    public function getPageTitle()
    {
        return '';
    }

    public function loadCustomCssJs()
    {
        return;
    }

    public function getTemplateFile()
    {
        return '';
    }
}
