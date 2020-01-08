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
namespace MODX\Revolution\Tests\Model;


use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\MODxTestHarness;

/**
 * Tests related to the modParser class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modParser
 */
class modParserTest extends MODxTestCase {
    public static $scope = [];

    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::getFixture(modX::class, 'modx', true);
        $placeholders = ['tag' => 'Tag', 'tag1' => 'Tag1', 'tag2' => 'Tag2'];
        self::$scope = $modx->toPlaceholders($placeholders, '', '.', true);
    }

    public static function tearDownAfterClass() {
        if (!empty(self::$scope)) {
            $modx = MODxTestHarness::getFixture(modX::class, 'modx');
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
        $matches = [];
        $tagCount = $this->modx->parser->collectElementTags($input, $matches, $prefix, $suffix);
        $this->assertEquals($expectedMatches, $matches, "Did not collect expected tags.");
        $this->assertEquals($expectedCount, $tagCount, "Did not collect expected number of tags.");
    }
    /**
     * dataProvider for testCollectElementTags
     */
    public function providerCollectElementTags() {
        return [
            ["", '[[', ']]', [], 0],
            ["[[tag]]", '[[', ']]', [["[[tag]]", "tag"]], 1],
            ["<title>[[*pagetitle]] - [[++site_name]]</title><body>[something]</body>", '[[', ']]', [["[[*pagetitle]]", "*pagetitle"], ["[[++site_name]]", "++site_name"]], 2],
            ["[[[[*field:is=`0`:then=`Tag`:else=`Tag`]]]]", '[[', ']]', [["[[[[*field:is=`0`:then=`Tag`:else=`Tag`]]]]", "[[*field:is=`0`:then=`Tag`:else=`Tag`]]"]], 1],
            [
                "[[!+fi.successMessage:empty=`
    test[]
	[[~[[*id]]]]
`]]", '[[', ']]', [
                [
                    "[[!+fi.successMessage:empty=`
    test[]
	[[~[[*id]]]]
`]]", "!+fi.successMessage:empty=`
    test[]
	[[~[[*id]]]]
`"
                ]
            ], 1
            ],
            ["items[[[tag]]]", '[[', ']]', [["[[tag]]", "tag"]], 1],
            ["[[tag]][[tag2]]", '[[', ']]', [["[[tag]]", "tag"], ["[[tag2]]", "tag2"]], 2],
            ["[[tag[[tag2]]]]", '[[', ']]', [["[[tag[[tag2]]]]", "tag[[tag2]]"]], 1],
            ["[[tag[[tag2[[tag3]]]]]]", '[[', ']]', [["[[tag[[tag2[[tag3]]]]]]", "tag[[tag2[[tag3]]]]"]], 1],
            ["[[tag\n?food=`beer`\n[[tag2]]]]", '[[', ']]', [["[[tag\n?food=`beer`\n[[tag2]]]]", "tag\n?food=`beer`\n[[tag2]]"]], 1],
            ["\n[[ tag? &food=`beer` [[tag2]][[tag3]]]]", '[[', ']]', [["[[ tag? &food=`beer` [[tag2]][[tag3]]]]", " tag? &food=`beer` [[tag2]][[tag3]]"]], 1],
            /*array("\n[[ tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]]]", '[[', ']]', array(array("[[ tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]]]", " tag? <![CDATA[Some CDATA content]]> &food=`beer` [[tag2]][[tag3]]")), 1),*/
        ];
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
        $processed = $this->modx->parser->processElementTags(
            $params['parentTag'],
            $content,
            $params['processUncacheable'],
            $params['removeUnprocessed'],
            $params['prefix'],
            $params['suffix'],
            $params['tokens'],
            $params['depth']
        );
        $actual = [
            'processed' => $processed,
            'content' => $content
        ];
        $this->assertEquals($expected, $actual, "Did not get expected results from tag parsing.");
    }
    /**
     * dataProvider for testProcessElementTags.
     */
    public function providerProcessElementTags() {
        return [
            [
                [
                    'processed' => 0,
                    'content' => ""
                ],
                "",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe]]"
                ],
                "[[!doNotCacheMe]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]"
                ],
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe]]"
                ],
                "[[!doNotCacheMe]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 1,
                    'content' => ""
                ],
                "[[!doNotCacheMe]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 0,
                    'content' => "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]"
                ],
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 1,
                    'content' => ""
                ],
                "[[!doNotCacheMe? &but=`[[youCanCacheMe]]`]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 1,
                    'content' => "[[!doNotCacheMe? &but=`Tag`]]"
                ],
                "[[!doNotCacheMe? &but=`[[+tag]]`]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 2,
                    'content' => "[[!doNotCacheMe? &but=`Tag`]]Tag2"
                ],
                "[[!doNotCacheMe? &but=`[[+tag]]`]][[+tag2]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 2,
                    'content' => "[[!+tag1? &but=`Tag`]]Tag2[[!+tag1]]"
                ],
                "[[!+tag1? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => false,
                    'removeUnprocessed' => true,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 3,
                    'content' => "Tag1Tag2Tag1"
                ],
                "[[!+tag1? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 3,
                    'content' => "[[+notATag? &but=`Tag`]]Tag2Tag1"
                ],
                "[[+notATag? &but=`[[+tag]]`]][[+tag2]][[!+tag1]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
            [
                [
                    'processed' => 3,
                    'content' => "[[+notATag? &but=`Tag2`]]Tag2Tag1"
                ],
                "[[+notATag? &but=`[[+tag2]]`]][[+tag2]][[!+tag1]]",
                [
                    'parentTag' => '',
                    'processUncacheable' => true,
                    'removeUnprocessed' => false,
                    'prefix' => '[[',
                    'suffix' => ']]',
                    'tokens' => [],
                    'depth' => 0
                ]
            ],
        ];
    }

    /**
     * Test modParser->parsePropertyString()
     *
     * @dataProvider providerParsePropertyString
     *
     * @param array $expected
     * @param string $string
     * @param boolean $valuesOnly
     */
    public function testParsePropertyString($expected, $string, $valuesOnly) {
        $actual = $this->modx->parser->parsePropertyString($string, $valuesOnly);
        $this->assertEquals($expected, $actual, "Property string not parsed properly");
    }
    public function providerParsePropertyString() {
        return [
            [
                [],
                "",
                false
            ],
            [
                [
                    'property' => [
                        'name' => 'property',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => [],
                        'value' => 'value'
                    ]
                ],
                "&property=value",
                false
            ],
            [
                [
                    'property' => 'value'
                ],
                "&property=value",
                true
            ],
            [
                [
                    'property' => 'value'
                ],
                " &property=value ",
                true
            ],
            [
                [
                    'property' => 'value',
                    'property2' => 'value2',
                ],
                " &property=value &property2=value2 ",
                true
            ],
            [
                [
                    'property' => [
                        'name' => 'property',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => [],
                        'value' => 'value'
                    ],
                    'property2' => [
                        'name' => 'property2',
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => [],
                        'value' => 'value2'
                    ],
                ],
                " &property=value &property2=value2 ",
                false
            ],
            [
                [
                    'property' => 'value&value=value',
                ],
                "property=`value&value=value`",
                true
            ],
            [
                [
                    'property' => 'value? &value=`value` ',
                ],
                "property=`value? &value=`value` `",
                true
            ],
            [
                [
                    'property' => 'value? &value=`value`',
                ],
                "property=`value? &value=``value```",
                true
            ],
            [
                [
                    'property' => 'value with ` nested backticks',
                ],
                " &property=`value with `` nested backticks`",
                true
            ],
            [
                [
                    'property' => 'value with nested backticks`',
                ],
                " &property=`value with nested backticks```",
                true
            ],
        ];
    }

    /**
     * Test modParser->realname().
     *
     * @dataProvider providerTestRealname
     * @param string $expected The expected filtered tag name.
     * @param string $string The tag name to filter.
     */
    public function testRealname($expected, $string) {
        $actual = $this->modx->parser->realname($string);
        $this->assertEquals($expected, $actual, "Could not generate proper realname from unfiltered element tag name");
    }
    /**
     * dataProvider for testRealname.
     */
    public function providerTestRealname() {
        return [
            ["", ""],
            ["name", "name"],
            ["name", "name:"],
            ["name", "name:filter"],
            ["name", "name@propset:filter"],
            ["name", "name@propset:filter=`name@propset:filter`"],
        ];
    }

    public function testDefaultNonExistingTvValue() {
        $output = "[[*foo:default=`bar`]]";
        $this->modx->parser->processElementTags('', $output, true, false, '[[', ']]', [], 10);
        $this->assertEquals($output, "bar", "Did not parse non-existing TV with default modifier correctly");

    }
}
