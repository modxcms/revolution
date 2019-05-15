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
    public function setUp()
    {
        parent::setUp();

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
        $this->modx->resourceMap = array(
            0 => array(1, 2, 6),
            2 => array(3),
            3 => array(4),
            4 => array(5),
            6 => array(7),
            7 => array(8),
            8 => array(9),
            9 => array(10),
            10 => array(11),
            11 => array(12),
            12 => array(13),
            13 => array(14),
            14 => array(15),
            15 => array(16),
            16 => array(17),
            17 => array(18),
            18 => array(19)
        );

        $ctx = new stdClass();
        $ctx->resourceMap = array(
            21 => array(22),
            22 => array(23),
            23 => array(24),
            24 => array(25)
        );
        $this->modx->contexts['custom'] = $ctx;
    }



    public function tearDown() {
        parent::tearDown();
        $this->modx->placeholders = array();
        $this->modx->resourceMap = array(array(1));
        unset($this->modx->contexts['custom']);
    }
    /**
     * Test getting the modCacheManager instance.
     */
    public function testGetCacheManager() {
        $this->modx->getCacheManager();
        $this->assertInstanceOf('modCacheManager',$this->modx->cacheManager, "Failed to load a modCacheManager instance");
    }

    /**
     * @param string $expected
     * @param string $string
     * @param array $chars
     * @param string $allowedTags
     * @dataProvider providerSanitizeString
     */
    public function testSanitizeString($expected,$string,$chars = array('/',"'",'"','(',')',';','>','<'),$allowedTags = '') {
        if ($chars == null) $chars = array('/',"'",'"','(',')',';','>','<');
        if ($allowedTags == null) $allowedTags = '';

        $result = $this->modx->sanitizeString($string,$chars,$allowedTags);
        $this->assertEquals($expected,$result);
    }
    /**
     * @return array
     */
    public function providerSanitizeString() {
        return array(
            array('test','test'),
            array('Get this','Get (this)'),
        );
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
        return array(
            array(array('r' => 1),'r=1'),
            array(array('r' => 1,'s' => 2),'r=1&s=2'),
            array(array('r' => 1,'s' => 2,'t'),'r=1&s=2&0=t'),
            array(array('z' => 'Test space'),'z=Test+space'),
            array(array('a' => 'test/slash'),'a=test%2Fslash'),
        );
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
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * Test the getParser method
     */
    public function testGetParser() {
        $this->modx->getParser();
        $this->assertInstanceOf('modParser',$this->modx->parser, "Failed to load a modParser instance");
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
        return array(
            array('name', 'Joe'),
            array('testArray', array('one' => 1,'two' => 2)),
        );
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
        return array(
            array(array('one' => 1,'two' => 2),'two',2),
            array(array('one' => 1,'two' => 2),'test.two',2,'test.'),
        );
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
        return array(
            array(array('one' => 1,'two' => 2),'two',2),
            array(array('one' => 1,'two' => 2),'test.two',2,'test'),
            array(array('one' => 1,'two' => 2),'test-two',2,'test','-'),
        );
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
        return array(
            array('two',2,'two'),
            array('two',2,'test.two','test'),
            array('two',2,'test-two','test','-'),
        );
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
        return array(
            array('test','one'),
            array('one',array('two' => 2)),
            array('123',456),
        );
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
        return array(
            array('test','one'),
            array('one',array('two' => 2)),
            array(3,534),
        );
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
        return array(
            array(array('test' => 'testing'),array('test'),'test'),
            array(array('test' => 'testing','one' => 1),array('one'),'one'),
        );
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
        $tree = $this->modx->getTree($start, is_null($depth) ? 10 : $depth, $options ?: array());
        $this->assertEquals($result, $tree);
    }

    public function providerGetTree()
    {
        return array(
            array(0, 0, array(), array(1 => 1, 2 => 2, 6 => 6)),
            array(0, 1, array(), array(1 => 1, 2 => array(3 => 3), 6 => array(7 => 7))),
            array(7, 5, array(), array(8 => array(9 => array(10 => array(11 => array(12 => array(13 => 13))))))),
            array(6, null, array(), array(7 => array(8 => array(9 => array(10 => array(11 => array(12 => array(13 => array(14 => array(15 => array(16 => array(17 => 17)))))))))))),
            array(21, 3, array('context' => 'custom'), array(22 => array(23 => array(24 => array(25 => 25)))))
        );
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
        return array(
            array(0, 0, array(), array()),
            array(0, 1, array(), array(1, 2, 6)),
            array(6, 5, array(), array(7, 8, 9, 10, 11)),
            array(6, null, array(), array(7, 8, 9, 10, 11, 12, 13, 14, 15, 16)),
            array(22, 2, array('context' => 'custom'), array(23, 24))
        );
    }
}
