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
 * Tests related to the modOutputFilter class, including testing of core output filters.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Filters
 * @group modOutputFilter
 */
class modOutputFilterTest extends MODxTestCase {
    /** @var modPlaceholderTag $tag */
    public $tag;

    public function setUp() {
        parent::setUp();
        $this->modx->getParser();
        $this->tag = new modPlaceholderTag($this->modx);
        $this->tag->setCacheable(false);
        $this->tag->set('name','utp');
    }

    /**
     * Test :cssToHead filter that adds CSS to HEAD of a page
     * 
     * @param string $value
     * @param boolean $addTag
     * @dataProvider providerCssToHead
     */
    public function testCssToHead($value,$addTag) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:cssToHead');
        $this->tag->process();
        if ($addTag) {
            $value = '<link rel="stylesheet" href="'.$value.'" type="text/css" />';
        }
        $this->assertContains($value,$this->modx->sjscripts);
        unset($this->modx->sjscripts[$value]);
    }
    /**
     * @return array
     */
    public function providerCssToHead() {
        return array(
            array('assets/css/style.css',true),
            array('<link rel="stylesheet" href="assets/css/style.css" type="text/css" />',false),
        );
    }

    /**
     * Test :htmlToHead filter that adds HTML to the HEAD of a page
     * 
     * @param string $value
     * @dataProvider providerHtmlToHead
     */
    public function testHtmlToHead($value) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:htmlToHead');
        $this->tag->process();
        $this->assertContains($value,$this->modx->sjscripts);
        unset($this->modx->sjscripts[$value]);
    }
    /**
     * @return array
     */
    public function providerHtmlToHead() {
        return array(
            array('<style>'),
        );
    }

    /**
     * Test :htmlToBottom filter that adds HTML to the bottom of a page
     *
     * @param string $value
     * @dataProvider providerHtmlToBottom
     */
    public function testHtmlToBottom($value) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:htmlToBottom');
        $this->tag->process();
        $this->assertContains($value,$this->modx->jscripts);
        unset($this->modx->jscripts[$value]);
    }
    /**
     * @return array
     */
    public function providerHtmlToBottom() {
        return array(
            array('<footer>'),
        );
    }

    /**
     * Test :jsToBottom filter that adds JS to the bottom of a page
     *
     * @param string $value
     * @param boolean $addTag
     * @param boolean $plainText
     * @dataProvider providerJsToBottom
     */
    public function testJsToBottom($value,$addTag = false,$plainText = false) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:jsToBottom=`'.($plainText ? 1 : 0).'`');
        $this->tag->process();
        if ($addTag) {
            $value = '<script type="text/javascript" src="'.$value.'"></script>';
        }
        $this->assertContains($value,$this->modx->jscripts);
        unset($this->modx->jscripts[$value]);
    }
    /**
     * @return array
     */
    public function providerJsToBottom() {
        return array(
            array('assets/js/script.js',true,false),
            array('<script type="text/javascript" src="assets/js/script2.js"></script>',false,false),
            array('assets/js/script3.js',false,true),
        );
    }

    /**
     * Test :jsToHead filter that adds JS to the HEAD of a page
     *
     * @param string $value
     * @param boolean $addTag
     * @param boolean $plainText
     * @dataProvider providerJsToHead
     */
    public function testJsToHead($value,$addTag = false,$plainText = false) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:jsToHead=`'.($plainText ? 1 : 0).'`');
        $this->tag->process();
        if ($addTag) {
            $value = '<script type="text/javascript" src="'.$value.'"></script>';
        }
        $this->assertContains($value,$this->modx->sjscripts);
        unset($this->modx->sjscripts[$value]);
    }
    /**
     * @return array
     */
    public function providerJsToHead() {
        return array(
            array('assets/js/hscript.js',true,false),
            array('<script type="text/javascript" src="assets/js/hscript2.js"></script>',false,false),
            array('assets/js/hscript3.js',false,true),
        );
    }

    /**
     * Test :toPlaceholder=`phName` filter
     * 
     * @param string $toPlaceholder
     * @param mixed $value
     * @dataProvider providerToPlaceholder
     */
    public function testToPlaceholder($toPlaceholder,$value) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:toPlaceholder=`'.$toPlaceholder.'`');
        $this->tag->process();
        $this->assertArrayHasKey($toPlaceholder,$this->modx->placeholders);
        if (isset($this->modx->placeholders[$toPlaceholder])) {
            $this->assertEquals($value,$this->modx->placeholders[$toPlaceholder]);
        }
    }
    /**
     * @return array
     */
    public function providerToPlaceholder() {
        return array(
            array('myPlaceholder','Test'),
            array('emptyPlaceholder',''),
        );
    }
}