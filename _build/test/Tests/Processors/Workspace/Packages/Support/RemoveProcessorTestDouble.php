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

use MODX\Revolution\Processors\Workspace\Packages\Remove;

/**
 * @internal
 */
final class RemoveProcessorTestDouble extends Remove
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

    public function clearCache(): void
    {
        /* Parent calls sleep(2); skipped for fast unit tests. */
    }

    public function cleanup(): array
    {
        $this->modx->invokeEvent('OnPackageRemove', [
            'package' => $this->package,
        ]);

        return $this->success();
    }
}
