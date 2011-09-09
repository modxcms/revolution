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
     * Tests the :stripString filter
     *
     * @param string $value
     * @param string $strip
     * @param string $expected
     * @dataProvider providerStripString
     */
    public function testStripString($value,$strip,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:stripString=`'.$strip.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerStripString() {
        return array(
            array('','',''),
            array('Don\'t even think about it','Don\'t even ','think about it'),
        );
    }

    /**
     * Tests the :replace filter
     *
     * @param string $value
     * @param string $with
     * @param string $expected
     * @dataProvider providerReplace
     */
    public function testReplace($value,$with,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:replace=`'.$with.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerReplace() {
        return array(
            array('','',''),
            array('Strip it all out','it all==none','Strip none out'),
            array('foobar','foo==bar','barbar'),
        );
    }

    /**
     * Tests the :stripTags filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerStripTags
     */
    public function testStripTags($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:stripTags');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerStripTags() {
        return array(
            array('Hi!<br />','Hi!'),
            array('<strong>Boo!</strong> No.','Boo! No.'),
            array('Dogs are cool <p>Cats are weird','Dogs are cool Cats are weird'),
            array('',''),
        );
    }

    /**
     * Tests the :strlen filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerStrLen
     */
    public function testStrLen($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:strlen');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerStrLen() {
        return array(
            array('abcdefghijklmnopqrstuvwxyz',26),
            array('',0),
            array('a big dog',9),
        );
    }

    /**
     * Tests the :reverse filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerEsrever
     */
    public function testEsrever($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:reverse');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerEsrever() {
        return array(
            array('a brown fox','xof nworb a'),
            array('level','level'),
            array('somemeninterpretninememos','somemeninterpretninememos'),
        );
    }

    /**
     * Tests the :limit filter
     *
     * @param string $value
     * @param int $limit
     * @param string $expected
     * @dataProvider providerLimit
     */
    public function testLimit($value,$limit,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:limit=`'.$limit.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerLimit() {
        return array(
            array('Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'
            ,10,'Lurem ipso'),
            array('Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'
            ,1000000,'Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'),
            array('','',''),
        );
    }

    /**
     * Tests the :ellipsis filter
     *
     * @param string $value
     * @param int $limit
     * @param string $expected
     * @dataProvider providerEllipsis
     */
    public function testEllipsis($value,$limit,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:ellipsis=`'.$limit.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerEllipsis() {
        return array(
            array('Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'
            ,10,'Lurem ipso&#8230;'),
            array('Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'
            ,1000000,'Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Ut depeeboos dooee fel megna oornere-a eleeqooem. Preesent ioo messa ut sepeeee sulleecitoodin mulesteee-a. Preesent looctoos, turtur sulleecitoodin sulleecitoodin fooceeboos, iret deeem imperdeeet moorees, nun iecoolees sepeeee mee qooees deeem. Qooeesqooe-a iooeesmud tempoos joostu. Ut iget neesl. Noolla feceelisi. Noolla nun felees. Um gesh dee bork, bork! Prueen iooeesmud toorpees nun toorpees. Um gesh dee bork, bork! Integer ioo iret sed neebh purta pleceret. Um de hur de hur de hur. Iteeem lectoos neebh, mettees feetee-a, deegnissim a, ileeeffend ec, deeem. Coom suceeis netuqooe-a peneteeboos it megnees dees pertooreeent muntes, nescetoor reedicooloos moos. Um gesh dee bork, bork! Iteeem nec felees fel mee teencidoont rhuncoos. Um gesh dee bork, bork! Moorees depeeboos. Um gesh dee bork, bork! Foosce-a qooem reesoos, pleceret sed, deegnissim rootroom, ileeeffend sed, leu. Lurem ipsoom dulur seet emet, cunsectetooer edeepiscing ileet. Um de hur de hur de hur. Eleeqooem lurem.'),
            array('','',''),
        );
    }

    /**
     * Tests the :nl2br filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerNL2BR
     */
    public function testNL2BR($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:nl2br');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerNL2BR() {
        return array(
            array('A test paragraph
goes here','A test paragraph<br />
goes here'),
            array('',''),
        );
    }

    /**
     * Tests the :strtotime filter
     *
     * @param string $value
     * @dataProvider providerStrToTime
     */
    public function testStrToTime($value) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:strtotime');
        $o = $this->tag->process();
        $this->assertEquals(strtotime($value),$o);
    }
    /**
     * @return array
     */
    public function providerStrToTime() {
        return array(
            array('2011-05-01 10:23:11'),
            array(''),
        );
    }

    /**
     * Tests the :md5 filter
     *
     * @param string $value
     * @dataProvider providerMD5
     */
    public function testMD5($value) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:md5');
        $o = $this->tag->process();
        $this->assertEquals(md5($value),$o);
    }
    /**
     * @return array
     */
    public function providerMD5() {
        return array(
            array('coolio'),
            array(''),
        );
    }

    /**
     * Tests the :cdata filter
     * 
     * @param string $value
     * @param string $expected
     * @dataProvider providerCData
     */
    public function testCData($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:cdata');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerCData() {
        return array(
            array('code here','<![CDATA[code here]]>'),
            array('','<![CDATA[]]>'),
        );
    }

    /**
     * Test :urlencode filter
     * 
     * @param string $value
     * @param string $expected
     * @dataProvider providerUrlEncode
     */
    public function testUrlEncode($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:urlencode');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerUrlEncode() {
        return array(
            array('test','test'),
            array('test with space','test+with+space'),
        );
    }

    /**
     * Test :urldecode filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerUrlDecode
     */
    public function testUrlDecode($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:urldecode');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerUrlDecode() {
        return array(
            array('test','test'),
            array('test+with+space','test with space'),
        );
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