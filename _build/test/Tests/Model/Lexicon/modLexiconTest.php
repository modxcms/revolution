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
 * Tests related to the modMail class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Lexicon
 * @group modLexicon
 */
class modLexiconTest extends MODxTestCase {
    /** @var modLexicon $lexicon */
    public $lexicon;

    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('modLexicon',null,true,true);
        $this->lexicon = new modLexicon($this->modx);
    }

    public function tearDown() {
        $this->lexicon->clear();
    }

    /**
     * Ensure total() returns an accurate count
     * @param string $topic
     * @param int $total
     * @dataProvider providerTotal
     */
    public function testTotal($topic,$total) {
        $this->assertEquals(0,$this->lexicon->total());
        $this->lexicon->load($topic);
        $this->assertEquals($total,$this->lexicon->total());
    }
    /**
     * @return array
     */
    public function providerTotal() {
        require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/core/lexicon/en/about.inc.php';
        $total = count($_lang);
        return array(
            array('about', $total),
        );
    }

    /**
     * Tests the load method with various use cases
     * @param string $key
     * @depends testTotal
     * @dataProvider providerLoad
     */
    public function testLoad($key) {
        $this->lexicon->load($key);
        $lang = '';
        $args = explode(':', $key);
        if (count($args) === 3) {
            $lang = $args[0];
        }

        $this->assertGreaterThan(0,$this->lexicon->total($lang));
    }
    /**
     * @return array
     */
    public function providerLoad() {
        return array(
            array('user'),
            array('context'),
            array('core:element'),
            array('en:core:action'),
            array('fr:core:action'),
        );
    }

    /**
     * Ensure the clear method clears the lexicon
     * @depends testLoad
     */
    public function testClear() {
        $this->lexicon->load('user');
        $this->assertGreaterThan(0,$this->lexicon->total());
        $this->lexicon->clear();
        $this->assertEquals(0,$this->lexicon->total());
    }

    /**
     * @param string $expected
     * @param string $namespace
     * @param string $topic
     * @param string $language
     * @dataProvider providerGetCacheKey
     */
    public function testGetCacheKey($expected,$namespace,$topic,$language) {
        $key = $this->lexicon->getCacheKey($namespace,$topic,$language);
        $this->assertEquals($expected,$key);
    }
    /**
     * @return array
     */
    public function providerGetCacheKey() {
        return array(
            array('lexicon/en/core/user','core','user','en'),
            array('lexicon/en/core/about','core','about','en'),
            array('lexicon/fr/core/user','core','user','fr'),
            array('lexicon/fr/core/about','core','about','fr'),
            array('lexicon/en/formit/default','formit','default','en'),
            array('lexicon/de/formit/default','formit','default','de'),
        );
    }

    /**
     * @param string $language
     * @param string $namespace
     * @param string $topic
     * @dataProvider providerGetFileTopic
     */
    public function testGetFileTopic($language,$namespace,$topic) {
        $results = $this->lexicon->getFileTopic($language,$namespace,$topic);
        $this->assertNotEmpty($results);
    }
    /**
     * @return array
     */
    public function providerGetFileTopic() {
        return array(
            array('en','core','default'),
            array('fr','core','default'),
            array('en','core','action'),
        );
    }

    /**
     * @param string $namespace
     * @param string $expected
     * @dataProvider providerGetNamespacePath
     */
    public function testGetNamespacePath($namespace,$expected) {
        $path = $this->lexicon->getNamespacePath($namespace);
        $path = str_replace(MODX_CORE_PATH,'',$path);
        $this->assertEquals($expected,$path);
    }
    /**
     * @return array
     */
    public function providerGetNamespacePath() {
        return array(
            array('core',''),
        );
    }

    /**
     * @param string $language
     * @param string $namespace
     * @depends testGetNamespacePath
     * @dataProvider providerGetTopicList
     */
    public function testGetTopicList($language,$namespace) {
        $results = $this->lexicon->getTopicList($language,$namespace);
        $this->assertNotEmpty($results);
    }
    /**
     * @return array
     */
    public function providerGetTopicList() {
        return array(
            array('en','core'),
            array('fr','core'),
            array('de','core'),
        );
    }

    /**
     * @param string $namespace
     * @depends testGetNamespacePath
     * @dataProvider providerGetLanguageList
     */
    public function testGetLanguageList($namespace) {
        $results = $this->lexicon->getLanguageList($namespace);
        $this->assertNotEmpty($results);
    }
    /**
     * @return array
     */
    public function providerGetLanguageList() {
        return array(
            array('core'),
        );
    }

    /**
     * @param string $topic
     * @param string $key
     * @param array $properties
     * @param string $expected
     * @dataProvider providerProcess
     * @depends testLoad
     */
    public function testProcess($topic,$key,$properties,$expected) {
        $this->lexicon->load($topic);
        $translation = $this->lexicon->process($key,$properties);
        $this->assertEquals($expected,$translation);
    }
    /**
     * @return array
     */
    public function providerProcess() {
        return array(
            array('chunk','chunk',array(),'Chunk'),
            array('chunk','chunks',array(),'Chunks'),
            array('chunk','chunk_err_nfs',array('id' => 1),'Chunk not found with id: 1'),
            array('chunk','chunk_err_nfs',array('id' => 123),'Chunk not found with id: 123'),
            array('chunk','chunk_err_nfs',array('id' => 'potatoes'),'Chunk not found with id: potatoes'),
        );
    }

    /**
     * @param string $topic
     * @param string $key
     * @param boolean $expected
     * @dataProvider providerExists
     * @depends testLoad
     */
    public function testExists($topic,$key,$expected = true) {
        $this->lexicon->load($topic);
        $exists = $this->lexicon->exists($key);
        $this->assertEquals($expected,$exists);
    }
    /**
     * @return array
     */
    public function providerExists() {
        return array(
            array('chunk','chunk_err_nf',true),
            array('chunk','chunks',true),
            array('chunk','potatoes',false),
            array('respect','for_programmers',false),
        );
    }

    /**
     * Test modLexicon.fetch and ensure prefixing features work
     *
     * @param string $topic The topic to load
     * @param string $key The key to check for in the fetched lexicon
     * @param string $filterPrefix If not empty, will filter results to only entries with this prefix
     * @param boolean $removePrefix If true, will remove the filterPrefix from the returned keys
     * @dataProvider providerFetch
     * @depends testLoad
     */
    public function testFetch($topic,$key,$filterPrefix = '',$removePrefix = false) {
        $this->lexicon->load($topic);
        $lang = $this->lexicon->fetch($filterPrefix,$removePrefix);
        $this->assertArrayHasKey($key,$lang);
    }
    /**
     * @return array
     */
    public function providerFetch() {
        return array(
            array('about','help_about'),
            array('chunk','chunks'),
            array('element','tv_elements','tv_'),
            array('element','elements','tv_',true),
        );
    }
}
