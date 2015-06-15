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
 * Tests related to the modTag class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modTag
 */
class modTagTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        include dirname(__FILE__) . '/modtagelement.mock.php';
    }

    /**
     * Test the modTag->getProperties() method.
     *
     * @dataProvider providerGetProperties
     * @param string $name The name of the element.
     * @param string|array|null $properties The element properties value.
     * @param string|array|null $addProperties Additional properties passed to getProperties()
     * @param array $expected The expected array of properties.
     */
    public function testGetProperties($name, $properties, $addProperties, $expected) {
        /** @var modTagElement $element */
        $element = new modTagElement($this->modx);
        $element->set('name', $name);
        $element->setProperties($properties);
        $actual = $element->getProperties($addProperties);
        $this->assertEquals($expected, $actual, "Expected properties were not found.");
    }
    public function providerGetProperties() {
        return array(
            array(
                'element1',
                '',
                null,
                array()
            ),
            array(
                'element2',
                'food=beer&bard=muse',
                null,
                array(
                    'food' => 'beer',
                    'bard' => 'muse'
                )
            ),
            array(
                'element3',
                array(
                    'food' => 'beer',
                    'bard' => 'muse'
                ),
                null,
                array(
                    'food' => 'beer',
                    'bard' => 'muse'
                )
            ),
            array(
                'element4',
                array(
                    'food' => 'beer',
                ),
                array(
                    'bard' => 'muse'
                ),
                array(
                    'food' => 'beer',
                    'bard' => 'muse'
                )
            ),
        );
    }

    /**
     * Test the modTag->process() method.
     *
     * @dataProvider providerProcess
     * @param $properties
     * @param $content
     */
    public function testProcess($name, $tag, $properties, $content, $expected) {
        /** @var modTagElement $element */
        $element = new modTagElement($this->modx);
        $element->set('name', $name);
        $element->process($properties, $content);
        $result = array(
            $element->_content,
            $element->_properties,
            $element->_result,
            $element->_processed,
            $element->get('name'),
            $element->_tag,
        );
        $this->assertEquals($expected, $result, "Did not get expected results");
    }
    public function providerProcess() {
        return array(
            array(
                'element1',
                '[[element1]]',
                array(
                    'property1' => 'value1',
                    'property2' => 'value2',
                ),
                "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                array(
                    "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                    array(
                        'property1' => 'value1',
                        'property2' => 'value2',
                    ),
                    true,
                    false,
                    'element1',
                    '[[element1?property1=`value1`&property2=`value2`]]'
                )
            ),
            array(
                'element2',
                '[[element2? &property1=`value1` &property2=`value2`]]',
                '&property1=`value1` &property2=`value2`',
                "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                array(
                    "<p>This is some sample content with some tags: [[+notAPlaceholder]] [[!+notAnotherPlaceholder]]</p>",
                    array(
                        'property1' => 'value1',
                        'property2' => 'value2',
                    ),
                    true,
                    false,
                    'element2',
                    '[[element2?property1=`value1`&property2=`value2`]]'
                )
            ),
        );
    }
}
