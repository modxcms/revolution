<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Tests\Controllers;

/**
 * Records modX::invokeEvent calls for assertions.
 *
 * @internal
 */
final class ModxInvokeEventCallLog
{
    /**
     * @var list<array{0: string, 1: array}>
     */
    private array $invocations = [];

    public function record(string $eventName, array $params): void
    {
        $this->invocations[] = [$eventName, $params];
    }

    /**
     * @return list<array{0: string, 1: array}>
     */
    public function getInvocations(): array
    {
        return $this->invocations;
    }
}
