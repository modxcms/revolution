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
namespace MODX\Revolution\Tests\Processors\Element;


use MODX\Revolution\modProcessorResponse;
use MODX\Revolution\modTemplate;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\Template\Create;
use MODX\Revolution\Processors\Element\Template\Get;
use MODX\Revolution\Processors\Element\Template\GetList;
use MODX\Revolution\Processors\Element\Template\Remove;

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
    public function setUp() {
        parent::setUp();
        $this->modx->error->reset();
        /** @var modTemplate $template */
        $template = $this->modx->newObject(modTemplate::class);
        $template->fromArray(array('templatename' => 'UnitTestTemplate'));
        $template->save();

    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        $templates = $this->modx->getCollection(modTemplate::class,array('templatename:LIKE' => '%UnitTest%'));
        /** @var modTemplate $template */
        foreach ($templates as $template) {
            $template->remove();
        }
        $this->modx->error->reset();
    }

    /**
     * Tests the element/template/create processor, which creates a Template
     *
     * @param boolean $shouldPass
     * @param string $templatePk
     * @dataProvider providerTemplateCreate
     */
    public function testTemplateCreate($shouldPass,$templatePk) {
        if (empty($templatePk)) return;
        $result = $this->modx->runProcessor(Create::class,array(
            'templatename' => $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Create::class.' processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount(modTemplate::class,array('templatename' => $templatePk));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/create processor test.
     * @return array
     */
    public function providerTemplateCreate() {
        return array(
            array(true,'UnitTestTemplate2'),
            array(true,'UnitTestTemplate3'),
            array(false,'UnitTestTemplate'),
        );
    }

    /**
     * Tests the element/template/get processor, which gets a Template
     * @param boolean $shouldPass
     * @param string $templatePk
     * @dataProvider providerTemplateGet
     */
    public function testTemplateGet($shouldPass,$templatePk) {
        if (empty($templatePk)) return;

        $template = $this->modx->getObject(modTemplate::class,array('templatename' => $templatePk));
        if (empty($template) && $shouldPass) {
            $this->fail('No Template found "'.$templatePk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(Get::class,array(
            'id' => $template ? $template->get('id') : $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Get::class.' processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/create processor test.
     * @return array
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
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @dataProvider providerTemplateGetList
     */
    public function testTemplateGetList($sort = 'key',$dir = 'ASC',$limit = 10,$start = 0) {
        $result = $this->modx->runProcessor(GetList::class,array(
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
     * @return array
     */
    public function providerTemplateGetList() {
        return array(
            array('templatename','ASC',5,0),
        );
    }

    /**
     * Tests the element/template/remove processor, which removes a Template
     *
     * @param boolean $shouldPass
     * @param string $templatePk
     * @dataProvider providerTemplateRemove
     */
    public function testTemplateRemove($shouldPass,$templatePk) {
        if (empty($templatePk)) return;

        /** @var modTemplate $template */
        $template = $this->modx->getObject(modTemplate::class,array('templatename' => $templatePk));
        if (empty($template) && $shouldPass) {
            $this->fail('No Template found "'.$templatePk.'" as specified in test provider.');
            return;
        }
        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(Remove::class,array(
            'id' => $template ? $template->get('id') : $templatePk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.Remove::class.' processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Template: `'.$templatePk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/template/remove processor test.
     * @return array
     */
    public function providerTemplateRemove() {
        return array(
            array(true,'UnitTestTemplate'),
            array(false,234),
        );
    }
}
