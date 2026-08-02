<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 * @internal
 */

namespace MODX\Revolution\Tests\Processors\Workspace\Packages\Support;

use MODX\Revolution\Processors\Workspace\Packages\Purge;

/**
 * @internal
 */
final class PurgeProcessorTestDouble extends Purge
{
    public array $filesystemOperations = [];

    public function removeTransportZip(string $transportZip): void
    {
        $this->filesystemOperations[] = ['zip', $transportZip];
    }

    public function removeTransportDirectory(string $transportDir): void
    {
        $this->filesystemOperations[] = ['dir', $transportDir];
    }
}
