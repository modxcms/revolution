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
namespace MODX\Revolution\Tests\Model\Security;

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to modAccessPolicy core policy resolution.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Access
 * @group modAccessPolicy
 */
class modAccessPolicyTest extends MODxTestCase
{
    /**
     * Renamed core Resource policy must still resolve for parallel resource groups (#13831).
     */
    public function testGetPolicyFindsRenamedResourcePolicy()
    {
        /** @var modAccessPolicy|null $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, [
            'name' => modAccessPolicy::POLICY_RESOURCE,
        ]);
        if (!$policy instanceof modAccessPolicy) {
            $this->markTestSkipped('Core Resource access policy is not installed.');
        }

        $originalName = $policy->get('name');
        $policyId = (int)$policy->get('id');
        $renamed = $originalName . '_renamed_13831';

        $policy->set('name', $renamed);
        $this->assertTrue($policy->save(), 'Failed to rename Resource policy for the test.');

        try {
            $this->assertNull(
                $this->modx->getObject(modAccessPolicy::class, ['name' => modAccessPolicy::POLICY_RESOURCE]),
                'Direct name lookup must fail after rename.'
            );

            $resolved = modAccessPolicy::getPolicy($this->modx, modAccessPolicy::POLICY_RESOURCE);
            $this->assertInstanceOf(modAccessPolicy::class, $resolved);
            $this->assertSame($policyId, (int)$resolved->get('id'));
            $this->assertSame($renamed, $resolved->get('name'));
        } finally {
            $policy->set('name', $originalName);
            $policy->save();
        }
    }

    /**
     * Unchanged core policy name still resolves via getPolicy().
     */
    public function testGetPolicyFindsResourcePolicyByName()
    {
        $resolved = modAccessPolicy::getPolicy($this->modx, modAccessPolicy::POLICY_RESOURCE);
        if (!$resolved instanceof modAccessPolicy) {
            $this->markTestSkipped('Core Resource access policy is not installed.');
        }

        $this->assertSame(modAccessPolicy::POLICY_RESOURCE, $resolved->get('name'));
    }

    /**
     * Ambiguous ResourceTemplate (renamed core + duplicate) must fail closed.
     */
    public function testGetPolicyFailsClosedWhenMultiplePoliciesShareTemplate()
    {
        /** @var modAccessPolicy|null $policy */
        $policy = $this->modx->getObject(modAccessPolicy::class, [
            'name' => modAccessPolicy::POLICY_RESOURCE,
        ]);
        if (!$policy instanceof modAccessPolicy) {
            $this->markTestSkipped('Core Resource access policy is not installed.');
        }

        $originalName = $policy->get('name');
        $templateId = (int)$policy->get('template');
        $duplicate = null;

        $policy->set('name', $originalName . '_renamed_13831_ambiguous');
        $this->assertTrue($policy->save());

        try {
            $duplicate = $this->modx->newObject(modAccessPolicy::class);
            $duplicate->fromArray([
                'name' => 'Resource Duplicate 13831',
                'description' => 'Temporary duplicate for #13831 ambiguity test',
                'parent' => 0,
                'template' => $templateId,
                'class' => '',
                'data' => $policy->get('data'),
                'lexicon' => $policy->get('lexicon'),
            ]);
            $this->assertTrue($duplicate->save());

            $this->assertNull(
                modAccessPolicy::getPolicy($this->modx, modAccessPolicy::POLICY_RESOURCE),
                'Multiple policies on ResourceTemplate must not silently pick one.'
            );
        } finally {
            if ($duplicate instanceof modAccessPolicy && !$duplicate->isNew()) {
                $duplicate->remove();
            }
            $policy->set('name', $originalName);
            $policy->save();
        }
    }
}
