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
 * Tests related to the modParser class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modParser
 */
class modParserTest extends MODxTestCase {
    public static $scope = array();

    public static function setUpBeforeClass() {
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $placeholders = array('tag' => 'Tag', 'tag1' => 'Tag1', 'tag2' => 'Tag2');
        self::$scope = $modx->toPlaceholders($placeholders, '', '.', true);
    }

    public static function tearDownAfterClass() {
        if (!empty(self::$scope)) {
            $modx =& MODxTestHarness::getFixture('modX', 'modx');
            if (array_key_exists('keys', self::$scope)) {
                $modx->unsetPlaceholder(self::$scope['keys']);
            }
            if (array_key_exists('restore', self::$scope)) {
                $modx->toPlaceholders(self::$scope['restore']);
            }
        }
    }

    /**
     * Test modParser->collectElementTags()
     *
     * @dataProvider providerCollectElementTags
     * @param $input
     * @param $expected
     */
    public function testCollectElementTags($input, $prefix, $suffix, $expectedMatches, $expectedCount) {
        $matches = array();
        /** @var modParser $parser */
        $parser = $this->modx->getParser();
        $tagCount = $parser->collectElementTags($input, $matches, $prefix, $suffix);
        $this->assertEquals($expectedMatches, $matches, "Did not collect expected tags.");
        $this->assertEquals($expectedCount, $tagCount, "Did not collect expected number of tags.");
    }
    /**
     * dataProvider for testCollectElementTags
     */
    public function providerCollectElementTags() {
        return array(
            array("", '[[', ']]', array(), 0),
            array("[[tag]]", '[[', ']]', array(array("[[tag]]", "tag")), 1),
            array("[[tag]][[tag2]]", '[[', ']]', array(array("[[tag]]", "tag"), array("[[tag2]]", "tag2")), 2),
            array("[[tag[[tag2]]]]", '[[', ']]', array(array("[[tag[[tag2]]]]", "tag[[tag2]]")), 1),
            array("[[tag[[tag2[[tag3]]]]]]", '[[', ']]', array(array("[[tag[[tag2[[tag3]]]]]]", "tag[[tag2[[tag3]]]]")), 1),
            array("[[tag\n?food=`beer`\n[[tag2]]]]", '[[', ']]', array(array("[[tag\n?food=`beer`\n[[tag2]]]]", "tag\n?food=`beer`\n[[tag2]]")), 1),
            array("\n[[ tag? &food=`beer` [[tag2]][[tag3]]]]", '[[', ']]', array(array("[[ tag? &food=`beer` [[tag2]][[tag3]]]]", " tag? &food=`beer` [[tag2]][[tag3]]")), 1),
            /*array("\n[[ tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]]]", '[[', ']]', array(array("[[ tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]]]", " tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]")), 1),*/
        );
    }

    /**
     * Test modParser->processElementTags().
     *
     * @dataProvider providerProcessElementTags
     * @param array $expected An array with expected processed tag count and content.
     * @param string $content The content to process tags in.
     * @param array $params An array of parameters for the processElementTags() method.
     */
    public function testProcessElementTags($expected, $content, $params) {
        /** @var modParser $parser */
        $parser = $this->modx->getParser();
        $processed = $parser->processElementTags(
            $params['parentTag'],
            $content,
            $params['processUncacheable'],
            $params['removeUnprocessed'],
            $params['prefix'],
            $params['suffix'],
            $params['tokens'],
            $params['depth']
        );
        $actual = array(
            'processed' => $processed,
            'content' => $content
        );
        $this->assertEquals($expected, $actual, "Did not get expected results from tag parsing.");
    }
    /**
     * dataProvider for testProcessElementTags.
     */
    public function providerProcessElementTags() {
        return array(
            array(
                array(
                    'processed' => 0,
                    'content' => ""
                ),
                "",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe]]"
                ),
                "[[!doNotCacheMe]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]"
                ),
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe]]"
                ),
                "[[!doNotCacheMe]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 1,
                    'content' => ""
                ),
                "[[!doNotCacheMe]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]"
                ),
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 1,
                    'content' => ""
                ),
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 1,
                    'content' => "[[!doNotCacheMe? &but=`Tag`]]"
                ),
                "[[!doNotCacheMe? &but=`[[+tag]]`]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 2,
                    'content' => "[[!doNotCacheMe? &but=`Tag`]]Tag2"
                ),
                "[[!doNotCacheMe? &but=`[[+tag]]`]][[+tag2]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 2,
                    'content' => "[[!+tag1? &but=`Tag`]]Tag2[[!+tag1]]"
                ),
                "[[!+tag1? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 3,
                    'content' => "Tag1Tag2Tag1"
                ),
                "[[!+tag1? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 3,
                    'content' => "[[+notATag? &but=`Tag`]]Tag2Tag1"
                ),
                "[[+notATag? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
            array(
                array(
                    'processed' => 3,
                    'content' => "[[+notATag? &but=`Tag2`]]Tag2Tag1"
                ),
                "[[+notATag? &but=`[[+tag2]]`]][[+tag2]][[!+tag1]]",
                array(
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => array(),
                    'depth' => 0
                )
            ),
        );
    }

    /**
     * Test modParser->parsePropertyString()
     *
     * @dataProvider providerParsePropertyString
     * @param $expected
     * @param $string
     */
    public function testParsePropertyString($expected, $string, $valuesOnly) {
        $this->modx->getParser();
        $actual = $this->modx->parser->parsePropertyString($string, $valuesOnly);
        $this->assertEquals($expected, $actual, "Property string not parsed properly");
    }
    public function providerParsePropertyString() {
        return array(
            array(
                array(),
                "",
                false
            ),
            array(
                array(
                    'property' => array(
                        'name' => 'property',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => 'value'
                    )
                ),
                "&property=value",
                false
            ),
            array(
                array(
                    'property' => 'value'
                ),
                "&property=value",
                true
            ),
            array(
                array(
                    'property' => 'value'
                ),
                " &property=value ",
                true
            ),
            array(
                array(
                    'property' => 'value',
                    'property2' => 'value2',
                ),
                " &property=value &property2=value2 ",
                true
            ),
            array(
                array(
                    'property' => array(
                        'name' => 'property',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => 'value'
                    ),
                    'property2' => array(
                        'name' => 'property2',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(),
                        'value' => 'value2'
                    ),
                ),
                " &property=value &property2=value2 ",
                false
            ),
            array(
                array(
                    'property' => 'value&value=value',
                ),
                "property=`value&value=value`",
                true
            ),
            array(
                array(
                    'property' => 'value? &value=`value` ',
                ),
                "property=`value? &value=`value` `",
                true
            ),
            array(
                array(
                    'property' => 'value? &value=`value`',
                ),
                "property=`value? &value=``value```",
                true
            ),
            array(
                array(
                    'property' => 'value with ` nested backticks',
                ),
                " &property=`value with `` nested backticks`",
                true
            ),
            array(
                array(
                    'property' => 'value with nested backticks`',
                ),
                " &property=`value with nested backticks```",
                true
            ),
        );
    }

    /**
     * Test modParser->realname().
     *
     * @dataProvider providerTestRealname
     * @param string $expected The expected filtered tag name.
     * @param string $string The tag name to filter.
     */
    public function testRealname($expected, $string) {
        $this->modx->getParser();
        $actual = $this->modx->parser->realname($string);
        $this->assertEquals($expected, $actual, "Could not generate proper realname from unfiltered element tag name");
    }
    /**
     * dataProvider for testRealname.
     */
    public function providerTestRealname() {
        return array(
            array("", ""),
            array("name", "name"),
            array("name", "name:"),
            array("name", "name:filter"),
            array("name", "name@propset:filter"),
            array("name", "name@propset:filter=`name@propset:filter`"),
        );
    }
}
