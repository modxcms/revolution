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
namespace MODX\Revolution\Tests\Model\Element;


use MODX\Revolution\modElement;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modElement class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modElement
 */
class modElementTest extends MODxTestCase {
    /**
     * Test the modElement->getProperties() method.
     *
     * @dataProvider providerGetProperties
     * @param string $name The name of the element.
     * @param string|array|null $properties The element properties value.
     * @param string|array|null $addProperties Additional properties passed to getProperties()
     * @param array $expected The expected array of properties.
     */
    public function testGetProperties($name, $properties, $addProperties, $expected) {
        /** @var modElement $element */
        $element = $this->modx->newObject(modElement::class);
        $element->set('name', $name);
        $element->setProperties($properties);
        $actual = $element->getProperties($addProperties);
        $this->assertEquals($expected, $actual, "Expected properties were not found.");
    }
    public function providerGetProperties() {
        return [
            [
                'element1',
                '',
                null,
                []
            ],
            [
                'element2',
                'food=beer&bard=muse',
                null,
                [
                    'food' => 'beer',
                    'bard' => 'muse'
                ]
            ],
            [
                'element3',
                [
                    'food' => 'beer',
                    'bard' => 'muse'
                ],
                null,
                [
                    'food' => 'beer',
                    'bard' => 'muse'
                ]
            ],
            [
                'element4',
                [
                    'food' => 'beer',
                ],
                [
                    'bard' => 'muse'
                ],
                [
                    'food' => 'beer',
                    'bard' => 'muse'
                ]
            ],
        ];
    }

    /**
     * Test the modElement->process() method.
     *
     * @dataProvider providerProcess
     * @param $properties
     * @param $content
     */
    public function testProcess($name, $tag, $properties, $content, $expected) {
        /** @var modElement $element */
        $element = $this->modx->newObject(modElement::class);
        $element->set('name', $name);
        $element->process($properties, $content);
        $result = [
            $element->_content,
            $element->_properties,
            $element->_result,
            $element->_processed,
            $element->get('name'),
            $element->_tag,
        ];
        $this->assertEquals($expected, $result, "Did not get expected results");
    }
    public function providerProcess() {
        return [
            [
                'element1',
                '[[element1]]',
                [
                    'property1' => 'value1',
                    'property2' => 'value2',
                ],
                "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                [
                    "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                    [
                        'property1' => 'value1',
                        'property2' => 'value2',
                    ],
                    true,
                    false,
                    'element1',
                    '[[element1?property1=`value1`&property2=`value2`]]'
                ]
            ],
            [
                'element2',
                '[[element2? &property1=`value1` &property2=`value2`]]',
                '&property1=`value1` &property2=`value2`',
                "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                [
                    "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                    [
                        'property1' => 'value1',
                        'property2' => 'value2',
                    ],
                    true,
                    false,
                    'element2',
                    '[[element2?property1=`value1`&property2=`value2`]]'
                ]
            ],
        ];
    }
}
