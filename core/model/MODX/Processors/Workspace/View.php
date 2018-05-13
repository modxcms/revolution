<?php

namespace MODX\Processors\Workspace;

use MODX\Processors\modObjectGetProcessor;

/**
 * Views a workspace
 *
 * @param integer $id The ID of the workspace
 *
 * @package modx
 * @subpackage processors.workspace
 */
class View extends modObjectGetProcessor
{
    public $classKey = 'modWorkspace';
    public $permission = 'workspaces';
    public $languageTopics = ['workspace'];
}