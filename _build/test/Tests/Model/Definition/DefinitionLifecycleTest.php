<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\modX;
use MODX\Revolution\Definition\DefinitionManifestCompiler;
use MODX\Revolution\Definition\DefinitionRegistryArtifact;
use MODX\Revolution\MODxTestCase;

class DefinitionLifecycleTest extends MODxTestCase
{
    private string $fixtureRoot;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->fixtureRoot = sys_get_temp_dir() . '/modx-definition-lifecycle-' . bin2hex(random_bytes(8));
        mkdir($this->fixtureRoot, 0777, true);
        mkdir($this->fixtureRoot . '/artifacts', 0777, true);
        file_put_contents(
            $this->fixtureRoot . '/listener.php',
            '<?php $GLOBALS["phase0_definition_lifecycle"][] = $modx->event->name'
                . ' . ":" . ($scriptProperties["contextKey"] ?? "");'
        );
        file_put_contents($this->fixtureRoot . '/snippet.php', '<?php return "first";');
        file_put_contents($this->fixtureRoot . '/modx.php', $this->manifest());
        $GLOBALS['phase0_definition_lifecycle'] = [];
    }

    /** @after */
    public function tearDownFixtures()
    {
        unset($GLOBALS['phase0_definition_lifecycle']);
        unlink($this->fixtureRoot . '/listener.php');
        unlink($this->fixtureRoot . '/snippet.php');
        unlink($this->fixtureRoot . '/modx.php');
        foreach (glob($this->fixtureRoot . '/artifacts/*') ?: [] as $artifact) {
            unlink($artifact);
        }
        rmdir($this->fixtureRoot . '/artifacts');
        rmdir($this->fixtureRoot);
        parent::tearDownFixtures();
    }

    public function testListenersAreActiveForEarliestEventsAndEveryContextSwitch()
    {
        $modx = modX::getInstance(
            'definition-lifecycle-' . bin2hex(random_bytes(5)),
            ['definition_manifests' => [$this->fixtureRoot . '/modx.php']],
            true
        );

        $this->assertTrue($modx->initialize('web'));
        $this->assertSame([
            'OnContextInit:web',
            'OnInitCulture:',
            'OnMODXInit:web',
        ], $GLOBALS['phase0_definition_lifecycle']);

        $this->assertTrue($modx->switchContext('mgr'));
        $this->assertSame('OnContextInit:mgr', end($GLOBALS['phase0_definition_lifecycle']));
        $this->assertSame(
            'disk:phase0/lifecycle:listener:on-context-init',
            $modx->eventMap['OnContextInit']['disk:phase0/lifecycle:listener:on-context-init']
        );
        $modx->eventMap['Phase2ReferenceProbe'] = [];
        $this->assertArrayHasKey('Phase2ReferenceProbe', $modx->context->eventMap);
    }

    public function testManifestRequestsRecompileDiskInputsWithoutPersistentResolution(): void
    {
        $manifest = $this->fixtureRoot . '/modx.php';
        file_put_contents($manifest, $this->cacheManifest());

        $first = $this->freshRegistry($manifest);
        file_put_contents($this->fixtureRoot . '/snippet.php', '<?php return "second";');
        $sourceChanged = $this->freshRegistry($manifest);
        file_put_contents(
            $manifest,
            str_replace("'CacheSnippet'", "'RenamedCacheSnippet'", $this->cacheManifest())
        );
        $manifestChanged = $this->freshRegistry($manifest);

        $this->assertNotSame($first->getReleaseHash(), $sourceChanged->getReleaseHash());
        $this->assertNotSame($sourceChanged->getReleaseHash(), $manifestChanged->getReleaseHash());
        $this->assertNotNull(
            $manifestChanged->getDefinition(\MODX\Revolution\modSnippet::class, 'RenamedCacheSnippet')
        );

        unlink($this->fixtureRoot . '/snippet.php');
        try {
            $this->freshRegistry($manifest);
            $this->fail('A removed source file must be observed on the next manifest request.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('not readable', $exception->getMessage());
        }

        file_put_contents($this->fixtureRoot . '/snippet.php', '<?php return "restored";');
        unlink($manifest);
        try {
            $this->freshRegistry($manifest);
            $this->fail('A removed manifest must be observed on the next request.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('not readable', $exception->getMessage());
        } finally {
            file_put_contents($manifest, $this->manifest());
        }
    }

    public function testBootstrapRejectsAContentAddressedSymlinkedArtifact(): void
    {
        $artifact = $this->writeArtifact();
        $alias = $this->fixtureRoot . '/artifacts/' . str_repeat('a', 64) . '.php';
        $this->assertTrue(symlink($artifact, $alias));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('must not be a symlink');
        $this->registryFromArtifact($alias);
    }

    public function testBootstrapRejectsAnArtifactOutsideTheReleaseOwnedDirectory(): void
    {
        $artifact = $this->writeArtifact();
        $outside = $this->fixtureRoot . '/' . basename($artifact);
        $this->assertTrue(copy($artifact, $outside));

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('outside definition_registry_artifact_dir');
            $this->registryFromArtifact($outside);
        } finally {
            unlink($outside);
        }
    }

    public function testBootstrapRejectsAMissingReleaseOwnedArtifactDirectory(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('definition_registry_artifact_dir must resolve to a readable directory');

        $this->registryFromArtifact($this->writeArtifact(), $this->fixtureRoot . '/missing-artifacts');
    }

    private function writeArtifact(): string
    {
        $catalog = (new DefinitionManifestCompiler())->compile([$this->fixtureRoot . '/modx.php']);
        $artifact = $this->fixtureRoot . '/artifacts/' . $catalog['release_hash'] . '.php';
        (new DefinitionRegistryArtifact())->writeImmutable($artifact, $catalog);

        return $artifact;
    }

    private function registryFromArtifact(
        string $artifact,
        ?string $artifactDirectory = null
    ): \MODX\Revolution\Definition\DefinitionRegistry {
        $modx = modX::getInstance(
            'definition-artifact-' . bin2hex(random_bytes(5)),
            [
                'definition_registry_artifact' => $artifact,
                'definition_registry_artifact_dir' => $artifactDirectory ?? $this->fixtureRoot . '/artifacts',
            ],
            true
        );

        return $modx->getDefinitionRegistry();
    }

    private function freshRegistry(string $manifest): \MODX\Revolution\Definition\DefinitionRegistry
    {
        $modx = modX::getInstance(
            'definition-manifest-cache-' . bin2hex(random_bytes(5)),
            ['definition_manifests' => [$manifest]],
            true
        );

        return $modx->getDefinitionRegistry();
    }

    private function cacheManifest(): string
    {
        return <<<'PHP'
<?php

return [
    'schema' => 1,
    'package' => 'phase0/manifest-cache',
    'root' => __DIR__,
    'elements' => [
        'snippets' => [
            'CacheSnippet' => ['file' => 'snippet.php'],
        ],
    ],
];
PHP;
    }

    private function manifest(): string
    {
        return <<<'PHP'
<?php

return [
    'schema' => 1,
    'package' => 'phase0/lifecycle',
    'root' => __DIR__,
    'elements' => [],
    'listeners' => [
        [
            'key' => 'on-context-init',
            'event' => 'OnContextInit',
            'file' => 'listener.php',
            'priority' => 0,
            'contexts' => ['web', 'mgr'],
        ],
        [
            'key' => 'on-init-culture',
            'event' => 'OnInitCulture',
            'file' => 'listener.php',
            'priority' => 0,
            'contexts' => ['web'],
        ],
        [
            'key' => 'on-modx-init',
            'event' => 'OnMODXInit',
            'file' => 'listener.php',
            'priority' => 0,
            'contexts' => ['web'],
        ],
    ],
];
PHP;
    }
}
