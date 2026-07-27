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
 */

namespace MODX\Revolution\Tests\Processors\System;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Processor;

/**
 * Contract for Processor::logProgress console protocol (#16296).
 *
 * @group Processors
 * @group System
 */
class ConsoleProgressProtocolTest extends MODxTestCase
{
    public function testLogProgressUsesIsolatedPrefixAndClamps()
    {
        $source = file_get_contents(MODX_CORE_PATH . 'src/Revolution/Processors/Processor.php');
        $this->assertStringContainsString("__MODX_PROGRESS__:indeterminate", $source);
        $this->assertStringContainsString("'__MODX_PROGRESS__:' . \$current . ':' . \$total", $source);

        $console = file_get_contents(MODX_CORE_PATH . 'src/Revolution/Processors/System/Console.php');
        $this->assertStringContainsString("show_progress", $console);
        $this->assertStringContainsString('__MODX_PROGRESS__:', $console);
        $this->assertStringNotContainsString("strpos(\$message['msg'], 'PROGRESS:')", $console);

        $stub = new class ($this->modx) extends Processor {
            public function process()
            {
                return $this->success();
            }
        };
        $stub->logProgress(0, 0);
        $stub->logProgress(9, 5);
        $this->assertTrue(true);
    }
}
