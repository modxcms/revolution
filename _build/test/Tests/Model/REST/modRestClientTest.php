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
 * Tests related to the modRestClient class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group REST
 * @group modRestClient
 */
class modRestClientTest extends MODxTestCase {
    /**
     * Assert that modRestClient.getConnection loads the client
     */
    public function testGetConnection() {
        /** @var modRestClient $rest */
        $rest = $this->modx->getService('rest','rest.modRestClient');
        $success = $rest->getConnection();

        $this->assertTrue($success);
    }

    /**
     * Assert that modRestClient.setResponseType actually sets the response type
     */
    public function testSetResponseType() {
        /** @var modRestClient $rest */
        $rest = $this->modx->getService('rest','rest.modRestClient');
        $rest->setResponseType('json');

        $this->assertEquals($rest->responseType,'json');
    }
}
