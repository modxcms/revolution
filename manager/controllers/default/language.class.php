<?php

use MODX\Revolution\modParsedManagerController;

/**
 * Switches the current manger language to requested.
 * @noinspection AutoloadingIssuesInspection
 */
class LanguageManagerController extends modParsedManagerController
{
    public function checkPermissions(): bool
    {
        return $this->modx->hasPermission('language');
    }

    public function process(array $scriptProperties = []): void
    {
        $targetLanguage = $this->modx->getOption('switch', $scriptProperties, 'en');

        if (in_array($targetLanguage, $this->modx->lexicon->getLanguageList(), true)) {
            $_SESSION['manager_language'] = $targetLanguage;
        }

        $this->modx->sendRedirect($_SERVER['HTTP_REFERER'] ?? MODX_MANAGER_URL);
    }
}
