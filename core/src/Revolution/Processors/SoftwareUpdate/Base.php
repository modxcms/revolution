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

use MODX\Revolution\Processors\Processor;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use MODX\Revolution\modX;

/**
 * Provides base methods and shared properties for building status data used
 * in the front end display of software updates (MODX and Extras)
 *
 * @package MODX\Revolution\Processors\SoftwareUpdate
 */
class Base extends Processor
{
    public $apiClient = null;
    public $apiFactory = null;
    public $apiHost = 'https://sentinel.modx.com';
    public $apiGetReleasesPath = '/releases/products/997329d1-6f68-48d5-8e5e-5251adbb1f38/upgrades/';
    public $apiGetFilePath = '/releases/variants/[downloadId]/download/';

    public function process()
    {
        return parent::process();
    }

    /**
     * Initialize the client responsible for fetching upgrades-related data.
     *
     * @return
     */
    public function initApiClient()
    {
        if (!$this->apiClient) {
            $this->apiClient = $this->modx->services->get(ClientInterface::class);
            $this->apiFactory = $this->modx->services->get(RequestFactoryInterface::class);
        }
    }

    /**
     * Builds the API link used to fetch file data
     *
     * @param array $requestParams Query parameters
     * @param string $targetId An intermediate id used to fetch the actual download link
     * @return string The full URI to pass into the upgrades API
     */
    public function buildRequestUri(array $requestParams = [], string $targetId = ''): string
    {
        $uri = $this->apiHost;
        /*
            When a $targetId is passed in, we are making the final request whose response
            reveals the real update file path. Otherwise the request gets a full list of
            potential upgrades based on criteria passed in the $requestParams
        */
        $uri .= !empty($targetId)
            ? str_replace('[downloadId]', $targetId, $this->apiGetFilePath)
            : $this->apiGetReleasesPath
            ;
        if (count($requestParams) > 0) {
            $uri .= '?' . http_build_query($requestParams);
        }
        return $uri;
    }
}
