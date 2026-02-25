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

namespace MODX\Revolution\Tests\Processors;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Processor;

/**
 * Tests for Processor::getBooleanProperty() normalization.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Processor
 */
class ProcessorTest extends MODxTestCase
{
    /**
     * @return Processor
     */
    private function createProcessor(array $properties = []): Processor
    {
        return new class ($this->modx, $properties) extends Processor {
            public function process()
            {
                return $this->success();
            }
        };
    }

    public function testGetBooleanPropertyStringTrue(): void
    {
        $p = $this->createProcessor(['flag' => 'true']);
        $this->assertTrue($p->getBooleanProperty('flag', false));
    }

    public function testGetBooleanPropertyStringTrueCaseInsensitive(): void
    {
        $p = $this->createProcessor(['flag' => 'TRUE']);
        $this->assertTrue($p->getBooleanProperty('flag', false));

        $p = $this->createProcessor(['flag' => 'True']);
        $this->assertTrue($p->getBooleanProperty('flag', false));
    }

    public function testGetBooleanPropertyStringTrueTrimmed(): void
    {
        $p = $this->createProcessor(['flag' => ' true ']);
        $this->assertTrue($p->getBooleanProperty('flag', false));
    }

    public function testGetBooleanPropertyStringFalse(): void
    {
        $p = $this->createProcessor(['flag' => 'false']);
        $this->assertFalse($p->getBooleanProperty('flag', true));
    }

    public function testGetBooleanPropertyStringFalseCaseInsensitive(): void
    {
        $p = $this->createProcessor(['flag' => 'FALSE']);
        $this->assertFalse($p->getBooleanProperty('flag', true));

        $p = $this->createProcessor(['flag' => ' False ']);
        $this->assertFalse($p->getBooleanProperty('flag', true));
    }

    public function testGetBooleanPropertyOnYesOne(): void
    {
        $p = $this->createProcessor(['a' => 'on', 'b' => 'yes', 'c' => '1']);
        $this->assertTrue($p->getBooleanProperty('a', false));
        $this->assertTrue($p->getBooleanProperty('b', false));
        $this->assertTrue($p->getBooleanProperty('c', false));
    }

    public function testGetBooleanPropertyOffNoZero(): void
    {
        $p = $this->createProcessor(['a' => 'off', 'b' => 'no', 'c' => '0']);
        $this->assertFalse($p->getBooleanProperty('a', true));
        $this->assertFalse($p->getBooleanProperty('b', true));
        $this->assertFalse($p->getBooleanProperty('c', true));
    }

    public function testGetBooleanPropertyMissingKeyReturnsDefault(): void
    {
        $p = $this->createProcessor([]);
        $this->assertFalse($p->getBooleanProperty('missing', false));
        $this->assertTrue($p->getBooleanProperty('missing', true));
    }

    public function testGetBooleanPropertyNativeBoolean(): void
    {
        $p = $this->createProcessor(['t' => true, 'f' => false]);
        $this->assertTrue($p->getBooleanProperty('t', false));
        $this->assertFalse($p->getBooleanProperty('f', true));
    }

    public function testGetBooleanPropertyNonStringCastedToBool(): void
    {
        $p = $this->createProcessor(['one' => 1, 'zero' => 0]);
        $this->assertTrue($p->getBooleanProperty('one', false));
        $this->assertFalse($p->getBooleanProperty('zero', true));
    }

    public function testGetBooleanPropertyUnknownStringReturnsDefault(): void
    {
        $p = $this->createProcessor(['key' => 'unknown']);
        $this->assertFalse($p->getBooleanProperty('key', false));
        $this->assertTrue($p->getBooleanProperty('key', true));
    }

    public function testGetBooleanPropertyEmptyStringReturnsDefault(): void
    {
        $p = $this->createProcessor(['key' => '']);
        $this->assertFalse($p->getBooleanProperty('key', false));
        $this->assertTrue($p->getBooleanProperty('key', true));
    }
}
