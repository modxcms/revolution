<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2011 by MODX, LLC.
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
 * Tests related to element/template/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Template
 * @group TemplateProcessors
 */
class TemplateProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/template/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $template = $modx->getObject('modTemplate',array('templatename' => 'UnitTestTemplate'));
        if ($template) $template->remove();
        $template = $modx->getObject('modTemplate',array('templatename' => 'UnitTestTemplate2'));
        if ($template) $template->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $template = $modx->getObject('modTemplate',array('templatename' => 'UnitTestTemplate'));
        if ($template) $template->remove();
        $template = $modx->getObject('modTemplate',array('templatename' => 'UnitTestTemplate2'));
        if ($template) $template->remove();
    }

    /**
     * Tests the element/template/create processor, which creates a Template
     * @dataProvider providerTemplateCreate
     */
    public function testTemplateCreate($shouldPass,$templatePk) {
        if (empty($templatePk)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'templatename' => $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modTemplate',array('templatename' => $templatePk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/create processor test.
     */
    public function providerTemplateCreate() {
        return array(
            array(true,'UnitTestTemplate'),
            array(true,'UnitTestTemplate2'),
            array(false,'UnitTestTemplate2'),
        );
    }

    /**
     * Tests the element/template/get processor, which gets a Template
     * @dataProvider providerTemplateGet
     */
    public function testTemplateGet($shouldPass,$templatePk) {
        if (empty($templatePk)) return false;

        $template = $this->modx->getObject('modTemplate',array('templatename' => $templatePk));
        if (empty($template) && $shouldPass) {
            $this->fail('No Template found "'.$templatePk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $template ? $template->get('id') : $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/create processor test.
     */
    public function providerTemplateGet() {
        return array(
            array(true,'UnitTestTemplate'),
            array(false,234),
        );
    }
    
    /**
     * Attempts to get a list of templates
     *
     * @dataProvider providerTemplateGetList
     */
    public function testTemplateGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getList',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
        ));
        $results = $this->getResults($result);
        $this->assertTrue(!empty($results),'Could not get list of Templates: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/getlist processor test.
     */
    public function providerTemplateGetList() {
        return array(
            array('templatename','ASC',5,0),
        );
    }

    /**
     * Tests the element/template/remove processor, which removes a Template
     * @dataProvider providerTemplateRemove
     */
    public function testTemplateRemove($shouldPass,$templatePk) {
        if (empty($templatePk)) return false;

        $template = $this->modx->getObject('modTemplate',array('templatename' => $templatePk));
        if (empty($template) && $shouldPass) {
            $this->fail('No Template found "'.$templatePk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $template ? $template->get('id') : $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/remove processor test.
     */
    public function providerTemplateRemove() {
        return array(
            array(true,'UnitTestTemplate'),
            array(false,234),
        );
    }
}