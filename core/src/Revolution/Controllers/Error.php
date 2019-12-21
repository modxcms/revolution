<?php

namespace MODX\Revolution\Controllers;

use MODX\Revolution\modManagerController;

final class Error extends modManagerController
{
    /**
     * Do permission checking in this method. Returning false will present a "permission denied" message.
     *
     * @return boolean
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * Process the controller, returning an array of placeholders to set.
     *
     *
     * @param array $scriptProperties A array of REQUEST parameters.
     *
     * @return mixed Either an error or output string, or an array of placeholders to set.
     */
    public function process(array $scriptProperties = [])
    {
        return [
            '_e' => [
                'message' => $this->config['message'] ?? 'unknown error',
                'errors' => $this->config['errors'] ? array_filter($this->config['errors']) : [],
            ]
        ];
    }

    /**
     * Return a string to set as the controller's page title.
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('error');
    }

    /**
     * Register any custom CSS or JS in this method.
     *
     * @return void
     */
    public function loadCustomCssJs()
    {

    }

    /**
     * Return the relative path to the template file to load
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'error.tpl';
    }
}
