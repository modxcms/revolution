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
namespace MODX\Revolution\Tests\Model\Transport;


use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Transport\modPackageBuilder;

/**
 * Tests related to the modPackageBuilder class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group modPackageBuilder
 */
class modPackageBuilderTest extends MODxTestCase {
    /**
     * modPackageBuilder must declare $modx to avoid PHP 8.2 dynamic property deprecations.
     */
    public function testConstructorDoesNotCreateDynamicModxProperty()
    {
        $deprecations = [];
        set_error_handler(static function ($severity, $message) use (&$deprecations) {
            if ($severity === E_DEPRECATED && str_contains($message, 'dynamic property')) {
                $deprecations[] = $message;
            }
            return true;
        }, E_DEPRECATED);

        try {
            $builder = new modPackageBuilder($this->modx);
        } finally {
            restore_error_handler();
        }

        $this->assertSame([], $deprecations, 'Expected no dynamic property deprecations on construction');
        $this->assertInstanceOf(modX::class, $builder->modx);
        $this->assertSame($this->modx, $builder->modx);
    }
}
