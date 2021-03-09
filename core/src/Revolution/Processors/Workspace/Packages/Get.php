<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages;

use MODX\Revolution\Processors\Model\GetProcessor;
use MODX\Revolution\Transport\modTransportPackage;
use MODX\Revolution\Transport\modTransportProvider;
use xPDO\Transport\xPDOTransport;

/**
 * Gets a Transport Package.
 * @param integer $id The ID of the chunk.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Get extends GetProcessor
{
    public $classKey = modTransportPackage::class;
    public $languageTopics = ['workspace'];
    public $permission = 'packages';
    public $objectType = 'package';
    public $primaryKeyField = 'signature';
    public $checkViewPermission = false;

    /** @var string $dateFormat */
    public $dateFormat = '%b %d, %Y %I:%M %p';

    /** @var modTransportProvider $provider */
    public $provider;

    /** @var modTransportPackage $object */
    public $object;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->modx->addPackage('Revolution\Transport', MODX_CORE_PATH . 'src/');
        $this->dateFormat = $this->getProperty('dateFormat',
            $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format'));
        return parent::initialize();
    }

    /**
     * @return array|string
     */
    public function cleanup()
    {
        $this->getMetadata();
        $packageArray = $this->object->toArray();
        unset($packageArray['attributes'], $packageArray['metadata'], $packageArray['manifest']);
        $packageArray = $this->formatDates($packageArray);
        return $this->success('', $packageArray);
    }

    /**
     * Get the metadata for the object
     * @return void
     */
    public function getMetadata()
    {
        /** @var xPDOTransport $transport */
        $transport = $this->object->getTransport();
        if ($transport) {
            $this->object->set('readme', $transport->getAttribute('readme'));
            $this->object->set('license', $transport->getAttribute('license'));
            $this->object->set('changelog', $transport->getAttribute('changelog'));
        }
    }

    /**
     * Format the dates for readability
     * @param array $packageArray
     * @return array
     */
    public function formatDates(array $packageArray)
    {
        $updated = $this->object->get('updated');
        if ($updated !== '0000-00-00 00:00:00' && $updated !== null) {
            $packageArray['updated'] = date($this->dateFormat, strtotime($updated));
        } else {
            $packageArray['updated'] = '';
        }
        $packageArray['created'] = date($this->dateFormat, strtotime($this->object->get('created')));
        $installed = $this->object->get('installed');
        if ($installed === null || $installed === '0000-00-00 00:00:00') {
            $packageArray['installed'] = null;
        } else {
            $packageArray['installed'] = date($this->dateFormat, strtotime($installed));
        }
        return $packageArray;
    }
}
