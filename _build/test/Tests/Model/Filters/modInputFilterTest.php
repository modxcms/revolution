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
namespace MODX\Revolution\Tests\Model\Filters;


use MODX\Revolution\Filters\modInputFilter;
use MODX\Revolution\modFieldTag;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modInputFilter class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Filters
 * @group modInputFilter
 */
class modInputFilterTest extends MODxTestCase {

    /**
     * @dataProvider providerFilter
     * @param $elementName
     * @param $expectedElementName
     * @param $expectedComands
     * @param $expectedModifiers
     * @return void
     */
    public function testFilter($elementName, $expectedElementName, $expectedComands, $expectedModifiers) {
        $element = new modFieldTag($this->modx);
        $element->set('name', $elementName);

        $modInputFilter = new modInputFilter($this->modx);
        $modInputFilter->filter($element);

        $this->assertEquals($expectedElementName, $element->get('name'));
        $this->assertEquals($expectedComands, $modInputFilter->getCommands());
        $this->assertEquals($expectedModifiers, $modInputFilter->getModifiers());
    }

    /**
     * dataProvider for testFilter
     */
    public function providerFilter() {
        return [
            [
                'pagetitle:eq=`0`:then=`foo`:else=`bar`',
                'pagetitle',
                [
                    'eq',
                    'then',
                    'else',
                ],
                [
                    '0',
                    'foo',
                    'bar',
                ]
            ]
        ];
    }
}
