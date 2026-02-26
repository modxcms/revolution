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

namespace MODX\Revolution\Tests\Processors\Element\TemplateVar\Renders\mgr\input;

use MODX\Revolution\modTemplateVar;
use MODX\Revolution\MODxTestCase;

/**
 * Tests for date TV input render (minTimeValue/maxTimeValue with Smarty placeholder handling).
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group TemplateVar
 */
class DateInputRenderTest extends MODxTestCase
{
    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->getService('smarty', 'MODX\Revolution\Smarty\modSmarty', '');
    }

    /**
     * Test that static time values are converted to date format.
     */
    public function testProcessConvertsStaticTimeValues()
    {
        $placeholders = [];
        $controller = new \stdClass();
        $controller->setPlaceholder = function ($k, $v) use (&$placeholders) {
            $placeholders[$k] = $v;
        };
        $this->modx->controller = $controller;

        $tv = $this->modx->newObject(modTemplateVar::class);
        $tv->fromArray(['id' => 1, 'name' => 'test'], '', true, true);

        $dateClassPath = dirname(__DIR__, 9)
            . '/core/src/Revolution/Processors/Element/TemplateVar/Renders/mgr/input/date.class.php';
        $renderClass = include $dateClassPath;
        $render = new $renderClass($tv, []);
        $render->process('2025-01-15 14:30:00', [
            'maxTimeValue' => '14:30',
            'minTimeValue' => '09:00',
        ]);

        $this->assertArrayHasKey('params', $placeholders);
        $params = $placeholders['params'];
        $this->assertEquals('2:30 PM', $params['maxTimeValue']);
        $this->assertEquals('9:00 AM', $params['minTimeValue']);
    }

    /**
     * Test that Smarty placeholders in time values are not converted.
     */
    public function testProcessSkipsSmartyPlaceholdersInTimeValues()
    {
        $placeholders = [];
        $controller = new \stdClass();
        $controller->setPlaceholder = function ($k, $v) use (&$placeholders) {
            $placeholders[$k] = $v;
        };
        $this->modx->controller = $controller;

        $tv = $this->modx->newObject(modTemplateVar::class);
        $tv->fromArray(['id' => 1, 'name' => 'test'], '', true, true);

        $dateClassPath = dirname(__DIR__, 9)
            . '/core/src/Revolution/Processors/Element/TemplateVar/Renders/mgr/input/date.class.php';
        $renderClass = include $dateClassPath;
        $render = new $renderClass($tv, []);
        $smartyMax = '{$smarty.now|date_format:\'%H:%M\'}';
        $smartyMin = '{$smarty.now|date_format:\'%I:%M %p\'}';
        $render->process('', [
            'maxTimeValue' => $smartyMax,
            'minTimeValue' => $smartyMin,
        ]);

        $this->assertArrayHasKey('params', $placeholders);
        $params = $placeholders['params'];
        $this->assertEquals($smartyMax, $params['maxTimeValue']);
        $this->assertEquals($smartyMin, $params['minTimeValue']);
    }
}
