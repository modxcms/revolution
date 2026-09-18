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

/** @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps */

namespace MODX\Revolution\Tests\Utilities\Sanitizers;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Utilities\Sanitizers\modStringSanitizer;

/**
 * Tests related to string sanitizing utilities
 *
 * @package modx-test
 * @subpackage modx
 * @group Utilities
 */
class modStringSanitizerTest extends MODxTestCase
{
    /** @var modStringSanitizer $sanitizers */
    public $sanitizers;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->sanitizers = $this->modx->services->get(modStringSanitizer::class);
    }

    /**
     * @param string $expected
     * @param string $htmlSource The html string to clean
     * @param ?string|array $allowedTags An array or comma-separated list of tag names to allow
     * @param ?string|array $allowedAttr An array or comma-separated list of tag attribute names to allow
     * @param bool $allowScripts Whether to allow javascript in html source passed to this method
     * @param bool $allowComments Whether to allow comments in the final output
     * @dataProvider providerStripHTML
     */
    public function testStripHTML(
        $expected,
        string $htmlSource,
        string|array|null $allowedTags = '',
        string|array|null $allowedAttr = '',
        bool $allowScripts = false,
        bool $allowComments = false
    ) {

        $allowedTags = $allowedTags ?? '';
        $allowedAttr = $allowedAttr ?? '';

        $result = $this->sanitizers->stripHTML($htmlSource, $allowedTags, $allowedAttr, $allowScripts, $allowComments);
        $this->assertEquals($expected, $result);

        // Make sure the placeholder wrapping tag is not present in the final output
        $this->assertDoesNotMatchRegularExpression('/<\/?phwrap>/', $result);
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
        // Allowed tags but no attributes
        $parmSet5 = ['p, a, strong, em'];

        // Have to hack this to keep parser from interpreting as actual opening short tag in the tests below
        $shortOpenTag = <<<TAG
        <? 
        TAG;

        return [
            // Single-use param specs
            'Should handle malformed html' => [
                'Still here',
                '<div wrong<span class="bad">Still here</div/em>',
                ...['span']
            ],
            // Full strip, nothing passed in for allowed params
            'White space only should result in empty string' => [
                '',
                '   '
            ],
            'Should remove all tags and attrs' => [
                'My great string',
                '<p class="gone">My <em>great</em> string</p>'
            ],
            'Should remove all tags and attrs (when null is passed to allowed params)' => [
                'My great string',
                '<p class="gone">My <em>great</em> string</p>',
                ...$nullParams
            ],
            'Should remove tag and html comment' => [
                'My great string',
                '<p>My <!-- comment -->great string</p>'
            ],
            'Should remove script tag and its contents' => [
                'This would , but we fixed it.',
                'This would <script>alert("be bad");</script>, but we fixed it.'
            ],
            'Should remove broken script tag and remaining contents (no closing)' => [
                'This would ',
                'This would <script>alert("be bad");, but we fixed it.'
            ],
            'Should remove broken script tag (no opening)' => [
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
                trim($shortOpenTag) . ' echo "Still not great!"; ?>'
            ],
            'Should remove php (short incomplete tag)' => [
                '',
                trim($shortOpenTag) . ' echo "You know ... not great!";'
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
            ],
            /*
                paramSet5 rules, allowed:
                    tags -- p, a, strong, em
                    attr -- none
            */
            'Should retain allowed tags but remove all attributes' => [
                '<p>A <a>jazzy</a> caption simplified</p>',
                '<p class="test">A <a href="here.com">jazzy</a> caption <span>simplified</span></p>',
                ...$parmSet5
            ]
        ];
    }
}
