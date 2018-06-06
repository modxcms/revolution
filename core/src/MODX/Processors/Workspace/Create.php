<?php

namespace MODX\Processors\Workspace;

use MODX\Processors\modProcessor;

/**
 * Create a workspace
 *
 * @package modx
 * @subpackage processors.workspace
 */
class Create extends modProcessor
{
    public function process()
    {
        $this->modx->lexicon->load('workspace');

        if (!$this->modx->hasPermission('workspaces')) {
            return $this->modx->error->failure($this->modx->lexicon('permission_denied'));
        }

        return $this->failure('Not yet implemented.');
    }
}