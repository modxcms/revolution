<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Providers;

use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\Transport\modTransportProvider;

/**
 * Remove a provider
 * @param integer $id The provider ID
 * @package MODX\Revolution\Processors\Workspace\Providers
 */
class Remove extends RemoveProcessor
{
    public $classKey = modTransportProvider::class;
    public $languageTopics = ['workspace'];
    public $permission = 'providers';
    public $objectType = 'provider';
}
