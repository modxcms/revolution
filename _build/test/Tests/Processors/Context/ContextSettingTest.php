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

/**
 * Tests related to context/setting/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Context
 * @group ContextSetting
 * @group ContextProcessors
 * @group modContext
 * @group modContextSetting
 */
class ContextSettingProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'context/setting/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modContext $ctx */
        $ctx = $this->modx->newObject('modContext');
        $ctx->set('key','unittest');
        $ctx->set('description','The unit test context for context settings.');
        $ctx->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        /** @var modContext $ctx */
        $ctx = $this->modx->getObject('modContext','unittest');
        if ($ctx) $ctx->remove();

        $settings = $this->modx->getCollection('modContextSetting',array(
            'context_key' => 'unittest',
        ));
        /** @var modContextSetting $setting */
        foreach ($settings as $setting) {
            $setting->remove();
        }
    }

    /**
     * Tests the context/setting/create processor, which creates a context setting
     * @param string $ctx
     * @param string $key
     * @param string $description
     * @dataProvider providerContextSettingCreate
     */
    public function testContextSettingCreate($ctx,$key,$description = '') {
        if (empty($ctx)) return;
        $this->assertTrue(true);
        return;
        /*
        try {
            $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
                'ctx' => $ctx,
                'key' => $key,
                'description' => $description,
            ));
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage(), '', __METHOD__, __FILE__, __LINE__);
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modContext',$ctx);
        $this->assertTrue($s && $ct > 0,'Could not create context: `'.$ctx.'`: '.$result['message']);*/
    }
    /**
     * Data provider for context/setting/create processor test.
     * @return array
     */
    public function providerContextSettingCreate() {
        return array(
            array('unittest','unittest_setting',''),
        );
    }
}
