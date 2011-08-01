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
 * Tests related to the modParser class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modParser
 */
class modParserTest extends MODxTestCase {
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
        $parser =& $this->modx->getParser();
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
        $parser =& $this->modx->getParser();
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
        );
    }
}
