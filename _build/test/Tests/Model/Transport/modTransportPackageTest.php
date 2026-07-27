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

namespace MODX\Revolution\Tests\Model\Transport;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Transport\modTransportPackage;

/**
 * Tests related to the modTransportPackage class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group modTransportPackage
 */
class modTransportPackageTest extends MODxTestCase
{
    /** @var string */
    private $signature = 'unittest-emoji-0.0.1-pl';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        modTransportPackage::resetUtf8mb4SupportCache();
        $existing = $this->modx->getObject(modTransportPackage::class, ['signature' => $this->signature]);
        if ($existing) {
            $existing->remove();
        }
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $existing = $this->modx->getObject(modTransportPackage::class, ['signature' => $this->signature]);
        if ($existing) {
            $existing->remove();
        }
        modTransportPackage::resetUtf8mb4SupportCache();
        parent::tearDownFixtures();
    }

    public function testSanitizeConvertsSupplementaryPlaneCharactersWithoutMbstringApi()
    {
        $package = $this->newForceSanitizingPackage(true);
        $package->set('metadata', [
            'readme' => "Intro 🚀 end",
            'nested' => ['changelog' => "Ship it ✨ and 🎉"],
        ]);

        $metadata = $package->get('metadata');
        $this->assertSame('Intro &#x1f680; end', $metadata['readme']);
        // ✨ is BMP (3-byte UTF-8); only supplementary-plane chars become entities.
        $this->assertSame('Ship it ✨ and &#x1f389;', $metadata['nested']['changelog']);
    }

    public function testSanitizeLeavesBmpAndMalformedBytesAlone()
    {
        $package = $this->newForceSanitizingPackage(true);
        $malformed = "ok\xF0\x28broken";
        $package->set('attributes', [
            'readme' => "Café ✓",
            'raw' => $malformed,
        ]);

        $attributes = $package->get('attributes');
        $this->assertSame('Café ✓', $attributes['readme']);
        $this->assertSame($malformed, $attributes['raw']);
    }

    public function testSanitizeIsIdempotent()
    {
        $package = $this->newForceSanitizingPackage(true);
        $package->set('metadata', ['readme' => '🚀']);
        $once = $package->get('metadata');
        $package->set('metadata', $once);
        $twice = $package->get('metadata');

        $this->assertSame('&#x1f680;', $once['readme']);
        $this->assertSame($once, $twice);
    }

    public function testUtf8mb4CapableConnectionPreservesEmoji()
    {
        $package = $this->newForceSanitizingPackage(false);
        $package->set('metadata', ['readme' => 'Hello 🚀']);
        $metadata = $package->get('metadata');
        $this->assertSame('Hello 🚀', $metadata['readme']);
    }

    public function testSaveMetadataWithEmojiOnNonUtf8mb4Database()
    {
        $package = $this->modx->newObject(modTransportPackage::class);
        $package->fromArray([
            'signature' => $this->signature,
            'created' => date('Y-m-d H:i:s'),
            'updated' => null,
            'installed' => null,
            'state' => 1,
            'workspace' => 1,
            'provider' => 0,
            'disabled' => false,
            'source' => $this->signature . '.transport.zip',
            'package_name' => 'unittest-emoji',
            'version_major' => 0,
            'version_minor' => 0,
            'version_patch' => 1,
            'release' => 'pl',
            'release_index' => 0,
        ], '', true, true);
        // Use set() so non-utf8mb4 sanitization runs (fromArray rawValues bypasses it).
        $package->set('metadata', [
            'name' => 'UnitTest Emoji',
            'readme' => "Install me 🚀 please",
        ]);
        $package->set('attributes', [
            'readme' => "Local readme with 🎉",
        ]);

        $this->assertTrue((bool)$package->save(), 'Package with emoji metadata should save on non-utf8mb4.');

        modTransportPackage::resetUtf8mb4SupportCache();
        /** @var modTransportPackage $reloaded */
        $reloaded = $this->modx->getObject(modTransportPackage::class, ['signature' => $this->signature]);
        $this->assertInstanceOf(modTransportPackage::class, $reloaded);

        $metadata = $reloaded->get('metadata');
        $attributes = $reloaded->get('attributes');
        $this->assertSame('Install me &#x1f680; please', $metadata['readme']);
        $this->assertSame('Local readme with &#x1f389;', $attributes['readme']);
    }

    /**
     * @param bool $requiresSanitization
     * @return modTransportPackage
     */
    private function newForceSanitizingPackage($requiresSanitization)
    {
        $package = new class ($this->modx, $requiresSanitization) extends modTransportPackage {
            /** @var bool */
            private $forceRequiresSanitization;

            public function __construct($xpdo, $forceRequiresSanitization)
            {
                parent::__construct($xpdo);
                $this->forceRequiresSanitization = (bool)$forceRequiresSanitization;
            }

            protected function requiresMultibyteSanitization()
            {
                return $this->forceRequiresSanitization;
            }
        };

        return $package;
    }
}
