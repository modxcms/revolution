<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages\Version;

use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\Transport\modTransportPackage;
use xPDO\Om\xPDOObject;
use xPDO\Transport\xPDOTransport;

/**
 * Gets a list of package versions for a package
 * @package MODX\Revolution\Processors\Workspace\Packages\Version
 */
class GetList extends GetListProcessor
{
    public $primaryKeyField = 'signature';
    public $classKey = modTransportPackage::class;
    public $objectType = 'package';
    public $checkListPermission = false;
    public $permission = 'packages';
    public $languageTopics = ['workspace'];

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->modx->addPackage('Revolution\Transport', MODX_CORE_PATH . 'src/');
        $this->setDefaultProperties([
            'limit' => 10,
            'start' => 0,
            'workspace' => 1,
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'),
            'signature' => false,
        ]);
        return parent::initialize();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [];
        $signatureArray = explode('-', $this->getProperty('signature'));
        /* get packages */
        $criteria = [
            'workspace' => $this->getProperty('workspace', 1),
            'package_name' => urldecode($this->getProperty('package_name', $signatureArray[0])),
        ];
        $limit = $this->getProperty('limit');
        $pkgList = $this->modx->call(modTransportPackage::class, 'listPackageVersions',
            [&$this->modx, $criteria, $limit > 0 ? $limit : 0, $this->getProperty('start')]);
        $data['results'] = $pkgList['collection'];
        $data['total'] = $pkgList['total'];
        return $data;
    }

    /**
     * @param xPDOObject|modTransportPackage $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        if ($object->get('installed') === '0000-00-00 00:00:00') {
            $object->set('installed', null);
        }

        $packageArray = $object->toArray();
        $packageArray = $this->parseVersion($object, $packageArray);
        $packageArray = $this->formatDates($object, $packageArray);
        $packageArray = $this->getMetaData($object, $packageArray);
        $packageArray = $this->prepareMenu($object, $packageArray);

        /* setup description, using either metadata or readme */
        if ($object->get('installed') === null) {
            $this->currentIndex--;
        }
        return $packageArray;
    }

    /**
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function parseVersion(modTransportPackage $package, array $packageArray)
    {
        $signatureArray = explode('-', $package->get('signature'));
        $packageArray['name'] = !empty($packageArray['package_name']) ? $packageArray['package_name'] : $signatureArray[0];
        $packageArray['version'] = $signatureArray[1];
        if (isset($signatureArray[2])) {
            $packageArray['release'] = $signatureArray[2];
        }
        return $packageArray;
    }

    /**
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function formatDates(modTransportPackage $package, array $packageArray)
    {
        $updated = $package->get('updated');
        if ($updated !== '0000-00-00 00:00:00' && $updated !== null) {
            $packageArray['updated'] = date($this->getProperty('dateFormat'), strtotime($updated));
        } else {
            $packageArray['updated'] = '';
        }
        $packageArray['created'] = date($this->getProperty('dateFormat'), strtotime($package->get('created')));
        $installed = $package->get('installed');
        if ($installed === null || $installed === '0000-00-00 00:00:00') {
            $packageArray['installed'] = null;
        } else {
            $packageArray['installed'] = date($this->getProperty('dateFormat'), strtotime($installed));
        }
        return $packageArray;
    }

    /**
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function getMetaData(modTransportPackage $package, array $packageArray)
    {
        $metadata = $package->get('metadata');
        if (!empty($metadata)) {
            foreach ($metadata as $row) {
                if (!empty($row['name']) && $row['name'] === 'description') {
                    $packageArray['readme'] = str_replace([PHP_EOL, '<br /><br />'], ['', '<br />'],
                        nl2br($row['text']));
                    break;
                }
            }
        } else {
            /** @var xPDOTransport $transport */
            $transport = $package->getTransport();
            if ($transport) {
                $packageArray['readme'] = $transport->getAttribute('readme');
                $packageArray['readme'] = str_replace([PHP_EOL, '<br /><br />'], ['', '<br />'],
                    nl2br($packageArray['readme']));
            }
        }
        unset($packageArray['attributes'], $packageArray['metadata'], $packageArray['manifest']);

        return $packageArray;
    }

    /**
     * @param modTransportPackage $package
     * @param array $packageArray
     * @return array
     */
    public function prepareMenu(modTransportPackage $package, array $packageArray)
    {
        $notInstalled = $package->get('installed') === null || $package->get('installed') === '0000-00-00 00:00:00';
        $packageArray['iconaction'] = $notInstalled ? 'icon-install' : 'icon-uninstall';
        $packageArray['textaction'] = $notInstalled ? $this->modx->lexicon('install') : $this->modx->lexicon('uninstall');

        if ($this->currentIndex > 0 || !$package->get('installed')) {
            $packageArray['menu'] = [];
            $packageArray['menu'][] = [
                'text' => $this->modx->lexicon('package_version_remove'),
                'handler' => 'this.removePriorVersion',
            ];
        }
        return $packageArray;
    }
}
