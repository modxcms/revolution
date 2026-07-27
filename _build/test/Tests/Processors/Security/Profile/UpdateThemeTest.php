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
namespace MODX\Revolution\Tests\Processors\Security\Profile;


use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Security\Profile\UpdateTheme;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the security/profile/updatetheme processor, which stores
 * the current user's manager theme preference (light/dark/system).
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Security
 * @group Profile
 * @group UpdateTheme
 */
class UpdateThemeProcessorTest extends MODxTestCase {
    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures() {
        parent::setUpFixtures();
        $this->removeThemeSetting();
    }

    /**
     * Cleanup data after this test.
     *
     * @after
     */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
        $this->removeThemeSetting();
    }

    private function removeThemeSetting() {
        $setting = $this->modx->getObject(modUserSetting::class, [
            'key' => 'manager_dark_mode',
            'user' => $this->modx->user->get('id'),
        ]);
        if ($setting) {
            $setting->remove();
        }
    }

    /**
     * Tests the security/profile/updatetheme processor with each allowed value.
     *
     * @param string $value
     * @dataProvider providerAllowedValues
     */
    public function testAllowedValuesAreSaved($value) {
        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(UpdateTheme::class, ['value' => $value]);
        $this->assertNotEmpty($result, 'Could not load ' . UpdateTheme::class . ' processor');

        $s = $this->checkForSuccess($result);
        $this->assertTrue($s, 'Processor failed for allowed value `' . $value . '`: ' . $result->getMessage());

        /** @var modUserSetting $setting */
        $setting = $this->modx->getObject(modUserSetting::class, [
            'key' => 'manager_dark_mode',
            'user' => $this->modx->user->get('id'),
        ]);
        $this->assertNotEmpty($setting, 'Expected a modUserSetting row for manager_dark_mode');
        $this->assertEquals($value, $setting->get('value'));
    }

    /**
     * Data provider of allowed theme values.
     * @return array
     */
    public function providerAllowedValues() {
        return [
            ['light'],
            ['dark'],
            ['system'],
        ];
    }

    /**
     * Tests that an existing preference is updated in place rather than
     * duplicated when the user switches theme again.
     */
    public function testChangingValueUpdatesExistingSetting() {
        $first = $this->modx->runProcessor(UpdateTheme::class, ['value' => 'dark']);
        $this->assertTrue($this->checkForSuccess($first), 'Could not save initial value: ' . $first->getMessage());

        $second = $this->modx->runProcessor(UpdateTheme::class, ['value' => 'light']);
        $this->assertTrue($this->checkForSuccess($second), 'Could not save updated value: ' . $second->getMessage());

        $count = $this->modx->getCount(modUserSetting::class, [
            'key' => 'manager_dark_mode',
            'user' => $this->modx->user->get('id'),
        ]);
        $this->assertEquals(1, $count, 'Expected exactly one manager_dark_mode row for the user, found ' . $count);

        /** @var modUserSetting $setting */
        $setting = $this->modx->getObject(modUserSetting::class, [
            'key' => 'manager_dark_mode',
            'user' => $this->modx->user->get('id'),
        ]);
        $this->assertEquals('light', $setting->get('value'));
    }

    /**
     * Tests that values outside the allowlist are rejected and nothing is
     * persisted.
     *
     * @param mixed $value
     * @dataProvider providerDisallowedValues
     */
    public function testDisallowedValuesAreRejected($value) {
        $result = $this->modx->runProcessor(UpdateTheme::class, ['value' => $value]);
        $this->assertNotEmpty($result, 'Could not load ' . UpdateTheme::class . ' processor');

        $s = $this->checkForSuccess($result);
        $this->assertFalse($s, 'Processor succeeded for a disallowed value: ' . var_export($value, true));

        $count = $this->modx->getCount(modUserSetting::class, [
            'key' => 'manager_dark_mode',
            'user' => $this->modx->user->get('id'),
        ]);
        $this->assertEquals(0, $count, 'A disallowed value should not be persisted.');
    }

    /**
     * Data provider of disallowed/invalid theme values.
     * @return array
     */
    public function providerDisallowedValues() {
        return [
            ['auto'],
            [''],
            ['Dark'],
            ['light; drop table modx_user_settings'],
        ];
    }
}
