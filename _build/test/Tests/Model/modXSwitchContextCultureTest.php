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

use MODX\Revolution\modContextSetting;
use MODX\Revolution\MODxTestCase;

/**
 * Regression for culture re-init inside switchContext (#14962).
 *
 * @group Model
 * @group modX
 */
class modXSwitchContextCultureTest extends MODxTestCase
{
    public function testSwitchContextReturnsFalseWhenContextMissing()
    {
        $previous = $this->modx->context;
        $this->modx->context = null;
        try {
            $this->assertFalse($this->modx->switchContext('web'));
        } finally {
            $this->modx->context = $previous;
        }
    }

    public function testSwitchContextReinitializesCultureKey()
    {
        $originalKey = $this->modx->context->get('key');
        $targetKey = ($originalKey === 'web') ? 'mgr' : 'web';

        $setting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => $targetKey,
            'key' => 'cultureKey',
        ]);
        $created = false;
        if (!$setting) {
            $setting = $this->modx->newObject(modContextSetting::class);
            $setting->fromArray([
                'context_key' => $targetKey,
                'key' => 'cultureKey',
                'value' => 'de',
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => 'language',
            ], '', true);
            $this->assertTrue((bool)$setting->save());
            $created = true;
        }
        $previousValue = $setting->get('value');
        $setting->set('value', 'de');
        $setting->save();

        // Avoid session/request overrides masking the context cultureKey.
        unset($_SESSION['cultureKey'], $_REQUEST['cultureKey']);

        try {
            $switched = $this->modx->switchContext($targetKey, true);
            $this->assertTrue($switched, 'Expected switchContext to succeed');
            $this->assertSame('de', $this->modx->cultureKey);
            $this->assertSame('de', $this->modx->getOption('cultureKey'));
        } finally {
            $this->modx->switchContext($originalKey, true);
            if ($created) {
                $setting->remove();
            } else {
                $setting->set('value', $previousValue);
                $setting->save();
            }
        }
    }
}
