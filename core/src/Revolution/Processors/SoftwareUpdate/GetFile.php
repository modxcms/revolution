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
use Psr\Http\Client\ClientExceptionInterface;
use MODX\Revolution\modX;

/**
 * Retrieves the downloadable file URL and other metadata for the specified MODX upgrade package
 *
 * @property string $downloadId An identifier used to retrieve the package's download URL
 * @package MODX\Revolution\Processors\SoftwareUpdate
 */
class GetFile extends Base
{
    public function process()
    {
        $downloadId = $this->getProperty('downloadId', null);
        $responseData = [];

        if ($downloadId) {
            $this->initApiClient();

            $uri = $this->getRequestUri(['uuid' => $this->modx->uuid], $downloadId);
            $request = $this->apiFactory->createRequest('GET', $uri)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Content-Type', 'application/json');
            try {
                $response = $this->apiClient->sendRequest($request);
            } catch (ClientExceptionInterface $e) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
                return $this->failure($e->getMessage());
            }

            $fileData = $response->getBody()->getContents();

            if ($fileData) {
                $fileData = json_decode($fileData, true);
                if (!empty($fileData['zip_url']) && strpos($fileData['zip_url'], 'http') === 0) {
                    $name = basename($fileData['zip_url']);
                    $responseData['filename'] = $name;
                    $responseData['zip'] = $fileData['zip_url'];
                    $responseData['status'] = $response->getStatusCode();
                }
            }
            return $this->success('', $responseData);
        }
    }
}
