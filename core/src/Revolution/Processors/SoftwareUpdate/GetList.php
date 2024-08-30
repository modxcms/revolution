<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\SoftwareUpdate;

use MODX\Revolution\Processors\SoftwareUpdate\Base;
use MODX\Revolution\Processors\Workspace\Packages\GetList as PackagesGetList;
use MODX\Revolution\Transport\modTransportPackage;
use Psr\Http\Client\ClientExceptionInterface;
use MODX\Revolution\modX;

/**
 * Retrieves status data for use in the front end display of software updates (MODX and Extras)
 *
 * @property string $softwareType Identifies which type of software status data should be
 * retrieved (currently only two options: 'modx' or 'extras')
 * @package MODX\Revolution\Processors\SoftwareUpdate
 */
class GetList extends Base
{
    public $installedVersionData;

    public function initialize()
    {
        $this->installedVersionData = $this->modx->getVersionData();
        return parent::initialize();
    }

    public function process()
    {
        $softwareType = $this->getProperty('softwareType', 'modx');
        $categoryData = [
            'updateable' => 0
        ];
        if ($softwareType === 'modx') {
            $modxData = $this->getModxUpdates();
            if (is_array($modxData)) {
                $categoryData = array_merge($categoryData, $modxData);
            }
        } else {
            $extrasData = $this->getExtrasUpdates();
            if (is_array($extrasData)) {
                $categoryData = array_merge($categoryData, $extrasData);
            }
        }
        return $this->success('', $categoryData);
    }

    public function getModxUpdates()
    {
        $this->initApiClient();

        $uri = $this->getRequestUri([
            'current' => $this->installedVersionData['full_version'],
            'level' => 'major',
            'variant' => 'Traditional',
            'prereleases' => 0
        ]);

        $request = $this->apiFactory->createRequest('GET', $uri)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
        try {
            $response = $this->apiClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'ClientExceptionInterface Err: ' . $e->getMessage());
            return $this->failure($e->getMessage());
        }

        $listData = $response->getBody()->getContents();
        $categoryData = [];
        if ($listData) {
            $listData = json_decode($listData, true);
            $upgrades = $listData['upgrades'];
            $selectedUpgrade = null;
            if (!empty($upgrades)) {
                $i = 0;
                $upgradesCount = count($upgrades);
                if ($upgradesCount === 1) {
                    $categoryData['updateable'] = 1;
                    $selectedUpgrade = $upgrades;
                } else {
                    foreach ($upgrades as $upgrade) {
                        $selectedUpgrade = $upgrade;
                        break;
                    }
                    $categoryData['updateable'] = (int)version_compare($this->installedVersionData['full_version'], $upgrade['version'], '<');
                }
                if ($categoryData['updateable']) {
                    /*
                        NOTE: This is superfluous now, but is done in preparation
                        for iterating through multiple displayable versions
                    */
                    $categoryData['versions'][$i]['version'] = $selectedUpgrade['version'];
                    $urlSegments = explode('/', trim($selectedUpgrade['url'], '/'));
                    $categoryData['versions'][$i]['downloadId'] = $urlSegments[count($urlSegments) - 2];

                    $categoryData['latest']['version'] = $categoryData['versions'][0]['version'];
                    $categoryData['latest']['downloadId'] = $categoryData['versions'][0]['downloadId'];
                }
            }
        }
        return $categoryData;
    }

    public function getExtrasUpdates()
    {
        $categoryData = [];
        $packages = $this->modx->call(modTransportPackage::class, 'listPackages', [$this->modx, 1]);
        if ($packages && array_key_exists('collection', $packages)) {
            $packagesProcessor = new PackagesGetList($this->modx);

            /** @var modTransportPackage $package */
            foreach ($packages['collection'] as $package) {
                $tmp = [];
                $tmp = $packagesProcessor->checkForUpdates($package, $tmp);
                if (!empty($tmp['updateable'])) {
                    $categoryData['names'][] = $package->get('package_name');
                    $categoryData['updateable']++;
                }
            }
        }
        return $categoryData;
    }
}
