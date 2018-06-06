<?php

namespace MODX\Processors\Workspace\Providers;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a provider
 *
 * @param integer $id The provider ID
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'transport.modTransportProvider';
    public $languageTopics = ['workspace'];
    public $permission = 'providers';
    public $objectType = 'provider';
}