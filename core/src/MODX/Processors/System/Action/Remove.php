<?php

namespace MODX\Processors\System\Action;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modAction';
    public $languageTopics = ['action', 'menu'];
    public $permission = 'actions';
    public $objectType = 'action';
}