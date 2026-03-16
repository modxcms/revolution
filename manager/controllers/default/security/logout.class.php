<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modManagerController;

class SecurityLogoutManagerController extends modManagerController {
    /**
     * Check for any permissions or requirements to load page
     * @return bool
     */
    public function checkPermissions() {
        return true;
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {}

    /**
     * Custom logic code here for setting placeholders, etc
     * @param array $scriptProperties
     * @return mixed
     */
    public function process(array $scriptProperties = []) {
        $managerLanguage = null;
        if (!empty($_SESSION['manager_language'])) {
            $languages = $this->modx->lexicon->getLanguageList('core');
            if (in_array($_SESSION['manager_language'], $languages)) {
                $managerLanguage = $_SESSION['manager_language'];
            }
        }
        $this->modx->runProcessor('security/logout');
        $url = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        if ($managerLanguage !== null) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . 'manager_language=' . urlencode($managerLanguage);
        }
        $this->modx->sendRedirect($url);
    }

    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('logout');
    }

    /**
     * Return the location of the template file
     * @return string
     */
    public function getTemplateFile() {
        return 'security/logout.tpl';
    }

    /**
     * Specify the language topics to load
     * @return array
     */
    public function getLanguageTopics() {
        return ['access','user'];
    }
}
