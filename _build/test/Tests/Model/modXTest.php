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

use MODX\Revolution\modCacheManager;
use MODX\Revolution\modParser;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use stdClass;

/**
 * Tests related to the main modX class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 */
class modXTest extends MODxTestCase
{
    /**
     * @before
     * @throws \xPDO\xPDOException
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        /*
         * This map following the next pattern:
         *  1 Mainpage
         *  2 Services
         *  └──3 Group of services
         *     └──4 Service
         *        └──5 SubService
         *  6 Catalog
         *  └──7 Category
         *     └──8 SubCategory
         *        └──9 SubCategory
         *           └──10 SubCategory
         *              └──11 SubCategory
         *                 └──12 SubCategory
         *                    └──13 SubCategory
         *                       └──14 SubCategory
         *                          └──15 SubCategory
         *                             └──16 SubCategory
         *                                └──17 SubCategory
         *                                   └──18 SubCategory
         *                                      └──19 SubCategory
         */
        $this->modx->resourceMap = [
            0 => [1, 2, 6],
            2 => [3],
            3 => [4],
            4 => [5],
            6 => [7],
            7 => [8],
            8 => [9],
            9 => [10],
            10 => [11],
            11 => [12],
            12 => [13],
            13 => [14],
            14 => [15],
            15 => [16],
            16 => [17],
            17 => [18],
            18 => [19]
        ];

        $ctx = new stdClass();
        $ctx->resourceMap = [
            21 => [22],
            22 => [23],
            23 => [24],
            24 => [25]
        ];
        $this->modx->contexts['custom'] = $ctx;
    }


    /**
     * Check the call to a single instance
     *
     * @after
     */
    public function testSingleInstance()
    {
        $this->modx->setOption('test_option', 'test');
        $this->assertTrue($this->modx->getOption('test_option') === modX::getInstance()->getOption('test_option'));
    }

    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
        $this->modx->placeholders = [];
        $this->modx->resourceMap = [[1]];
        unset($this->modx->contexts['custom']);
    }
    /**
     * Test getting the modCacheManager instance.
     */
    public function testGetCacheManager() {
        $this->modx->getCacheManager();
        $this->assertInstanceOf(modCacheManager::class,$this->modx->cacheManager, "Failed to load a modCacheManager instance");
    }

    /**
     * @param string $expected
     * @param string $string
     * @param array $chars
     * @param string $allowedTags
     * @dataProvider providerSanitizeString
     */
    public function testSanitizeString($expected,$string,$chars = ['/',"'",'"','(',')',';','>','<'],$allowedTags = '') {
        if ($chars == null) $chars = ['/',"'",'"','(',')',';','>','<'];
        if ($allowedTags == null) $allowedTags = '';

        $result = $this->modx->sanitizeString($string,$chars,$allowedTags);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerSanitizeString() {
        return [
            ['test','test'],
            ['Get this','Get (this)'],
        ];
    }

    /**
     * @param string $expected
     * @param mixed $cultureKey
     * @param string $default
     * @dataProvider providerSanitizeCultureKey
     */
    public function testSanitizeCultureKey($expected, $cultureKey, $default = 'en')
    {
        $this->assertEquals($expected, modX::sanitizeCultureKey($cultureKey, $default));
    }
    /**
     * @return array
     */
    public function providerSanitizeCultureKey()
    {
        return [
            /* legitimate language/locale keys are preserved */
            ['en', 'en'],
            ['de-DE', 'de-DE'],
            ['pt_BR', 'pt_BR'],
            ['en-US', 'en-US'],
            ['zh-CN', 'zh-CN'],
            /* tag injection, traversal, script and entity syntax fall back to the default */
            ['en', '[[!Register?&a=b]]'],
            ['en', 'en]]x'],
            ['en', '[[*pagetitle]]'],
            ['en', '../../etc/passwd'],
            ['en', '<script>'],
            ['en', 'a&#123;b'],
            ['en', '``'],
            ['en', ''],
            ['en', 'de DE'],
            ['en', null],
            ['en', ['en']],
            /* a caller-supplied default is honored on invalid input */
            ['fr', '[[bad]]', 'fr'],
        ];
    }

    /**
     * @param array $parameters
     * @param string $expected
     * @dataProvider providerToQueryString
     */
    public function testToQueryString(array $parameters,$expected) {
        $result = modX::toQueryString($parameters);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerToQueryString() {
        return [
            [['r' => 1],'r=1'],
            [['r' => 1,'s' => 2],'r=1&s=2'],
            [['r' => 1,'s' => 2,'t'],'r=1&s=2&0=t'],
            [['z' => 'Test space'],'z=Test+space'],
            [['a' => 'test/slash'],'a=test%2Fslash'],
        ];
    }

    /**
     * @param boolean $stopOnNotice
     * @dataProvider providerSetDebug
     */
    public function testSetDebug($stopOnNotice) {
        //$oldValue = $this->modx->setDebug(true,$stopOnNotice);
        $oldValue = $this->modx->getDebug();
        $this->modx->setDebug($stopOnNotice);
        $this->assertEquals($stopOnNotice, $this->modx->getDebug());
        //$this->assertEquals($stopOnNotice,$this->modx->stopOnNotice);
        $this->modx->setDebug($oldValue);
    }
    /**
     * @return array
     */
    public function providerSetDebug() {
        return [
            [true],
            [false],
        ];
    }

    /**
     * Test the getParser method
     */
    public function testGetParser() {
        $this->modx->getParser();
        $this->assertInstanceOf(modParser::class, $this->modx->parser, "Failed to load a modParser instance");
        $this->modx->parser = null;
    }

    /**
     * @param string $k
     * @param mixed $v
     * @dataProvider providerSetPlaceholder
     */
    public function testSetPlaceholder($k,$v) {
        $this->modx->setPlaceholder($k,$v);
        $this->assertEquals($v,$this->modx->placeholders[$k]);
    }
    /**
     * @return array
     */
    public function providerSetPlaceholder() {
        return [
            ['name', 'Joe'],
            ['testArray', ['one' => 1,'two' => 2]],
        ];
    }

    /**
     * @param array $placeholders
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @dataProvider providerSetPlaceholders
     */
    public function testSetPlaceholders(array $placeholders,$key,$value,$namespace = '') {
        $this->modx->setPlaceholders($placeholders,$namespace);
        $this->assertEquals($value,$this->modx->placeholders[$key]);
    }
    /**
     * @return array
     */
    public function providerSetPlaceholders() {
        return [
            [['one' => 1,'two' => 2],'two',2],
            [['one' => 1,'two' => 2],'test.two',2,'test.'],
        ];
    }

    /**
     * @param $placeholders
     * @param $key
     * @param $value
     * @param string $prefix
     * @param string $separator
     * @param bool $restore
     * @dataProvider providerToPlaceholders
     */
    public function testToPlaceholders($placeholders,$key,$value,$prefix = '',$separator = '.',$restore = false) {
        $this->modx->toPlaceholders($placeholders,$prefix,$separator,$restore);
        $this->assertEquals($value,$this->modx->placeholders[$key]);
    }
    /**
     * @return array
     */
    public function providerToPlaceholders() {
        return [
            [['one' => 1,'two' => 2],'two',2],
            [['one' => 1,'two' => 2],'test.two',2,'test'],
            [['one' => 1,'two' => 2],'test-two',2,'test','-'],
        ];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $expectedKey
     * @param string $prefix
     * @param string $separator
     * @param bool $restore
     * @dataProvider providerToPlaceholder
     */
    public function testToPlaceholder($key,$value,$expectedKey,$prefix = '',$separator = '.',$restore = false) {
        $this->modx->toPlaceholder($key,$value,$prefix,$separator,$restore);
        $this->assertEquals($value,$this->modx->placeholders[$expectedKey]);
    }
    /**
     * @return array
     */
    public function providerToPlaceholder() {
        return [
            ['two',2,'two'],
            ['two',2,'test.two','test'],
            ['two',2,'test-two','test','-'],
        ];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider providerGetPlaceholder
     */
    public function testGetPlaceholder($key,$value) {
        $this->modx->setPlaceholder($key,$value);
        $result = $this->modx->getPlaceholder($key);
        $this->assertEquals($value,$result);
    }
    /**
     * @return array
     */
    public function providerGetPlaceholder() {
        return [
            ['test','one'],
            ['one', ['two' => 2]],
            ['123',456],
        ];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider providerUnsetPlaceholder
     */
    public function testUnsetPlaceholder($key,$value) {
        $this->modx->setPlaceholder($key,$value);
        $this->modx->unsetPlaceholder($key);
        $this->assertArrayNotHasKey($key,$this->modx->placeholders);
    }
    /**
     * @return array
     */
    public function providerUnsetPlaceholder() {
        return [
            ['test','one'],
            ['one', ['two' => 2]],
            [3,534],
        ];
    }

    /**
     * @param array $placeholders
     * @param array $placeholdersToUnset
     * @param string $keyToCheck
     * @dataProvider providerUnsetPlaceholders
     */
    public function testUnsetPlaceholders(array $placeholders,array $placeholdersToUnset,$keyToCheck) {
        $this->modx->setPlaceholders($placeholders);
        $this->modx->unsetPlaceholders($placeholdersToUnset);
        $this->assertArrayNotHasKey($keyToCheck,$this->modx->placeholders);
    }
    /**
     * @return array
     */
    public function providerUnsetPlaceholders() {
        return [
            [['test' => 'testing'], ['test'],'test'],
            [['test' => 'testing','one' => 1], ['one'],'one'],
        ];
    }

    /**
     * @param null $start
     * @param int $depth
     * @param array $options
     * @param array $result
     * @dataProvider providerGetTree
     */
    public function testGetTree($start, $depth, array $options, array $result)
    {
        $tree = $this->modx->getTree($start, is_null($depth) ? 10 : $depth, $options ?: []);
        $this->assertEquals($result, $tree);
    }

    public function providerGetTree()
    {
        return [
            [0, 0, [], [1 => 1, 2 => 2, 6 => 6]],
            [0, 1, [], [1 => 1, 2 => [3 => 3], 6 => [7 => 7]]],
            [7, 5, [], [8 => [9 => [10 => [11 => [12 => [13 => 13]]]]]]],
            [6, null, [], [7 => [8 => [9 => [10 => [11 => [12 => [13 => [14 => [15 => [16 => [17 => 17]]]]]]]]]]]],
            [21, 3, ['context' => 'custom'], [22 => [23 => [24 => [25 => 25]]]]]
        ];
    }

    /**
     * @param $start
     * @param $depth
     * @param array $options
     * @param array $result
     * @dataProvider providerGetChildIds
     */
    public function testGetChildIds($start, $depth, array $options, array $result)
    {
        $ids = $this->modx->getChildIds($start, is_null($depth) ? 10 : $depth, $options ?: $options);
        $this->assertEquals($ids, $result);
    }

    public function providerGetChildIds()
    {
        return [
            [0, 0, [], []],
            [0, 1, [], [1, 2, 6]],
            [6, 5, [], [7, 8, 9, 10, 11]],
            [6, null, [], [7, 8, 9, 10, 11, 12, 13, 14, 15, 16]],
            [22, 2, ['context' => 'custom'], [23, 24]]
        ];
    }

    /**
     * @param string $expected
     * @param string $htmlSource The html string to clean
     * @param ?string|array $allowedTags An array or comma-separated list of tag names to allow
     * @param ?string|array $allowedAttr An array or comma-separated list of tag attribute names to allow
     * @param bool $allowScripts Whether to allow javascript in html source passed to this method
     * @dataProvider providerStripHTML
     */
    public function testStripHTML($expected, string $htmlSource, $allowedTags = '', $allowedAttr = '', $allowScripts = false)
    {
        $allowedTags = $allowedTags ?? '';
        $allowedAttr = $allowedAttr ?? '';

        $result = $this->modx->stripHTML($htmlSource, $allowedTags, $allowedAttr, $allowScripts);
        $this->assertEquals($expected, $result);
    }

    public function providerStripHTML(): array
    {
        $nullParams = [null, null];
        // String list configs (including odd spacing)
        $parmSet1 = ['a, strong  , em', 'href,  title,id'];
        // Array list configs (including odd spacing)
        $parmSet2 = [['p', 'a', ' strong', 'em'], ['href  ', 'class', 'style', 'onclick', 'title']];
        // Custom/non-existing
        $parmSet3 = ['p, notatag', 'notanattr'];
        // Data attr and allowing scripts
        $parmSet4 = ['div,img, script', 'data, src', true];

        return [
            // Full strip, nothing passed in for allowed params
            'Should remove all tags and attrs' => [
                'My great string',
                '<p class="gone">My <em>great</em> string</p>'
            ],
            'Should remove all tags and attrs (when null is passed to allowed params)' => [
                'My great string',
                '<p class="gone">My <em>great</em> string</p>',
                ...$nullParams
            ],
            'Should remove script tags' => [
                'This would alert("be bad");',
                'This would <script>alert("be bad");</script>'
            ],
            'Should remove broken script tags (no closing)' => [
                'This would alert("be bad");',
                'This would <script>alert("be bad");'
            ],
            'Should remove broken script tags (no opening)' => [
                'This would alert("be bad");',
                'This would alert("be bad");</script>'
            ],
            'Should remove php (long tag)' => [
                '',
                '<?php echo "Also not great!"; ?>'
            ],
            'Should remove php (long incomplete tag)' => [
                '',
                '<?php echo "Again, not great!";'
            ],
            'Should remove php (short tag)' => [
                '',
                '<\? echo "Still not great!"; ?>'
            ],
            'Should remove php (short incomplete tag)' => [
                '',
                '<\? echo "You know ... not great!";'
            ],
            /*
                paramSet1 rules, allowed:
                    tags -- a, strong, em
                    attr -- href, title, id
            */
            'Should auto complete broken em (incorrect closing tags)' => [
                'A <em>jazzy<em> caption</em></em>',
                'A <em>jazzy<em> caption',
                ...$parmSet1
            ],
            'Should handle removals in nested structures' => [
                'A <em>jazzy</em> caption <a id="myId">more</a>',
                'A <b><em><span>jazzy</span></em></b> caption <span><a id="myId" style="color: red;"><b>more</b></a></span>',
                ...$parmSet1
            ],
            /*
                paramSet2 rules, allowed, given in array instead of string:
                    tags -- ['p', 'a', 'strong', 'em']
                    attr -- ['href', 'class', 'style', 'onclick', 'title'] {1}

                    {1} note that event handlers should always be removed, even when scripts
                    are allowed as it is bad practice mixing javascript directly in html
            */
            'Should remove non-standard tag and others not in list' => [
                '<p>A jazzy caption</p>',
                '<div><p>A <notatag>jazzy</notatag> caption</p></div>',
                ...$parmSet2
            ],
            'Should remove event handlers' => [
                '<p class="myClass">This element does <strong>all</strong> these things</p>',
                '<p class="myClass" data-someprop="hello">This <b>element</b> does <strong onclick="javascript:alert(hello);">all</strong> these things</p>',
                ...$parmSet2
            ],
            'Should replace javascript in all attrs' => [
                '<p class="myClass">This element does <strong title="#js-not-allowed#">all</strong> these things</p>',
                '<p class="myClass" data-someprop="hello">This <b>element</b> does <strong title="javascript:alert(hello);">all</strong> these things</p>',
                ...$parmSet2
            ],
            /*
                paramSet3 rules, allowed:
                    tags -- p, notatag
                    attr -- notanattr
            */
            'Should retain non-standard and other allowed tags' => [
                '<p>A <notatag>jazzy</notatag> caption</p>',
                '<div><p>A <notatag>jazzy</notatag> caption</p></div>',
                ...$parmSet3
            ],
            'Should retain non-standard and other allowed attributes' => [
                '<p notanattr="technically ok, but not advisable">A <notatag>jazzy</notatag> caption</p>',
                '<div><p notanattr="technically ok, but not advisable">A <notatag>jazzy</notatag> caption</p></div>',
                ...$parmSet3
            ],
            /*
                paramSet4 rules, allowed:
                    tags -- div, img, script
                    attr -- data, src
            */
            'Should retain data attributes' => [
                '<div data-someprop="hello" data-otherprop="world">A jazzy caption</div>',
                '<div data-someprop="hello" data-otherprop="world"><p>A <notatag>jazzy</notatag> caption</p></div>',
                ...$parmSet4
            ],
            'Should retain script tag and contents' => [
                '<div>As long as you are sure, </div><script>alert("you can do this");</script>',
                '<div>As long as you are sure, </div><script>alert("you can do this");</script>',
                ...$parmSet4
            ],
            'Should handle retention and removals in multiline html' => [
                <<<EXP
                    <div>
                        These tags will disappear
                        <img src="/some/path/to.png">
                    </div>
                    <script>
                        let x = 1;
                        console.log('X is ', x);
                    </script>
                EXP,
                <<<SRC
                    <div>
                        <p>These tags will <span>disappear</span></p>
                        <img src="/some/path/to.png" alt="should have this but not in list">
                    </div>
                    <script>
                        let x = 1;
                        console.log('X is ', x);
                    </script>
                SRC,
                ...$parmSet4
            ]
        ];
    }
}
