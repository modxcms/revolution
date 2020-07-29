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

        $targetPage = MODX_MANAGER_URL;
        if (!empty($scriptProperties['page'])) {
            $targetPage = urldecode($scriptProperties['page']);
        }

        if (!in_array($targetLanguage, $this->modx->lexicon->getLanguageList())) {
            return;
        }

        $_SESSION['manager_language'] = $targetLanguage;

        $this->modx->sendRedirect($targetPage);
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
