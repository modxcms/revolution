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

namespace MODX\Revolution\Tests\Processors\Workspace\Packages;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Workspace\Packages\Install;

/**
 * Contract for corrupted package zip error handling (#16454).
 *
 * @group Processors
 * @group Workspace
 */
class InstallErrorHandlingTest extends MODxTestCase
{
    public function testInstallProcessorMapsZipErrorsToLexicon()
    {
        $installSource = file_get_contents(MODX_CORE_PATH . 'src/Revolution/Processors/Workspace/Packages/Install.php');
        $this->assertStringContainsString('catch (\\Throwable $e)', $installSource);
        $this->assertStringContainsString('getInstallErrorMessage', $installSource);
        $this->assertStringContainsString('package_err_zip_invalid', $installSource);
        $this->assertStringContainsString('instanceof \\ValueError', $installSource);

        $lexicon = file_get_contents(MODX_CORE_PATH . 'lexicon/en/workspace.inc.php');
        $this->assertStringContainsString('package_err_zip_invalid', $lexicon);

        $transportSource = file_get_contents(MODX_CORE_PATH . 'src/Revolution/Transport/modTransportPackage.php');
        $this->assertMatchesRegularExpression(
            '/xPDOTransport::retrieve\([^;]+\);/',
            $transportSource,
            'getTransport() should call xPDOTransport::retrieve() without swallowing exceptions'
        );
        $this->assertStringNotContainsString(
            "try {\n                            \$this->package = xPDOTransport::retrieve",
            $transportSource
        );
    }

    public function testGetInstallErrorMessageMapsValueErrorToZipLexicon()
    {
        $processor = new Install($this->modx);
        $method = new \ReflectionMethod(Install::class, 'getInstallErrorMessage');
        $method->setAccessible(true);

        $zipMessage = $method->invoke($processor, new \ValueError('Invalid or uninitialized Zip object'));
        $this->assertSame($this->modx->lexicon('package_err_zip_invalid'), $zipMessage);

        $genericMessage = $method->invoke($processor, new \RuntimeException('Something else failed'));
        $this->assertSame($this->modx->lexicon('package_err_install_gen'), $genericMessage);
    }
}
