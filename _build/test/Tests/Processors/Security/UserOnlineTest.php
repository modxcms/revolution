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

namespace MODX\Revolution\Tests\Processors\Security;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\User\GetOnline;
use ReflectionClass;

/**
 * Ensures the security/user/getonline processor is gated by an authorization
 * permission.
 *
 * GetOnline extends GetListProcessor and surfaces user account information for
 * the manager "who is online" widget. ModelProcessor::checkPermissions() only
 * enforces access when the processor declares a $permission, so the processor
 * must declare one explicitly rather than relying on the permissive default.
 * The suite runs as a sudo admin, so a functional call would pass regardless of
 * the permission; this structural check asserts the permission is present.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Security
 * @group User
 * @group UserProcessors
 */
class UserOnlineTest extends MODxTestCase
{
    /**
     * GetOnline must declare a non-empty $permission so that
     * ModelProcessor::checkPermissions() consults hasPermission() rather than
     * returning true unconditionally.
     */
    public function testGetOnlineRequiresPermission()
    {
        $defaults = (new ReflectionClass(GetOnline::class))->getDefaultProperties();

        $this->assertArrayHasKey('permission', $defaults);
        $this->assertNotEmpty(
            $defaults['permission'],
            'GetOnline must declare a $permission so access to the who-is-online data is gated.'
        );
        $this->assertSame('view_user', $defaults['permission']);
    }
}
