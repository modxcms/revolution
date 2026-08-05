<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionManifestCompiler;
use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DefinitionRegistryArtifact;
use RuntimeException;
use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

class DefinitionManifestCompilerTest extends XTestCase
{
    private string $fixtureRoot;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->fixtureRoot = sys_get_temp_dir() . '/modx-definition-' . bin2hex(random_bytes(8));
        mkdir($this->fixtureRoot . '/elements', 0777, true);
    }

    /** @after */
    public function tearDownFixtures()
    {
        $directories = [$this->fixtureRoot . '/elements', $this->fixtureRoot];
        foreach ($directories as $directory) {
            $entries = glob($directory . '/{,.}*', GLOB_BRACE) ?: [];
            foreach ($entries as $entry) {
                if (is_file($entry) || is_link($entry)) {
                    unlink($entry);
                }
            }
        }
        if (is_dir($this->fixtureRoot . '/elements')) {
            rmdir($this->fixtureRoot . '/elements');
        }
        if (is_dir($this->fixtureRoot)) {
            rmdir($this->fixtureRoot);
        }
        parent::tearDownFixtures();
    }

    public function testEmptyReleaseHashConstantMatchesACompiledEmptyCatalog()
    {
        $this->assertSame(
            DefinitionRegistry::EMPTY_RELEASE_HASH,
            (new DefinitionManifestCompiler())->compile([])['release_hash'],
            'DefinitionRegistry::EMPTY_RELEASE_HASH must equal the compiled empty-catalog release hash.'
        );
    }

    public function testCompilationIsDeterministicAndContentAddressed()
    {
        $manifest = $this->writeManifest('return "disk-v1";');
        $compiler = new DefinitionManifestCompiler();

        $first = $compiler->compile([$manifest]);
        $second = $compiler->compile([$manifest]);

        $this->assertSame($first, $second);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $first['release_hash']);
        $definition = $first['definitions']['MODX\\Revolution\\modSnippet']['search'];
        $this->assertSame('disk:acme/search:snippet:Search', $definition['key']);
        $this->assertSame('Search', $definition['name']);
        $this->assertSame('search', $definition['normalized_name']);

        file_put_contents($this->fixtureRoot . '/elements/Search.php', '<?php return "disk-v2";');
        $changed = $compiler->compile([$manifest]);

        $this->assertNotSame($first['release_hash'], $changed['release_hash']);
    }

    public function testReleaseHashDoesNotDependOnReleaseDirectory()
    {
        $manifest = $this->writeManifest('return "disk";');
        $copyRoot = sys_get_temp_dir() . '/modx-definition-copy-' . bin2hex(random_bytes(8));
        mkdir($copyRoot . '/elements', 0777, true);
        copy($manifest, $copyRoot . '/modx.php');
        copy($this->fixtureRoot . '/elements/Search.php', $copyRoot . '/elements/Search.php');

        try {
            $compiler = new DefinitionManifestCompiler();
            $original = $compiler->compile([$manifest]);
            $relocated = $compiler->compile([$copyRoot . '/modx.php']);

            $this->assertSame($original['release_hash'], $relocated['release_hash']);
            $this->assertNotSame(
                $original['listeners']['disk:acme/search:listener:guard']['manifest'],
                $relocated['listeners']['disk:acme/search:listener:guard']['manifest']
            );
        } finally {
            unlink($copyRoot . '/elements/Search.php');
            unlink($copyRoot . '/modx.php');
            rmdir($copyRoot . '/elements');
            rmdir($copyRoot);
        }
    }

    public function testPackageIdentifiersMustAlreadyBeCanonical()
    {
        $manifest = $this->writeManifest('return "disk";', 'Acme/Search');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('canonical package identifier');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testUnsupportedTopLevelManifestNamespaceIsRejected()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "    'root' => __DIR__,",
            "    'root' => __DIR__,\n    'namespace' => 'acme',",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unsupported definition manifest field');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testPackageIdentifierCanBelongToOnlyOneManifest()
    {
        $manifest = $this->writeManifest('return "disk";');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('one manifest per package identifier');

        (new DefinitionManifestCompiler())->compile([$manifest, $manifest]);
    }

    public function testCrossPackageDuplicateNamesIdentifyBothPackages()
    {
        $first = $this->writeManifest('return "disk";', 'acme/first');
        $second = $this->fixtureRoot . '/second-manifest.php';
        file_put_contents($second, str_replace('acme/first', 'acme/second', file_get_contents($first)));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('acme/first and acme/second');

        (new DefinitionManifestCompiler())->compile([$first, $second]);
    }

    public function testSourcePathsCannotEscapeThePackageRootThroughSymlinks()
    {
        $outside = tempnam(sys_get_temp_dir(), 'modx-definition-outside-');
        file_put_contents($outside, '<?php return "outside";');
        symlink($outside, $this->fixtureRoot . '/elements/Search.php');
        $manifest = $this->writeManifest(null);

        try {
            (new DefinitionManifestCompiler())->compile([$manifest]);
            $this->fail('A symlink escape must be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('outside its package root', $exception->getMessage());
        } finally {
            unlink($this->fixtureRoot . '/elements/Search.php');
            file_put_contents($this->fixtureRoot . '/elements/Search.php', '<?php return "disk";');
            unlink($outside);
        }
    }

    public function testSourcePathsCannotTraverseOutsideThePackageRoot()
    {
        $outside = dirname($this->fixtureRoot) . '/modx-definition-traversal-' . basename($this->fixtureRoot) . '.php';
        file_put_contents($outside, '<?php return "outside";');
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'file' => 'elements/Search.php',\n                'properties'",
            "'file' => '../" . basename($outside) . "',\n                'properties'",
            file_get_contents($manifest)
        ));

        try {
            (new DefinitionManifestCompiler())->compile([$manifest]);
            $this->fail('A parent-directory traversal must be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('outside its package root', $exception->getMessage());
        } finally {
            unlink($outside);
        }
    }

    public function testAbsoluteSourcePathsAreRejected()
    {
        $absolute = $this->fixtureRoot . '/elements/Search.php';
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'file' => 'elements/Search.php',\n                'properties'",
            "'file' => '{$absolute}',\n                'properties'",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('must be relative');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testSourceDirectorySymlinksCannotEscapeThePackageRoot()
    {
        $outsideDirectory = dirname($this->fixtureRoot) . '/modx-definition-linked-' . basename($this->fixtureRoot);
        mkdir($outsideDirectory, 0777, true);
        file_put_contents($outsideDirectory . '/Search.php', '<?php return "outside";');
        symlink($outsideDirectory, $this->fixtureRoot . '/linked');
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'file' => 'elements/Search.php',\n                'properties'",
            "'file' => 'linked/Search.php',\n                'properties'",
            file_get_contents($manifest)
        ));

        try {
            (new DefinitionManifestCompiler())->compile([$manifest]);
            $this->fail('A symlinked-directory escape must be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('outside its package root', $exception->getMessage());
        } finally {
            unlink($this->fixtureRoot . '/linked');
            unlink($outsideDirectory . '/Search.php');
            rmdir($outsideDirectory);
        }
    }

    public function testDefinitionNamesMustUseASafeCharacterSet()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'Search' => [",
            "'../Evil' => [",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Definition names must match');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testDefinitionNamesCannotEndWithWhitespace()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'Search' => [",
            "'Search ' => [",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Definition names must match');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testDefinitionNamesAreLimitedToFiftyAsciiBytes()
    {
        $manifest = $this->writeManifest('return "disk";');
        $maximumName = str_repeat('A', 50);
        file_put_contents($manifest, str_replace(
            "'Search' => [",
            "'{$maximumName}' => [",
            file_get_contents($manifest)
        ));

        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $this->assertSame(
            $maximumName,
            $catalog['definitions']['MODX\\Revolution\\modSnippet'][strtolower($maximumName)]['name']
        );

        $tooLongName = $maximumName . 'A';
        file_put_contents($manifest, str_replace($maximumName, $tooLongName, file_get_contents($manifest)));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('at most 50 bytes');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testDefinitionNamesCannotBeEntirelyNumeric()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'Search' => [",
            "'001' => [",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('must not be entirely numeric');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testReleaseHashAcceptsAndDistinguishesNonUtf8SourceBytes()
    {
        $manifest = $this->writeManifest("return \"\xFF\";");
        $compiler = new DefinitionManifestCompiler();

        $first = $compiler->compile([$manifest]);
        file_put_contents($this->fixtureRoot . '/elements/Search.php', "<?php return \"\xFE\";");
        $changed = $compiler->compile([$manifest]);

        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $first['release_hash']);
        $this->assertNotSame($first['release_hash'], $changed['release_hash']);
    }

    public function testAsciiNameNormalizationIsExplicit()
    {
        $this->assertSame('identifier i', DefinitionRegistry::normalizeName('IDENTIFIER I'));
    }

    public function testCompiledArtifactRoundTripsWithoutReadingSourceDefinitions()
    {
        $manifest = $this->writeManifest('return "compiled";');
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $artifact = new DefinitionRegistryArtifact();
        $path = $this->fixtureRoot . '/elements/' . $catalog['release_hash'] . '.php';

        $artifact->write($path, $catalog);
        unlink($this->fixtureRoot . '/elements/Search.php');

        $this->assertSame($catalog, $artifact->load($path));
    }

    public function testCompiledArtifactRoundTripsArbitrarySourceBytes()
    {
        $artifact = new DefinitionRegistryArtifact();
        $path = $this->fixtureRoot . '/elements/registry.php';
        $catalog = [
            'schema' => 1,
            'definitions' => ['binary' => ['probe' => ['content' => "\x00\xFF"]]],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ];
        $catalog['release_hash'] = DefinitionManifestCompiler::calculateReleaseHash($catalog);

        $artifact->write($path, $catalog);

        $this->assertSame($catalog, $artifact->load($path));
    }

    public function testCompiledArtifactRejectsTamperedCatalogContent()
    {
        $manifest = $this->writeManifest('return "original";');
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $path = $this->fixtureRoot . '/elements/registry.php';
        (new DefinitionRegistryArtifact())->write($path, $catalog);
        $catalog['definitions']['MODX\\Revolution\\modSnippet']['search']['content'] = '<?php return "tampered";';
        file_put_contents($path, "<?php\n\nreturn " . var_export($catalog, true) . ";\n");

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Compiled definition registry is invalid');
        (new DefinitionRegistryArtifact())->load($path);
    }

    public function testCompiledArtifactRejectsANonArrayPayloadAsAnInvalidArtifact()
    {
        $path = $this->fixtureRoot . '/elements/registry.php';
        file_put_contents($path, '<?php return 1;');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Compiled definition registry is invalid');

        (new DefinitionRegistryArtifact())->load($path);
    }

    public function testCompiledArtifactRejectsRemovedOverrideState(): void
    {
        $manifest = $this->writeManifest('return "disk";');
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $catalog['definitions']['MODX\\Revolution\\modSnippet']['search']['override_requested'] = true;
        $catalog['definitions']['MODX\\Revolution\\modSnippet']['search']['override_authorized'] = true;
        $catalog['release_hash'] = DefinitionManifestCompiler::calculateReleaseHash($catalog);
        $path = $this->fixtureRoot . '/elements/registry.php';
        file_put_contents($path, "<?php\n\nreturn " . var_export($catalog, true) . ";\n");

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Compiled definition registry is invalid');

        (new DefinitionRegistryArtifact())->load($path);
    }

    public function testCachedArtifactAttestationIsInvalidatedWhenTheArtifactIdentityChanges()
    {
        $manifest = $this->writeManifest('return "original";');
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $path = $this->fixtureRoot . '/elements/registry.php';
        $artifact = new DefinitionRegistryArtifact();
        $artifact->write($path, $catalog);
        $identity = $artifact->identity($path);

        $catalog['definitions']['MODX\\Revolution\\modSnippet']['search']['content'] = '<?php return "tampered";';
        $replacement = $path . '.replacement';
        file_put_contents($replacement, "<?php\n\nreturn " . var_export($catalog, true) . ";\n");
        rename($replacement, $path);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Compiled definition registry is invalid');
        $artifact->load($path, $identity, $catalog['release_hash']);
    }

    public function testImmutableArtifactWriterFallsBackToExclusiveContentAddressedPublication()
    {
        $manifest = $this->writeManifest('return "fallback";');
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $path = $this->fixtureRoot . '/elements/' . $catalog['release_hash'] . '.php';
        $artifact = new class extends DefinitionRegistryArtifact {
            protected function publishHardLink(string $temporary, string $path): bool
            {
                return false;
            }
        };

        $this->assertTrue($artifact->writeImmutable($path, $catalog));
        $this->assertSame($catalog, $artifact->load($path));
        $this->assertFalse($artifact->writeImmutable($path, $catalog));
    }

    public function testManifestCollectionsMustHaveTheDeclaredShape()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        file_put_contents($manifest, str_replace(
            "'elements' => [",
            "'elements' => 'invalid',\n    'ignored_elements' => [",
            $contents
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('elements must be an array');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testUnknownElementEntryFieldIsRejectedWithItsSourceQualifiedKey()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "                'properties' => ['limit' => 20],",
            "                'properties' => ['limit' => 20],\n                'unexpected' => true,",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Unsupported definition element field for disk:acme/search:snippet:Search: unexpected'
        );

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testRemovedDatabaseOverrideFieldIsRejected(): void
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "                'properties' => ['limit' => 20],",
            "                'properties' => ['limit' => 20],\n                'override_database' => true,",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Unsupported definition element field for disk:acme/search:snippet:Search: override_database'
        );

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testUnknownListenerEntryFieldIsRejectedWithItsSourceQualifiedKey()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "            'contexts' => ['web'],",
            "            'contexts' => ['web'],\n            'unexpected' => true,",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Unsupported definition listener field for disk:acme/search:listener:guard: unexpected'
        );

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testAllSupportedElementAndListenerEntryFieldsCompile()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = str_replace("'snippets' => [", "'plugins' => [", file_get_contents($manifest));
        $contents = str_replace(
            "                'properties' => ['limit' => 20],",
            "                'properties' => ['limit' => 20],\n"
            . "                'property_sets' => ['Featured' => ['limit' => 10]],\n"
            . "                'media_source' => null,",
            $contents
        );
        $contents = str_replace(
            "            'event' => 'AcmeSearchBeforeQuery',",
            "            'event' => 'AcmeSearchBeforeQuery',\n"
            . "            'plugin' => 'Search',\n"
            . "            'property_set' => 'Featured',\n"
            . "            'properties' => ['enabled' => true],",
            $contents
        );
        file_put_contents($manifest, $contents);

        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $this->assertArrayHasKey('disk:acme/search:listener:guard', $catalog['listeners']);

        file_put_contents($manifest, str_replace(
            "            'event' => 'AcmeSearchBeforeQuery',\n"
            . "            'plugin' => 'Search',\n"
            . "            'property_set' => 'Featured',\n"
            . "            'properties' => ['enabled' => true],\n"
            . "            'file' => 'elements/Search.php',",
            "            'event' => 'AcmeSearchBeforeQuery',\n"
            . "            'plugin' => 'Search',\n"
            . "            'property_set' => 'Featured',\n"
            . "            'properties' => ['enabled' => true],\n"
            . "            'service' => 'Acme\\\\Guard',",
            file_get_contents($manifest)
        ));
        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $this->assertSame('Acme\\Guard', $catalog['listeners']['disk:acme/search:listener:guard']['service']);
    }

    public function testPropertySetNamesAreCaseInsensitiveAndDeclaredDuplicatesAreRejected()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        $propertySets = "'properties' => ['limit' => 20],\n"
            . "                'property_sets' => [\n"
            . "                    'Featured' => ['limit' => 10],\n"
            . "                    'featured' => ['limit' => 20],\n"
            . "                ],";
        $contents = str_replace(
            "'properties' => ['limit' => 20],",
            $propertySets,
            $contents
        );
        file_put_contents($manifest, $contents);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Duplicate property set names for disk:acme/search:snippet:Search: Featured and featured'
        );

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testMediaSourceMustBeNullOrOmittedForDiskElements()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        $contents = str_replace(
            "'properties' => ['limit' => 20],",
            "'properties' => ['limit' => 20],\n                'media_source' => null,",
            $contents
        );
        file_put_contents($manifest, $contents);

        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $definition = $catalog['definitions']['MODX\\Revolution\\modSnippet']['search'];
        $this->assertNull($definition['media_source']);

        file_put_contents($manifest, str_replace(
            "'media_source' => null,",
            "'media_source' => 7,",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('media_source');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testMediaSourceMustBeNullOrOmittedForPlugins()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = str_replace("'snippets' => [", "'plugins' => [", file_get_contents($manifest));
        $contents = str_replace(
            "'properties' => ['limit' => 20],",
            "'properties' => ['limit' => 20],\n                'media_source' => 7,",
            $contents
        );
        file_put_contents($manifest, $contents);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('media_source');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testListenerMustDeclareExactlyOneExecutableTarget()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        file_put_contents($manifest, str_replace(
            "'file' => 'elements/Search.php',\n            'priority'",
            "'file' => 'elements/Search.php',\n            'service' => 'Acme\\\\Guard',\n            'priority'",
            $contents
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('exactly one file or service target');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testListenerPluginIdentityMustBeANonEmptyString()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        $invalid = str_replace("'priority' => 10", "'plugin' => '',\n            'priority' => 10", $contents);
        file_put_contents($manifest, $invalid);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('plugin identity must be a non-empty string');

        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testListenerPropertySetIdentityIsCaseInsensitive()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = file_get_contents($manifest);
        $contents = str_replace("'snippets' => [", "'plugins' => [", $contents);
        $contents = str_replace(
            "'Search' => [\n                'file' => 'elements/Search.php',",
            "'Search' => [\n                'file' => 'elements/Search.php',\n                'property_sets' => ['Featured' => ['mode' => 'featured']],",
            $contents
        );
        $contents = str_replace(
            "'event' => 'AcmeSearchBeforeQuery',\n            'file' => 'elements/Search.php',",
            "'event' => 'AcmeSearchBeforeQuery',\n            'plugin' => 'Search',\n            'property_set' => 'FEATURED',\n            'file' => 'elements/Search.php',",
            $contents
        );
        file_put_contents($manifest, $contents);

        $catalog = (new DefinitionManifestCompiler())->compile([$manifest]);
        $this->assertSame('FEATURED', $catalog['listeners']['disk:acme/search:listener:guard']['property_set']);
    }

    public function testListenerPropertySetRequiresSamePackageDiskPlugin()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'file' => 'elements/Search.php',\n            'priority' => 10,",
            "'property_set' => 'Featured',\n            'file' => 'elements/Search.php',\n            'priority' => 10,",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('same-package disk plugin definition');
        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testListenerPropertySetMustExistOnItsDiskPlugin()
    {
        $manifest = $this->writeManifest('return "disk";');
        $contents = str_replace("'snippets' => [", "'plugins' => [", file_get_contents($manifest));
        $contents = str_replace(
            "'file' => 'elements/Search.php',\n            'priority' => 10,",
            "'plugin' => 'Search',\n            'property_set' => 'Missing',\n            'file' => 'elements/Search.php',\n            'priority' => 10,",
            $contents
        );
        file_put_contents($manifest, $contents);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('references unknown property_set: Missing');
        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testEventMetadataValidatesServiceAndGroup()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'AcmeSearchBeforeQuery' => ['service' => 'web']",
            "'AcmeSearchBeforeQuery' => ['service' => 'invalid', 'group' => 12]",
            file_get_contents($manifest)
        ));
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('event metadata');
        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testEventMetadataRejectsExplicitNullService()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "['service' => 'web']",
            "['service' => null]",
            file_get_contents($manifest)
        ));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('event metadata service');
        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    public function testArtifactWriterRejectsMalformedReleaseHash()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid definition registry catalog');

        (new DefinitionRegistryArtifact())->write($this->fixtureRoot . '/registry.php', [
            'schema' => 1,
            'release_hash' => 'not-a-sha-256',
        ]);
    }

    public function testCompilerRejectsTrailingNewlinesInManifestIdentifiers()
    {
        $invalidIdentifiers = [
            ["'package' => 'acme/search'", "'package' => 'acme/search\\n'", 'canonical package'],
            ["'key' => 'guard'", "'key' => 'guard\\n'", 'stable key'],
            [
                "'AcmeSearchBeforeQuery' => ['service' => 'web']",
                "'AcmeSearchBeforeQuery\\n' => ['service' => 'web']",
                'Invalid event name',
            ],
        ];
        foreach ($invalidIdentifiers as [$search, $replace, $message]) {
            $manifest = $this->writeManifest('return "disk";');
            file_put_contents($manifest, str_replace($search, $replace, file_get_contents($manifest)));
            try {
                (new DefinitionManifestCompiler())->compile([$manifest]);
                $this->fail("A trailing newline should be rejected: {$message}");
            } catch (RuntimeException $exception) {
                $this->assertStringContainsString($message, $exception->getMessage());
            }
        }
    }

    public function testArtifactWriterRejectsReleaseHashWithTrailingNewline()
    {
        $catalog = [
            'schema' => 1,
            'definitions' => [],
            'events' => [],
            'listeners' => [],
            'inventory' => [],
        ];
        $catalog['release_hash'] = DefinitionManifestCompiler::calculateReleaseHash($catalog) . "\n";

        $this->expectException(RuntimeException::class);
        (new DefinitionRegistryArtifact())->write($this->fixtureRoot . '/registry.php', $catalog);
    }

    public function testCompilerRejectsUnknownElementCollectionsAndPhpChunks()
    {
        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'elements' => [",
            "'elements' => ['templates' => [],",
            file_get_contents($manifest)
        ));
        try {
            (new DefinitionManifestCompiler())->compile([$manifest]);
            $this->fail('Unknown element collections must be rejected.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Unknown definition element collection', $exception->getMessage());
        }

        $manifest = $this->writeManifest('return "disk";');
        file_put_contents($manifest, str_replace(
            "'snippets' => [",
            "'chunks' => ['Chunk' => ['file' => 'elements/Search.php']],\n        'snippets' => [",
            file_get_contents($manifest)
        ));
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('chunk source must not use a .php file');
        (new DefinitionManifestCompiler())->compile([$manifest]);
    }

    private function writeManifest(?string $snippet = 'return "disk";', string $package = 'acme/search'): string
    {
        if ($snippet !== null) {
            file_put_contents($this->fixtureRoot . '/elements/Search.php', '<?php ' . $snippet);
        }

        $manifest = <<<'PHP'
<?php

return [
    'schema' => 1,
    'package' => '__PACKAGE__',
    'root' => __DIR__,
    'elements' => [
        'snippets' => [
            'Search' => [
                'file' => 'elements/Search.php',
                'properties' => ['limit' => 20],
            ],
        ],
    ],
    'events' => [
        'AcmeSearchBeforeQuery' => ['service' => 'web'],
    ],
    'listeners' => [
        [
            'key' => 'guard',
            'event' => 'AcmeSearchBeforeQuery',
            'file' => 'elements/Search.php',
            'priority' => 10,
            'contexts' => ['web'],
        ],
    ],
];
PHP;
        file_put_contents($this->fixtureRoot . '/modx.php', str_replace('__PACKAGE__', $package, $manifest));

        return $this->fixtureRoot . '/modx.php';
    }
}
