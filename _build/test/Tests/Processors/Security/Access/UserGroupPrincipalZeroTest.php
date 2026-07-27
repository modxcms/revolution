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
namespace MODX\Revolution\Tests\Processors\Security\Access;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\Access\UserGroup\Context\Create as ContextCreate;
use MODX\Revolution\Processors\Security\Access\UserGroup\Context\Update as ContextUpdate;
use MODX\Revolution\Processors\Security\Access\UserGroup\ResourceGroup\Create as ResourceGroupCreate;
use MODX\Revolution\Processors\Security\Access\UserGroup\ResourceGroup\Update as ResourceGroupUpdate;
use MODX\Revolution\Processors\Processor;

/**
 * Regression for #15062: principal ID 0 must not be treated as missing (== null).
 *
 * @package modx-test
 * @group Processors
 * @group Security
 * @group Access
 */
class UserGroupPrincipalZeroTest extends MODxTestCase
{
    /**
     * @param class-string<Processor> $processorClass
     * @dataProvider providerUserGroupAclProcessors
     */
    public function testPrincipalZeroIsNotRejectedAsMissing($processorClass)
    {
        $this->modx->lexicon->load('access', 'user', 'context');

        /** @var Processor $processor */
        $processor = new $processorClass($this->modx, [
            'principal' => 0,
            'target' => 1,
            'policy' => 1,
            'authority' => 0,
        ]);

        $processor->beforeSet();

        $this->assertFalse(
            $this->hasFieldError($processor, 'principal'),
            $processorClass . ' must accept principal 0 without usergroup_err_ns'
        );
    }

    /**
     * @return array<string, array{0: class-string<Processor>}>
     */
    public function providerUserGroupAclProcessors()
    {
        return [
            'context create' => [ContextCreate::class],
            'context update' => [ContextUpdate::class],
            'resource group create' => [ResourceGroupCreate::class],
            'resource group update' => [ResourceGroupUpdate::class],
        ];
    }

    /**
     * @param Processor $processor
     * @param string $field
     * @return bool
     */
    private function hasFieldError(Processor $processor, $field)
    {
        foreach ($this->modx->error->getErrors(true) as $error) {
            if (is_array($error) && isset($error['id']) && $error['id'] === $field) {
                return true;
            }
        }

        return false;
    }
}
