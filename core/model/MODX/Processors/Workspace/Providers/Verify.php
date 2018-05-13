<?php

namespace MODX\Processors\Workspace\Providers;

use MODX\Processors\modProcessor;
use MODX\Transport\modTransportProvider;

/**
 * Gets a provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
class Verify extends modProcessor
{
    public function process()
    {
        if (!$this->modx->hasPermission('providers')) {
            return $this->modx->error->failure($this->modx->lexicon('permission_denied'));
        }
        $this->modx->lexicon->load('workspace');

        /* get provider */
        if (empty($scriptProperties['id']) && empty($scriptProperties['name'])) {
            return $this->modx->error->failure($this->modx->lexicon('provider_err_ns'));
        }
        $c = [];
        if (!empty($scriptProperties['id'])) {
            $c['id'] = $scriptProperties['id'];
        } else {
            $c['name'] = $scriptProperties['name'];
        }
        /** @var modTransportProvider $provider */
        $provider = $this->modx->getObject('transport.modTransportProvider', $c);
        if (!$provider) return $this->modx->error->failure($this->modx->lexicon('provider_err_nfs', $c));

        /* get provider client */
        $loaded = $provider->getClient();
        if (!$loaded) return $this->modx->error->failure($this->modx->lexicon('provider_err_no_client'));

        /* verify provider */
        $verified = $provider->verify();
        if ($verified === true) {
            return $this->success();
        } else {
            return $this->failure($verified);
        }
    }
}