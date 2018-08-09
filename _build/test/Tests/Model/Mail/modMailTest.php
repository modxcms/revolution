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

/**
 * Tests related to the modMail class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Mail
 * @group modMail
 */
class modMailTest extends MODxTestCase {
    /**
     * @var modMail|PHPUnit_Framework_MockObject_MockObject $mail
     */
    public $mail;

    public function setUp() {
        parent::setUp();
        $this->modx->loadClass('mail.modMail',MODX_CORE_PATH.'model/modx/',true,true);
        $this->mail = $this->getMockForAbstractClass('modMail',array(&$this->modx));
        $this->mail->expects($this->any())
                   ->method('_getMailer')
                   ->will($this->returnValue(true));
    }

    /**
     * @param string $k
     * @param mixed $v
     * @dataProvider providerSet
     */
    public function testSet($k,$v) {
        $this->mail->set($k,$v);
        $this->assertEquals($v,$this->mail->attributes[$k]);
    }
    /**
     * @return array
     */
    public function providerSet() {
        return array(
            array('mail_use_smtp',true),
            array('mail_use_smtp',false),
        );
    }

    /**
     * @param string $k
     * @param mixed $v
     * @dataProvider providerGet
     * @depends testSet
     */
    public function testGet($k,$v) {
        $this->mail->set($k,$v);
        $result = $this->mail->get($k);
        $this->assertEquals($v,$result);
    }
    /**
     * @return array
     */
    public function providerGet() {
        return array(
            array('mail_use_smtp',true),
            array('mail_use_smtp',false),
        );
    }

    /**
     * @return void
     */
    public function testClearAttachments() {
        $this->mail->clearAttachments();
        $this->assertEmpty($this->mail->files);
    }

    /**
     * @param mixed $file
     * @dataProvider providerAttach
     * @depends testClearAttachments
     */
    public function testAttach($file) {
        $this->mail->clearAttachments();
        $this->mail->attach($file);
        $this->assertNotEmpty($this->mail->files);
        $this->mail->clearAttachments();
    }
    /**
     * @return array
     */
    public function providerAttach() {
        return array(
            array('test/file.txt'),
            array(array('tmp_name' => 'test/file.txt','error' => 0,'name' => 'file.txt')),
        );
    }

    /**
     * @param string $header
     * @param string $key
     * @param string $value
     * @dataProvider providerHeader
     */
    public function testHeader($header,$key,$value) {
        $this->mail->header($header);
        $found = false;
        foreach ($this->mail->headers as $header) {
            if (isset($header[0]) && isset($header[1])) {
                if ($header[0] == $key && $header[1] == $value) {
                    $found = true;
                    break;
                }
            }
        }
        $this->assertTrue($found);
    }
    /**
     * @return array
     */
    public function providerHeader() {
        return array(
            array('Content-type:text/html','Content-type','text/html'),
        );
    }

}
