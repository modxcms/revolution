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
 * Tests related to the main modX class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 */
class modXTest extends MODxTestCase {

    public function tearDown() {
        parent::tearDown();
        $this->modx->placeholders = array();
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
}
