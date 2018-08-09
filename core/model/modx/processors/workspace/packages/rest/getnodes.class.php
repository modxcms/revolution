<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.workspace.packages.rest
 */
class modPackageGetNodesProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

    public $nodeType = 'root';
    public $nodeKey = '';

    public function checkPermissions() {
        return $this->modx->hasPermission('packages');
    }

    public function getLanguageTopics() {
        return array('workspace');
    }

    public function initialize() {
        $provider = $this->getProperty('provider',false);
        if (empty($provider)) return $this->modx->lexicon('provider_err_ns');
        $this->provider = $this->modx->getObject('transport.modTransportProvider',$provider);
        if (empty($this->provider)) return $this->modx->lexicon('provider_err_nf');

        return true;
    }

    public function process() {
        /* get client */
        /* load appropriate processor */
        $id = $this->getProperty('id','n_root_0');
        $ar = explode('_',$id);
        $this->nodeType = !empty($ar[1]) ? $ar[1] : 'root';
        $this->nodeKey = !empty($ar[2]) ? $ar[2] : null;
        switch ($this->nodeType) {
            case 'repository':
                $list = $this->getCategories();
                break;
            case 'tag':
                $list = array();
                break;
            case 'root':
            default:
                $list = $this->getRepositories();
                break;
        }
        if (!is_array($list)) {
            return $this->failure($list);
        }
        return $this->toJSON($list);
    }

    public function getRepositories() {
        return $this->provider->repositories();
    }

    public function getCategories() {
        return $this->provider->categories($this->nodeKey);
    }
}
return 'modPackageGetNodesProcessor';
