<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Tests\Controllers;

use MODX\Revolution\modManagerRequest;
use MODX\Revolution\modX;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Ensures manager page system events fire in semantic order (OnBeforeManagerPageInit before OnManagerPageInit, #9461).
 *
 * @group Controllers
 * @group ManagerController
 */
class ManagerPageInitEventsTest extends TestCase
{
    /**
     * @return array{0: modX, 1: ModxInvokeEventCallLog}
     */
    private function createModxWithEventLog(): array
    {
        $log = new ModxInvokeEventCallLog();

        /** @var modX&\PHPUnit\Framework\MockObject\MockObject $modx */
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['invokeEvent'])
            ->getMock();

        $modx->method('invokeEvent')->willReturnCallback(
            static function ($eventName, array $params = []) use ($log) {
                $log->record($eventName, $params);

                return [];
            }
        );

        return [$modx, $log];
    }

    private function attachStubManagerRequest(modX $modx, string $action, string $namespace): void
    {
        $request = $this->getMockBuilder(modManagerRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->action = $action;
        $request->namespace = $namespace;
        $modx->request = $request;
    }

    public function testOnBeforeManagerPageInitRunsBeforeOnManagerPageInit(): void
    {
        [$modx, $log] = $this->createModxWithEventLog();
        $this->attachStubManagerRequest($modx, 'welcome', 'core');

        $config = [
            'namespace' => 'core',
            'namespace_path' => '/mgr/',
            'action' => 'welcome',
            'controller' => 'welcome',
        ];

        $controller = new MinimalManagerControllerForInitEventsStub($modx, $config);
        $controller->runPageInitEventsForTest();

        $invocations = $log->getInvocations();
        $this->assertCount(2, $invocations);
        $this->assertSame('OnBeforeManagerPageInit', $invocations[0][0]);
        $this->assertSame($config, $invocations[0][1]);
        $this->assertSame('OnManagerPageInit', $invocations[1][0]);
        $expectedManagerInit = array_merge($config, [
            'action' => 'welcome',
            'namespace' => 'core',
        ]);
        $this->assertSame($expectedManagerInit, $invocations[1][1]);
    }

    public function testOnManagerPageInitPrefersRequestActionAndNamespaceOverConfig(): void
    {
        [$modx, $log] = $this->createModxWithEventLog();
        $this->attachStubManagerRequest($modx, 'from_request', 'from_request_ns');

        $config = [
            'namespace' => 'config_ns',
            'action' => 'config_action',
            'controller' => 'config_action',
        ];

        $controller = new MinimalManagerControllerForInitEventsStub($modx, $config);
        $controller->runPageInitEventsForTest();

        $invocations = $log->getInvocations();
        $this->assertCount(2, $invocations);
        $this->assertSame('OnBeforeManagerPageInit', $invocations[0][0]);
        $this->assertSame($config, $invocations[0][1]);
        $this->assertSame('OnManagerPageInit', $invocations[1][0]);
        $this->assertSame('from_request', $invocations[1][1]['action']);
        $this->assertSame('from_request_ns', $invocations[1][1]['namespace']);
    }
}
