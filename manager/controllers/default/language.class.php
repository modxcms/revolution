<?php

class LanguageManagerController extends modParsedManagerController
{
    public function checkPermissions()
    {
        return true;   // todo need check new permission - language
    }

    public function process(array $scriptProperties = array())
    {
        $targetLanguage = $this->modx->getOption('switch', $scriptProperties, 'en');

        if (!in_array($targetLanguage, $this->modx->lexicon->getLanguageList())) {
            return;
        }

        $_SESSION['manager_language'] = $targetLanguage;

        $this->modx->sendRedirect(MODX_MANAGER_URL);
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
