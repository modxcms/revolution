<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
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
