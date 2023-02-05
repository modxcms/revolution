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

namespace MODX\Revolution\Tests\Model\Rest;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Rest\modRestService;
use MODX\Revolution\Rest\modRestServiceRequest;

/**
 * Tests related to the modRestService class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Rest
 * @group modRestService
 */
/**
 * @runTestsInSeparateProcesses
 */
final class modRestServiceTest extends MODxTestCase
{
    public function testPrepare()
    {
        $modRestService = new modRestService($this->modx);
        $modRestService->prepare();

        $this->assertInstanceOf(modRestServiceRequest::class, $modRestService->request);
    }

    public function testProcessWithUnfindableController()
    {
        $modRestService = new modRestService($this->modx, [
            'exitOnResponse' => false,
            'defaultAction' => 'unfindable',
        ]);
        $modRestService->prepare();

        $this->expectOutputString('{"success":false,"message":"Method not allowed","object":[],"code":405}');
        $modRestService->process();
    }

    public function testProcessWithUninstantiableController()
    {
        $modRestService = new modRestService($this->modx, [
            'exitOnResponse' => false,
            'defaultAction' => 'uninstantiable',
            'basePath' => MODX_BASE_PATH . '_build/test/data/rest/Controllers/',
            'controllerClassPrefix' => 'modRestServiceTest'
        ]);
        $modRestService->prepare();

        $this->expectOutputString('{"success":false,"message":"Bad Request","object":[],"code":400}');
        $modRestService->process();
    }

    public function testProcessWithUnsupportedHTTPMethod()
    {
        $modRestService = new modRestService($this->modx, [
            'exitOnResponse' => false,
            'basePath' => MODX_BASE_PATH . '_build/test/data/rest/Controllers/',
            'controllerClassPrefix' => 'modRestServiceTest'
        ]);
        $modRestService->prepare();
        $modRestService->request->setMethod('FOO');

        $this->expectOutputString('{"success":false,"message":"Unsupported HTTP method FOO","object":[],"code":405}');
        $modRestService->process();
    }

    public function testProcess()
    {
        $modRestService = new modRestService($this->modx, [
            'exitOnResponse' => false,
            'basePath' => MODX_BASE_PATH . '_build/test/data/rest/Controllers/',
            'controllerClassPrefix' => 'modRestServiceTest'
        ]);
        $modRestService->prepare();
        $modRestService->request->setMethod('GET');

        $this->expectOutputRegex('/{"results":\[\],"total":[0-9]+}/');
        $modRestService->process();
    }
}
