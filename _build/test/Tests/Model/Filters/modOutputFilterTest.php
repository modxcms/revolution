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
namespace MODX\Revolution\Tests\Model\Filters;


use MODX\Revolution\modPlaceholderTag;
use MODX\Revolution\MODxTestCase;

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
     * Tests the :cat filter
     *
     * @param string $value
     * @param string $string
     * @param string $expected
     * @dataProvider providerCat
     */
    public function testCat($value,$string,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:cat=`'.$string.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerCat() {
        return array(
            array('','',''),
            array('This dog',' went home','This dog went home'),
            array('','hello?','hello?'),
        );
    }

    /**
     * Tests the :uppercase filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerUppercase
     */
    public function testUppercase($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:uppercase');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerUppercase() {
        return array(
            array('',''),
            array('ALREADY THERE','ALREADY THERE'),
            array('booyah','BOOYAH'),
            array('i\'m not yelling','I\'M NOT YELLING'),
        );
    }

    /**
     * Tests the :lowercase filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerLowercase
     */
    public function testLowercase($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:lowercase');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerLowercase() {
        return array(
            array('',''),
            array('BOOYAH','booyah'),
            array('BoOyAh','booyah'),
            array('THiS CaT WENt To THe  cITy','this cat went to the  city'),
        );
    }

    /**
     * Tests the :ucwords filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerUCWords
     */
    public function testUCWords($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:ucwords');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerUCWords() {
        return array(
            array('',''),
            array('test','Test'),
            array('A big fat elephant','A Big Fat Elephant'),
            array('Have you read a Dr. Seuss Book?','Have You Read A Dr. Seuss Book?'),
        );
    }

    /**
     * Tests the :ucfirst filter
     *
     * @param string $value
     * @param string $expected
     * @dataProvider providerUCFirst
     */
    public function testUCFirst($value,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:ucfirst');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerUCFirst() {
        return array(
            array('',''),
            array('test','Test'),
            array('green eggers and hammond','Green eggers and hammond'),
            array('bocce ball, anyone?','Bocce ball, anyone?'),
        );
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
            ,10,'Lurem ipsoom&#8230;'),
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
     * Tests the :add filter
     *
     * @param string $value
     * @param int $add
     * @param string $expected
     * @dataProvider providerAdd
     */
    public function testAdd($value,$add,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:add=`'.$add.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerAdd() {
        return array(
            array('',0,0),
            array('123',1,124),
            array(-1,1,0),
            array(5,-1,4),
        );
    }

    /**
     * Tests the :subtract filter
     *
     * @param string $value
     * @param int $add
     * @param string $expected
     * @dataProvider providerSubtract
     */
    public function testSubtract($value,$add,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:subtract=`'.$add.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerSubtract() {
        return array(
            array('',0,0),
            array('123',1,122),
            array(-1,1,-2),
            array(1,1,0),
            array(5,-1,6),
        );
    }

    /**
     * Tests the :multiply filter
     *
     * @param string $value
     * @param int $multiplier
     * @param string $expected
     * @dataProvider providerMultiply
     */
    public function testMultiply($value,$multiplier,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:multiply=`'.$multiplier.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerMultiply() {
        return array(
            array('',0,0),
            array(1,5,5),
            array(4,7,28),
            array('100',2,200),
        );
    }

    /**
     * Tests the :divide filter
     *
     * @param string $value
     * @param int $divider
     * @param string $expected
     * @dataProvider providerDivide
     */
    public function testDivide($value,$divider,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:divide=`'.$divider.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerDivide() {
        return array(
            array(1,0,.5),
            array(0,0,0),
            array(10,5,2),
        );
    }

    /**
     * Tests the :divide filter
     *
     * @param string $value
     * @param int $modulus
     * @param string $expected
     * @dataProvider providerModulus
     */
    public function testModulus($value,$modulus,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:modulus=`'.$modulus.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerModulus() {
        return array(
            array(4,2,0),
            array(9,3,0),
            array(0,0,0),
            array(4,3,1),
            array(10,4,2),
        );
    }

    /**
     * Tests the :default filter
     *
     * @param string $value
     * @param int $default
     * @param string $expected
     * @dataProvider providerDefault
     */
    public function testDefault($value,$default,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:default=`'.$default.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerDefault() {
        return array(
            array('','foo','foo'),
            array('z','a','z'),
        );
    }

    /**
     * Tests the :notempty filter
     *
     * @param string $value
     * @param int $default
     * @param string $expected
     * @dataProvider providerNotEmpty
     */
    public function testNotEmpty($value,$default,$expected) {
        $this->modx->setPlaceholder('utp',$value);
        $this->tag->set('name','utp:notempty=`'.$default.'`');
        $o = $this->tag->process();
        $this->assertEquals($expected,$o);
    }
    /**
     * @return array
     */
    public function providerNotEmpty() {
        return array(
            array('','foo',''),
            array('z','a','a'),
            array('name','John','John'),
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
     * Tests :dirname filter
     *
     * @param string $filepath
     * @param array $expected
     * @dataProvider providerDirname
     */
    public function testDirname($filepath, $expected)
    {
        $this->modx->setPlaceholder('filepath', $filepath);
        $this->tag->set('name', 'filepath:dirname');
        $o = $this->tag->process();
        $this->modx->unsetPlaceholder('filepath');
        $this->assertEquals($expected, $o);
    }
    /**
     * @return array
     */
    public function providerDirname()
    {
        return array(
            array('/icon.ico', '/'),
            array('/assets/images/logo.jpg', '/assets/images'),
            array('./assets/files/doc.pdf', './assets/files'),
            // last three tests for pathinfo() function documentation notes
            array('/test/test.inc.php', '/test'),
            array('/test/test', '/test'),
            array('/test/.test', '/test'),
        );
    }

    /**
     * Tests :basename filter
     *
     * @param string $filepath
     * @param array $expected
     * @dataProvider providerBasename
     */
    public function testBasename($filepath, $expected)
    {

        $this->modx->setPlaceholder('filepath', $filepath);
        $this->tag->set('name', 'filepath:basename');
        $o = $this->tag->process();
        $this->modx->unsetPlaceholder('filepath');
        $this->assertEquals($expected, $o);
    }
    /**
     * @return array
     */
    public function providerBasename()
    {
        return array(
            array('/icon.ico', 'icon.ico'),
            array('/assets/images/logo.jpg', 'logo.jpg'),
            array('./assets/files/doc.pdf', 'doc.pdf'),
            // last three tests for pathinfo() function documentation notes
            array('/test/test.inc.php', 'test.inc.php'),
            array('/test/test', 'test'),
            array('/test/.test', '.test'),
        );
    }

    /**
     * Tests :filename filter
     *
     * @param string $filepath
     * @param array $expected
     * @dataProvider providerFilename
     */
    public function testFilename($filepath, $expected)
    {
        $this->modx->setPlaceholder('filepath', $filepath);
        $this->tag->set('name', 'filepath:filename');
        $o = $this->tag->process();
        $this->modx->unsetPlaceholder('filepath');
        $this->assertEquals($expected, $o);
    }
    /**
     * @return array
     */
    public function providerFilename()
    {
        return array(
            array('/icon.ico', 'icon'),
            array('/assets/images/logo.jpg', 'logo'),
            array('./assets/files/doc.pdf', 'doc'),
            // last three tests for pathinfo() function documentation notes
            array('/test/test.inc.php', 'test.inc'),
            array('/test/test', 'test'),
            array('/test/.test', ''),
        );
    }

    /**
     * Tests :extension filter
     *
     * @param string $filepath
     * @param array $expected
     * @dataProvider providerExtension
     */
    public function testExtension($filepath, $expected)
    {
        $this->modx->setPlaceholder('filepath', $filepath);
        $this->tag->set('name', 'filepath:extension');
        $o = $this->tag->process();
        $this->modx->unsetPlaceholder('filepath');
        $this->assertEquals($expected, $o);
    }
    /**
     * @return array
     */
    public function providerExtension()
    {
        return array(
            array('/icon.ico', 'ico'),
            array('/assets/images/logo.jpg', 'jpg'),
            array('./assets/files/doc.pdf', 'pdf'),
            // last three tests for pathinfo() function documentation notes
            array('/test/test.inc.php', 'php'),
            array('/test/test', ''),
            array('/test/.test', 'test'),
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
