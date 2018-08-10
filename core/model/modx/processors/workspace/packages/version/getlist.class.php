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
 * Gets a list of package versions for a package
 *
 * @package modx
 * @subpackage processors.workspace.package
 */
class modPackageVersionGetListProcessor extends modObjectGetListProcessor {
    public $primaryKeyField = 'signature';
    public $classKey = 'transport.modTransportPackage';
    public $objectType = 'package';
    public $checkListPermission = false;
    public $permission = 'packages';
    public $languageTopics = array('workspace');

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'workspace' => 1,
            'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format'),
            'signature' => false,
        ));
        $this->modx->addPackage('modx.transport',$this->modx->getOption('core_path',null,MODX_CORE_PATH).'model/');
        return parent::initialize();
    }

    public function getData() {
        $data = array();
        $signatureArray = explode('-',$this->getProperty('signature'));
        /* get packages */
        $criteria = array(
            'workspace' => $this->getProperty('workspace',1),
            'package_name' => urldecode($this->getProperty('package_name',$signatureArray[0])),
        );
        $limit = $this->getProperty('limit');
        $pkgList = $this->modx->call('transport.modTransportPackage', 'listPackageVersions', array(&$this->modx, $criteria, $limit > 0 ? $limit : 0, $this->getProperty('start')));
        $data['results'] = $pkgList['collection'];
        $data['total'] = $pkgList['total'];
        return $data;
    }

    /**
     * @param xPDOObject|modTransportPackage $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        if ($object->get('installed') == '0000-00-00 00:00:00') $object->set('installed',null);

        $packageArray = $object->toArray();
        $packageArray = $this->parseVersion($object,$packageArray);
        $packageArray = $this->formatDates($object,$packageArray);
        $packageArray = $this->getMetaData($object,$packageArray);
        $packageArray = $this->prepareMenu($object,$packageArray);

        /* setup description, using either metadata or readme */
        if ($object->get('installed') == null) $this->currentIndex--;
        return $packageArray;
    }

    public function parseVersion(modTransportPackage $package,array $packageArray) {
        $signatureArray = explode('-',$package->get('signature'));
        $packageArray['name'] = !empty($packageArray['package_name']) ? $packageArray['package_name'] : $signatureArray[0];
        $packageArray['version'] = $signatureArray[1];
        if (isset($signatureArray[2])) {
            $packageArray['release'] = $signatureArray[2];
        }
        return $packageArray;
    }

    public function formatDates(modTransportPackage $package,array $packageArray) {
        if ($package->get('updated') != '0000-00-00 00:00:00' && $package->get('updated') != null) {
            $packageArray['updated'] = date($this->getProperty('dateFormat'), strtotime($package->get('updated')));
        } else {
            $packageArray['updated'] = '';
        }
        $packageArray['created']= date($this->getProperty('dateFormat'), strtotime($package->get('created')));
        if ($package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00') {
            $packageArray['installed'] = null;
        } else {
            $packageArray['installed'] = date($this->getProperty('dateFormat'),strtotime($package->get('installed')));
        }
        return $packageArray;
    }

    public function getMetaData(modTransportPackage $package,array $packageArray) {
        $metadata = $package->get('metadata');
        if (!empty($metadata)) {
            foreach ($metadata as $row) {
                if (!empty($row['name']) && $row['name'] == 'description') {
                    $packageArray['readme'] = str_replace('<br /><br />','<br />',str_replace("\n",'',nl2br($row['text'])));
                    break;
                }
            }
        } else {
            /** @var xPDOTransport $transport */
            $transport = $package->getTransport();
            if ($transport) {
                $packageArray['readme'] = $transport->getAttribute('readme');
                $packageArray['readme'] = str_replace('<br /><br />','<br />',str_replace("\n",'',nl2br($packageArray['readme'])));
            }
        }
        unset($packageArray['attributes']);
        unset($packageArray['metadata']);
        unset($packageArray['manifest']);
        return $packageArray;
    }


    public function prepareMenu(modTransportPackage $package,array $packageArray) {
        $notInstalled = $package->get('installed') == null || $package->get('installed') == '0000-00-00 00:00:00';
        $packageArray['iconaction'] = $notInstalled ? 'icon-install' : 'icon-uninstall';
        $packageArray['textaction'] = $notInstalled ? $this->modx->lexicon('install') : $this->modx->lexicon('uninstall');

        if ($this->currentIndex > 0 || !$package->get('installed')) {
            $packageArray['menu'] = array();
            $packageArray['menu'][] = array(
                'text' => $this->modx->lexicon('package_version_remove'),
                'handler' => 'this.removePriorVersion',
            );
        }
        return $packageArray;
    }
}
return 'modPackageVersionGetListProcessor';
