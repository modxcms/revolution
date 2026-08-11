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
namespace MODX\Revolution\Tests\Model;

use MODX\Revolution\modMenu;
use MODX\Revolution\MODxTestCase;
use xPDO\xPDO;

/**
 * Tests related to modMenu cache rebuild and invalidation.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modMenu
 */
class modMenuTest extends MODxTestCase
{
    /**
     * rebuildCache must not emit Undefined global variable $_SESSION.
     */
    public function testRebuildCacheWithoutSessionDoesNotWarn()
    {
        $hadSession = array_key_exists('_SESSION', $GLOBALS);
        $previousSession = $hadSession ? $_SESSION : null;
        unset($GLOBALS['_SESSION']);

        $sessionWarnings = [];
        set_error_handler(static function ($severity, $message) use (&$sessionWarnings) {
            if (strpos($message, 'Undefined global variable $_SESSION') !== false) {
                $sessionWarnings[] = $message;
                return true;
            }

            return false;
        });

        try {
            $this->modx->setOption('cultureKey', 'en');
            $this->modx->getCacheManager();

            /** @var modMenu $menu */
            $menu = $this->modx->newObject(modMenu::class);
            $menus = $menu->rebuildCache('topnav');
            $this->assertIsArray($menus);
            $this->assertSame([], $sessionWarnings);
        } finally {
            restore_error_handler();
            if ($hadSession) {
                $_SESSION = $previousSession;
            } else {
                unset($GLOBALS['_SESSION']);
            }
        }
    }

    /**
     * clearCache (used by save/remove) must wipe language shards without $_SESSION.
     */
    public function testClearCacheWithoutSessionRemovesMenuKeys()
    {
        $hadSession = array_key_exists('_SESSION', $GLOBALS);
        $previousSession = $hadSession ? $_SESSION : null;
        unset($GLOBALS['_SESSION']);

        $this->modx->setOption('cultureKey', 'en');
        $cacheManager = $this->modx->getCacheManager();
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_menu_key', null, 'menu'),
            xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(
                'cache_menu_handler',
                null,
                $this->modx->getOption(xPDO::OPT_CACHE_HANDLER)
            ),
        ];

        $cacheKey = 'menus/topnav/en';
        $probe = ['probe' => true];
        $cacheManager->set($cacheKey, $probe, 0, $cacheOptions);
        $this->assertSame(['probe' => true], $cacheManager->get($cacheKey, $cacheOptions));

        try {
            /** @var modMenu $menu */
            $menu = $this->modx->newObject(modMenu::class);
            $clearCache = new \ReflectionMethod(modMenu::class, 'clearCache');
            $clearCache->setAccessible(true);
            $clearCache->invoke($menu);
            $this->assertNull($cacheManager->get($cacheKey, $cacheOptions));
        } finally {
            if ($hadSession) {
                $_SESSION = $previousSession;
            } else {
                unset($GLOBALS['_SESSION']);
            }
        }
    }
}
