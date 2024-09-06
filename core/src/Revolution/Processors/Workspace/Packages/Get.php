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

use MODX\Revolution\Formatter\modManagerDateFormatter;
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

    /** @var modTransportProvider $provider */
    public $provider;

    /** @var modTransportPackage $object */
    public $object;

    private modManagerDateFormatter $formatter;

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->modx->addPackage('Revolution\Transport', MODX_CORE_PATH . 'src/');
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);
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
        $packageArray['created'] = $this->formatter->formatPackageDate($this->object->get('created'));
        $packageArray['installed'] = $this->formatter->formatPackageDate(
            $this->object->get('installed'),
            'installed',
            false
        );
        $packageArray['updated'] = $this->formatter->formatPackageDate(
            $this->object->get('updated'),
            'updated',
            false
        );
        return $packageArray;
    }
}
