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
class modInputFilterTest extends MODxTestCase
{
    /**
     * @dataProvider providerFilter
     * @param $elementName
     * @param $expectedElementName
     * @param $expectedComands
     * @param $expectedModifiers
     * @return void
     */
    public function testFilter($elementName, $expectedElementName, $expectedComands, $expectedModifiers)
    {
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
    public function providerFilter()
    {
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
            ],
            [
                'content:notempty=`[[!Wayfinder? &startId=`0` &level=`1`]]`',
                'content',
                [
                    'notempty',
                ],
                [
                    '[[!Wayfinder? &startId=`0` &level=`1`]]',
                ]
            ],
            [
                'pagetitle:default=`[[*longtitle]]`:toPlaceholder=`title`',
                'pagetitle',
                [
                    'default',
                    'toPlaceholder',
                ],
                [
                    '[[*longtitle]]',
                    'title',
                ]
            ],
            [
                'echoinput:input=`[[++mail_smtp_hosts]] [[echoinput:eq=`0`:then=`test1:[[++mail_smtp_hosts]];`:else=`test2:[[++mail_smtp_hosts]]`?input=`0`]]`',
                'echoinput',
                [
                    'input',
                ],
                [
                    '[[++mail_smtp_hosts]] [[echoinput:eq=`0`:then=`test1:[[++mail_smtp_hosts]];`:else=`test2:[[++mail_smtp_hosts]]`?input=`0`]]',
                ]
            ],
            [
                'content:notempty=`[[!Wayfinder? &startId=`0` &level=`1`]]`',
                'content',
                [
                    'notempty',
                ],
                [
                    '[[!Wayfinder? &startId=`0` &level=`1`]]',
                ]
            ],
            [
                'content:up:trim',
                'content',
                [
                    'up',
                    'trim',
                ],
                [
                    '',
                    '',
                ]
            ]
        ];
    }
}
