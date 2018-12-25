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
 */
class modPackageRemoteGetListProcessor extends modProcessor {
    /** @var modTransportProvider $provider */
    public $provider;

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

        $this->setDefaultProperties(array(
            'query' => false,
            'tag' => false,
            'sorter' => false,
            'start' => 0,
            'limit' => 10,
            'dateFormat' => '%Y-%m-%d',
            'supportsSeparator' => ', ',
        ));
        $start = $this->getProperty('start');
        $limit = $this->getProperty('limit');
        $this->setProperty('page',!empty($start) ? round($start / $limit) : 0);
        return true;
    }

    public function process() {
        $data = $this->provider->find($this->getProperties());

        if (empty($data) || count($data) !== 2) {
            return $this->failure($this->modx->lexicon('provider_err_connect'));
        }

        $list = array();
        foreach ($data[1] as $package) {
            if ((string)$package['name'] == '') continue;
            $list[] = $package;
        }

        return $this->outputArray($list, (int)$data[0]);
    }
}
return 'modPackageRemoteGetListProcessor';
