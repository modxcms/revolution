<?php

namespace MODX\Processors\Element\Tv\Renders;

use MODX\modManagerController;

class PropertiesController extends modManagerController
{
    public $loadFooter = false;
    public $loadHeader = false;


    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }


    public function loadCustomCssJs()
    {
    }


    public function process(array $scriptProperties = [])
    {
    }


    public function getPageTitle()
    {
        return '';
    }


    public function getTemplateFile()
    {
        return 'empty.tpl';
    }


    public function getLanguageTopics()
    {
        return [];
    }
}