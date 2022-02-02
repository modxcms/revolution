<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modDashboard;
use MODX\Revolution\modManagerController;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\Processors\System\Dashboard\User\GetList;

/**
 * Loads the welcome page
 *
 * @package modx
 * @subpackage manager.controllers
 */
class WelcomeManagerController extends modManagerController
{
    /**
     * Whether or not to show the welcome screen
     *
     * @var boolean $showWelcomeScreen
     */
    public $showWelcomeScreen = false;

    /**
     * The current, active dashboard for the user
     *
     * @var null|modDashboard $dashboard
     */
    public $dashboard = null;


    /**
     * Check for any permissions or requirements to load page
     *
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('home');
    }


    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/widgets/system/modx.panel.dashboard.js');
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/widgets/system/modx.panel.dashboard.widget.js');
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/widgets/modx.panel.welcome.js');
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/modext/sections/welcome.js');
        $this->addJavascript($this->modx->getOption('manager_url') . 'assets/lib/Sortable.min.js');

        $new_widgets = 0;
        if ($this->dashboard->get('customizable')) {
            /** @var ProcessorResponse $res */
            $res = $this->modx->runProcessor(GetList::class, [
                'dashboard' => $this->dashboard->get('id'),
                'combo' => true,
            ]);
            if (!$res->isError()) {
                $tmp = $res->getResponse();
                if (is_string($tmp)) {
                    $tmp = json_decode($tmp, true);
                }
                $new_widgets = $tmp['total'];
            }
        }

        $obj = [
            'xtype' => 'modx-page-welcome',
            'dashboard' => array_merge(
                $this->dashboard->toArray(),
                ['new_widgets' => $new_widgets]
            )
        ];
        $this->addHtml('<script>Ext.onReady(function() {MODx.load(' . json_encode($obj, JSON_INVALID_UTF8_SUBSTITUTE) . ')});</script>');
        if ($this->showWelcomeScreen) {
            $url = $this->modx->getOption('welcome_screen_url', null, 'http://misc.modx.com/revolution/welcome.20.html');
            $this->addHtml('<script>Ext.onReady(function() { MODx.loadWelcomePanel("' . htmlspecialchars($url) . '"); });</script>');
        }
    }


    /**
     * Custom logic code here for setting placeholders, etc
     *
     * @param array $scriptProperties
     *
     * @return array
     */
    public function process(array $scriptProperties = [])
    {
        $this->checkForWelcomeScreen();

        $this->dashboard = $this->modx->user->getDashboard();
        $placeholders = $this->dashboard->toArray();
        $placeholders['dashboard'] = $this->dashboard->render($this, $this->modx->user);

        return $placeholders;
    }


    /**
     * Check to show if we need to show the Welcome Screen for the user
     *
     * @return void
     */
    public function checkForWelcomeScreen()
    {
        if ($this->modx->getOption('welcome_screen', null, false)) {
            $this->showWelcomeScreen = true;
            /** @var modSystemSetting $setting */
            $setting = $this->modx->getObject(modSystemSetting::class, 'welcome_screen');
            if ($setting) {
                $setting->set('value', false);
                $setting->save();
            }
            /** @var modUserSetting $setting */
            $setting = $this->modx->getObject(modUserSetting::class, [
                'key' => 'welcome_screen',
                'user' => $this->modx->user->get('id'),
            ]);
            if ($setting) {
                $setting->set('value', false);
                $setting->save();
            }
            $this->modx->reloadConfig();
        }
    }


    /**
     * Return the pagetitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('dashboard');
    }


    /**
     * Return the location of the template file
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return 'welcome.tpl';
    }


    /**
     * Specify the language topics to load
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['welcome', 'configcheck', 'dashboards'];
    }
}
