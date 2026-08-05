<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\Definition\DefinitionRegistry;
use MODX\Revolution\Definition\DefinitionRegistryArtifact;
use MODX\Revolution\Definition\DefinitionRegistryCli;
use MODX\Revolution\Definition\DefinitionRegistryDeployment;
use MODX\Revolution\Definition\DefinitionRegistryToolException;
use MODX\Revolution\Definition\EventDispatcher;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\modEvent;
use MODX\Revolution\modSnippet;
use xPDO\xPDO;

class DefinitionRegistryDeploymentTest extends MODxTestCase
{
    private string $fixtureRoot;
    private string $manifest;
    private ?string $warmedScriptCacheKey = null;

    /** @before */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->fixtureRoot = sys_get_temp_dir() . '/modx-definition-deployment-' . bin2hex(random_bytes(8));
        mkdir($this->fixtureRoot . '/artifacts', 0777, true);
        mkdir($this->fixtureRoot . '/elements', 0777, true);
        file_put_contents($this->fixtureRoot . '/elements/DeploySnippet.php', '<?php return "deployed";');
        file_put_contents($this->fixtureRoot . '/elements/DeployChunk.html', 'Deployment chunk');
        $this->manifest = $this->fixtureRoot . '/manifest.php';
        file_put_contents($this->manifest, $this->manifestSource());
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());
    }

    /** @after */
    public function tearDownFixtures()
    {
        $this->modx->setDefinitionRegistry(new DefinitionRegistry());
        $this->modx->getCacheManager()->delete('release-hash', [
            xPDO::OPT_CACHE_KEY => $this->modx->getOption(
                'cache_definition_registry_key',
                null,
                'definition_registry'
            ),
        ]);
        if ($this->warmedScriptCacheKey !== null) {
            $this->modx->getCacheManager()->delete($this->warmedScriptCacheKey, [
                xPDO::OPT_CACHE_KEY => $this->modx->getOption('cache_scripts_key', null, 'scripts'),
            ]);
            $include = $this->modx->getCachePath() . 'includes/' . $this->warmedScriptCacheKey . '.include.cache.php';
            if (is_file($include)) {
                unlink($include);
            }
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->fixtureRoot, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($this->fixtureRoot);
        parent::tearDownFixtures();
    }

    public function testValidateAndHashDoNotWriteAnArtifact()
    {
        $deployment = $this->deployment();

        $validated = $deployment->validate();
        $hashed = $deployment->hash();

        $this->assertSame($validated['release_hash'], $hashed['release_hash']);
        $this->assertSame(2, $validated['definitions']);
        $this->assertSame([], glob($this->fixtureRoot . '/artifacts/*'));
    }

    public function testReadOnlyCommandsDoNotReplaceTheActiveRegistry()
    {
        $active = new DefinitionRegistry([
            'release_hash' => hash('sha256', 'active-registry'),
        ]);
        $this->modx->setDefinitionRegistry($active);
        $deployment = $this->deployment();

        $commands = [
            'validate' => static fn() => $deployment->validate(),
            'hash' => static fn() => $deployment->hash(),
            'list' => static fn() => $deployment->list(),
            'explain' => static fn() => $deployment->explain(null, 'snippet', 'DeploySnippet'),
        ];
        foreach ($commands as $command => $run) {
            $run();
            $this->assertSame(
                $active,
                $this->modx->getDefinitionRegistry(),
                "{$command} must not activate its candidate registry."
            );
        }
    }

    public function testUnreadableReleaseInputUsesTheFilesystemExitCode()
    {
        $deployment = $this->deployment([
            'definition_manifests' => [$this->fixtureRoot . '/missing.php'],
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->validate(),
            4,
            'release-input-failed',
            'Missing release input must fail validation.'
        );
    }

    public function testMalformedManifestUsesTheValidationExitCode()
    {
        $manifest = $this->fixtureRoot . '/malformed.php';
        file_put_contents($manifest, '<?php return [;');
        $deployment = $this->deployment([
            'definition_manifests' => [$manifest],
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->validate(),
            3,
            'release-validation-failed',
            'Malformed release input must fail validation.'
        );
    }

    public function testCompileWritesOneImmutableContentAddressedArtifact()
    {
        $deployment = $this->deployment();

        $first = $deployment->compile();
        $second = $deployment->compile();

        $this->assertTrue($first['created']);
        $this->assertFalse($second['created']);
        $this->assertSame($first['artifact'], $second['artifact']);
        $this->assertSame($first['release_hash'] . '.php', basename($first['artifact']));
        $this->assertSame($first['release_hash'], (new DefinitionRegistryArtifact())->load(
            $first['artifact']
        )['release_hash']);
        $this->assertCount(1, glob($this->fixtureRoot . '/artifacts/*'));
    }

    public function testCompileNeverOverwritesAnExistingContentAddressedArtifact()
    {
        $deployment = $this->deployment();
        $releaseHash = $deployment->validate()['release_hash'];
        $path = $this->fixtureRoot . '/artifacts/' . $releaseHash . '.php';
        $conflict = (new \MODX\Revolution\Definition\DefinitionManifestCompiler())->compile([]);
        $artifact = new DefinitionRegistryArtifact();
        $artifact->write($path, $conflict);

        $this->assertToolFailure(
            static fn() => $deployment->compile(),
            5,
            'artifact-conflict',
            'Compile must not overwrite an existing content-addressed artifact.'
        );
        $this->assertSame($conflict, $artifact->load($path));
    }

    public function testListAndExplainExposeCanonicalProvenanceAndDecision()
    {
        $deployment = $this->deployment();

        $listed = $deployment->list(null, 'snippet');
        $explained = $deployment->explain(null, 'snippet', 'DeploySnippet');

        $this->assertCount(1, $listed['definitions']);
        $this->assertSame('disk:acme/deployment:snippet:DeploySnippet', $listed['definitions'][0]['key']);
        $this->assertSame('disk-only', $explained['decision']['reason']);
        $this->assertSame('disk', $explained['winner']);
        $this->assertFalse($explained['candidates']['database']);
        $this->assertSame('elements/DeploySnippet.php', $explained['definition']['file']);
    }

    public function testExplainReportsADatabaseOnlyDecision()
    {
        $name = 'DatabaseOnlyDeployment' . bin2hex(random_bytes(5));
        $snippet = $this->modx->newObject(modSnippet::class);
        $snippet->set('name', $name);
        $snippet->setContent('return "database";');
        $this->assertTrue($snippet->save());

        try {
            $explained = $this->deployment()->explain(null, 'snippet', $name);

            $this->assertTrue($explained['candidates']['database']);
            $this->assertFalse($explained['candidates']['disk']);
            $this->assertSame('database-only', $explained['decision']['reason']);
            $this->assertSame('database', $explained['winner']);
            $this->assertNull($explained['definition']);
        } finally {
            $snippet->remove();
        }
    }

    public function testWarmRejectsAnActiveArtifactThatDoesNotMatchTheConfiguredRelease()
    {
        $compiled = $this->deployment()->compile();
        file_put_contents($this->fixtureRoot . '/elements/DeployChunk.html', 'Changed deployment chunk');
        $deployment = $this->deployment([
            'definition_registry_artifact' => $compiled['artifact'],
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->warm(),
            5,
            'active-hash-mismatch',
            'Warm must reject an active artifact from another release.'
        );
    }

    public function testSelectedArtifactMustRemainInsideTheReleaseOwnedDirectory()
    {
        $compiled = $this->deployment()->compile();
        $outsideArtifact = $this->fixtureRoot . '/' . basename($compiled['artifact']);
        copy($compiled['artifact'], $outsideArtifact);
        $deployment = $this->deployment([
            'definition_registry_artifact' => $outsideArtifact,
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->list(),
            5,
            'active-artifact-outside-directory',
            'The selected artifact must remain inside its release-owned directory.'
        );
    }

    public function testMutableAliasConfiguredArtifactIsRejectedByWarmAndHash()
    {
        $compiled = $this->deployment()->compile();
        $alias = $this->fixtureRoot . '/artifacts/current.php';
        $this->assertTrue(symlink($compiled['artifact'], $alias));
        $deployment = $this->deployment([
            'definition_registry_artifact' => $alias,
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->warm(),
            5,
            'active-path-not-content-addressed',
            'Warm must reject a configured artifact path that is not content-addressed.'
        );
        $this->assertToolFailure(
            static fn() => $deployment->hash(),
            5,
            'active-path-not-content-addressed',
            'Hash must reject a configured artifact path that is not content-addressed.'
        );
    }

    public function testContentAddressedSymlinkConfiguredArtifactIsRejectedByWarmAndHash()
    {
        $compiled = $this->deployment()->compile();
        $alias = $this->fixtureRoot . '/artifacts/' . str_repeat('b', 64) . '.php';
        $this->assertTrue(symlink($compiled['artifact'], $alias));
        $deployment = $this->deployment([
            'definition_registry_artifact' => $alias,
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->warm(),
            5,
            'active-artifact-symlink',
            'Warm must reject a symlinked configured artifact path.'
        );
        $this->assertToolFailure(
            static fn() => $deployment->hash(),
            5,
            'active-artifact-symlink',
            'Hash must reject a symlinked configured artifact path.'
        );
    }

    public function testMissingConfiguredArtifactReportsNotFound()
    {
        $deployment = $this->deployment([
            'definition_registry_artifact' => $this->fixtureRoot . '/artifacts/' . str_repeat('c', 64) . '.php',
        ]);

        $this->assertToolFailure(
            static fn() => $deployment->list(),
            5,
            'active-artifact-not-found',
            'A missing configured artifact must be reported as not found, not outside-directory.'
        );
        $this->assertToolFailure(
            static fn() => $deployment->warm(),
            5,
            'active-artifact-not-found',
            'Warm must report a missing configured artifact as not found.'
        );
    }

    public function testWarmClearsResourcesAndWarmsTheActiveRelease()
    {
        $compiled = $this->deployment()->compile();
        $catalog = (new DefinitionRegistryArtifact())->load($compiled['artifact']);
        $definition = $catalog['definitions'][modSnippet::class]['deploysnippet'];
        $this->warmedScriptCacheKey = DefinitionRegistry::scriptCacheKey(
            $definition['key'],
            $definition['content_hash']
        );
        $deployment = $this->deployment([
            'definition_registry_artifact' => $compiled['artifact'],
        ]);

        $result = $deployment->warm();
        $hash = $deployment->hash();

        $this->assertSame($compiled['release_hash'], $result['release_hash']);
        $this->assertTrue($result['resource_cache_cleared']);
        $this->assertSame(1, $result['scripts_warmed']);
        $this->assertContains('web', $result['contexts']);
        $this->assertTrue($hash['matches_active']);
        $this->assertTrue($hash['matches_warmed']);
    }

    public function testWarmFailsWhenAContextCacheCannotBeWritten()
    {
        $compiled = $this->deployment()->compile();
        $contextCacheKey = 'definition-context-failure-' . bin2hex(random_bytes(5));
        $previousKey = $this->modx->getOption('cache_context_settings_key', null, 'context_settings');
        $previousHandler = $this->modx->getOption(
            'cache_context_settings_handler',
            null,
            $this->modx->getOption('cache_handler')
        );
        $this->modx->setOption('cache_context_settings_key', $contextCacheKey);
        $this->modx->setOption('cache_context_settings_handler', FailingDefinitionContextCache::class);

        try {
            $deployment = $this->deployment([
                'definition_registry_artifact' => $compiled['artifact'],
            ]);
            $this->assertToolFailure(
                static fn() => $deployment->warm(),
                5,
                'cache-partition-unavailable',
                'Warm must fail when context caches cannot be written.'
            );
        } finally {
            $this->modx->setOption('cache_context_settings_key', $previousKey);
            $this->modx->setOption('cache_context_settings_handler', $previousHandler);
        }
    }

    public function testCliUsesDeterministicJsonAndStableUsageExitCode()
    {
        $cli = new DefinitionRegistryCli($this->deployment());
        $stdout = fopen('php://memory', 'w+');
        $stderr = fopen('php://memory', 'w+');

        $success = $cli->run(['validate'], $stdout, $stderr);
        $usageFailure = $cli->run(['explain', '--type', 'snippet'], $stdout, $stderr);
        rewind($stdout);
        rewind($stderr);
        $successDocument = json_decode(stream_get_contents($stdout), true);
        $errorDocument = json_decode(stream_get_contents($stderr), true);

        $this->assertSame(0, $success);
        $this->assertTrue($successDocument['ok']);
        $this->assertSame('validate', $successDocument['command']);
        $this->assertSame(2, $usageFailure);
        $this->assertFalse($errorDocument['ok']);
        $this->assertSame('invalid-selector', $errorDocument['error']['code']);
    }

    public function testCliReportsAnStdoutWriteFailureOnStderr()
    {
        $deployment = $this->getMockBuilder(DefinitionRegistryDeployment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validate'])
            ->getMock();
        $deployment->method('validate')->willReturn(['release_hash' => hash('sha256', 'stdout-failure')]);
        $cli = new DefinitionRegistryCli($deployment);
        $stdout = fopen('php://memory', 'r');
        $stderr = fopen('php://memory', 'w+');

        $status = $cli->run(['validate'], $stdout, $stderr);
        rewind($stderr);
        $error = json_decode(stream_get_contents($stderr), true);

        $this->assertSame(5, $status);
        $this->assertSame('stdout-write-failed', $error['error']['code']);
        $this->assertSame('Could not write command output to stdout.', $error['error']['message']);
    }

    public function testValidateIncludesOrderedRuntimeDiagnostics()
    {
        $name = 'DeploymentCollision' . bin2hex(random_bytes(5));
        $snippet = $this->modx->newObject(modSnippet::class);
        $snippet->set('name', $name);
        $this->assertTrue($snippet->save());
        file_put_contents($this->manifest, str_replace(
            "'DeploySnippet' =>",
            "'{$name}' =>",
            $this->manifestSource()
        ));

        try {
            $result = $this->deployment()->validate();
            $this->assertSame('database-element-collision', $result['diagnostics'][0]['code']);
            $this->assertSame('disk:acme/deployment:snippet:' . $name, $result['diagnostics'][0]['key']);
        } finally {
            $snippet->remove();
        }
    }

    public function testValidateIncludesEventMetadataConflictDiagnostics()
    {
        $eventName = 'DeploymentMetadata' . bin2hex(random_bytes(5));
        $event = $this->modx->newObject(modEvent::class);
        $event->set('name', $eventName);
        $event->set('service', 3);
        $event->set('groupname', 'Database');
        $this->assertTrue($event->save());
        file_put_contents($this->manifest, str_replace(
            "'events' => [],",
            "'events' => ['{$eventName}' => ['service' => 'mgr', 'group' => 'Disk']],",
            $this->manifestSource()
        ));

        try {
            $result = $this->deployment()->validate();
            $this->assertSame('event-metadata-conflict', $result['diagnostics'][0]['code']);
            $this->assertSame('disk:acme/deployment:event:' . $eventName, $result['diagnostics'][0]['key']);
            $this->assertSame('warning', $result['diagnostics'][0]['severity']);
        } finally {
            $event->remove();
        }
    }

    public function testUninitializedModxHonorsTrustedStrictValidation()
    {
        $eventName = 'DeploymentStrict' . bin2hex(random_bytes(5));
        $event = $this->modx->newObject(modEvent::class);
        $event->set('name', $eventName);
        $event->set('service', 3);
        $event->set('groupname', 'Database');
        $this->assertTrue($event->save());
        file_put_contents($this->manifest, str_replace(
            "'events' => [],",
            "'events' => ['{$eventName}' => ['service' => 'mgr']],",
            $this->manifestSource()
        ));
        $modx = \MODX\Revolution\modX::getInstance(
            'definition-strict-uninitialized-' . bin2hex(random_bytes(5)),
            ['definition_strict_validation' => true],
            true
        );
        $deployment = new DefinitionRegistryDeployment([
            'definition_manifests' => [$this->manifest],
            'definition_registry_artifact' => '',
            'definition_registry_artifact_dir' => $this->fixtureRoot . '/artifacts',
        ], $modx);

        try {
            $this->assertToolFailure(
                static fn() => $deployment->validate(),
                3,
                'runtime-validation-failed',
                'Uninitialized deployment tooling must honor trusted strict validation.'
            );
        } finally {
            $event->remove();
        }
    }

    public function testUnknownExplainKeyIsAUsageFailure()
    {
        $this->assertToolFailure(
            fn() => $this->deployment()->explain('disk:acme/deployment:snippet:Missing', null, null),
            2,
            'definition-not-found',
            'An unknown source-qualified key is command usage, not release validation.'
        );
    }

    public function testValidationFailsOperationallyWhenTheDatabaseIsUnavailable()
    {
        $modx = $this->getMockBuilder(\MODX\Revolution\modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['connect'])
            ->getMock();
        $modx->method('connect')->willReturn(false);
        $deployment = new DefinitionRegistryDeployment([], $modx);

        $this->assertToolFailure(
            static fn() => $deployment->validate(),
            5,
            'database-unavailable',
            'Database-dependent validation must not report a successful empty collision check.'
        );
    }

    public function testValidationFailsWhenADatabasePresenceQueryCannotExecute()
    {
        $modx = $this->getMockBuilder(\MODX\Revolution\modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'connect',
                'getDefinitionEventDispatcher',
                'getTableName',
                'prepare',
                'setDefinitionRegistry',
            ])
            ->getMock();
        $modx->method('connect')->willReturn(true);
        $modx->method('getDefinitionEventDispatcher')->willReturn(
            new EventDispatcher($modx, new DefinitionRegistry())
        );
        $modx->method('getTableName')->willReturn('modx_site_snippets');
        $modx->method('prepare')->willReturn(false);
        $deployment = new DefinitionRegistryDeployment([
            'definition_manifests' => [$this->manifest],
            'definition_registry_artifact' => '',
            'definition_registry_artifact_dir' => $this->fixtureRoot . '/artifacts',
        ], $modx);

        $this->assertToolFailure(
            static fn() => $deployment->validate(),
            5,
            'database-presence-unavailable',
            'A failed database presence query must not be reported as no collision.'
        );
    }

    public function testEventOnlyValidationFailsWhenEventMetadataCannotBeQueried()
    {
        $manifest = $this->fixtureRoot . '/event-only-manifest.php';
        file_put_contents($manifest, <<<'PHP'
<?php

return [
    'schema' => 1,
    'package' => 'acme/event-only',
    'root' => __DIR__,
    'elements' => [],
    'events' => ['DeploymentEvent' => ['service' => 'web']],
    'listeners' => [],
];
PHP);
        $modx = $this->getMockBuilder(\MODX\Revolution\modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['connect', 'getTableName', 'prepare', 'setDefinitionRegistry'])
            ->getMock();
        $modx->method('connect')->willReturn(true);
        $modx->method('getTableName')->willReturn('modx_event');
        $modx->method('prepare')->willReturn(false);
        $deployment = new DefinitionRegistryDeployment([
            'definition_manifests' => [$manifest],
            'definition_registry_artifact' => '',
            'definition_registry_artifact_dir' => $this->fixtureRoot . '/artifacts',
        ], $modx);

        $this->assertToolFailure(
            static fn() => $deployment->validate(),
            5,
            'database-presence-unavailable',
            'An event-only release must not treat a failed metadata query as a row-less event.'
        );
    }

    public function testExplainFailsWhenItsDatabasePresenceQueryCannotExecute()
    {
        $modx = $this->getMockBuilder(\MODX\Revolution\modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'connect',
                'getDefinitionRegistry',
                'getTableName',
                'prepare',
                'setDefinitionRegistry',
            ])
            ->getMock();
        $modx->method('connect')->willReturn(true);
        $modx->method('getDefinitionRegistry')->willReturn(new DefinitionRegistry());
        $modx->method('getTableName')->willReturn('modx_site_snippets');
        $modx->method('prepare')->willReturn(false);
        $deployment = new DefinitionRegistryDeployment([
            'definition_manifests' => [],
            'definition_registry_artifact' => '',
            'definition_registry_artifact_dir' => $this->fixtureRoot . '/artifacts',
        ], $modx);

        $this->assertToolFailure(
            static fn() => $deployment->explain(null, 'snippet', 'DatabaseOnly'),
            5,
            'database-presence-unavailable',
            'explain must not report a failed database lookup as an absent definition.'
        );
    }

    public function testCliBootstrapFailureIsOneJsonErrorDocument()
    {
        $binDirectory = $this->fixtureRoot . '/isolated/bin';
        mkdir($binDirectory, 0777, true);
        $script = $binDirectory . '/modx-definitions';
        copy(MODX_BASE_PATH . 'bin/modx-definitions', $script);
        $process = proc_open(
            [PHP_BINARY, $script, 'validate'],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );
        $this->assertIsResource($process);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $this->assertSame(5, proc_close($process));
        $this->assertSame('', $stdout);
        $document = json_decode($stderr, true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($document['ok']);
        $this->assertSame('bootstrap-failed', $document['error']['code']);
    }

    public function testCliBootstrapFailureWithInvalidUtf8StillProducesJson()
    {
        $root = $this->fixtureRoot . '/isolated-' . "\xFF";
        $binDirectory = $root . '/bin';
        mkdir($binDirectory, 0777, true);
        $script = $binDirectory . '/modx-definitions';
        copy(MODX_BASE_PATH . 'bin/modx-definitions', $script);
        $process = proc_open(
            [PHP_BINARY, $script, 'validate'],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );
        $this->assertIsResource($process);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $this->assertSame(5, proc_close($process));
        $this->assertSame('', $stdout);
        $document = json_decode($stderr, true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($document['ok']);
        $this->assertSame('bootstrap-failed', $document['error']['code']);
    }

    /**
     * The CLI must snapshot trusted definition config before database settings can
     * merge, then initialize MODX before any deployment command touches a cache
     * backend. A behavioral subprocess test is impractical here: bin/modx-definitions
     * bootstraps with the site's real MODX_CONFIG_KEY and database, not this
     * harness's isolated test fixture, so influencing it via a database-stored
     * cache_handler setting would mutate the live site. This locks the ordering
     * invariant structurally instead.
     */
    public function testCliBootstrapInitializesModxAfterTheTrustedConfigSnapshot()
    {
        $script = file_get_contents(MODX_BASE_PATH . 'bin/modx-definitions');

        $snapshot = strpos($script, 'getTrustedDefinitionConfig');
        $initialize = strpos($script, '$modx->initialize(');
        $deployment = strpos($script, 'new DefinitionRegistryDeployment(');

        $this->assertNotFalse($snapshot, 'The CLI must snapshot trusted definition config.');
        $this->assertNotFalse($initialize, 'The CLI must initialize MODX so database cache settings merge.');
        $this->assertNotFalse($deployment, 'The CLI must construct the deployment tool.');
        $this->assertGreaterThan(
            $snapshot,
            $initialize,
            'MODX must initialize only after the trusted definition config snapshot.'
        );
        $this->assertLessThan(
            $deployment,
            $initialize,
            'MODX must initialize before any deployment command can target a cache backend.'
        );
    }

    private function deployment(array $config = []): DefinitionRegistryDeployment
    {
        return new DefinitionRegistryDeployment($config + [
            'definition_manifests' => [$this->manifest],
            'definition_registry_artifact' => '',
            'definition_registry_artifact_dir' => $this->fixtureRoot . '/artifacts',
        ], $this->modx);
    }

    private function assertToolFailure(
        callable $operation,
        int $exitStatus,
        string $errorCode,
        string $failureMessage
    ): void {
        try {
            $operation();
            $this->fail($failureMessage);
        } catch (DefinitionRegistryToolException $exception) {
            $this->assertSame($exitStatus, $exception->getExitStatus());
            $this->assertSame($errorCode, $exception->getErrorCode());
        }
    }

    private function manifestSource(): string
    {
        return <<<'PHP'
<?php

return [
    'schema' => 1,
    'package' => 'acme/deployment',
    'root' => __DIR__,
    'elements' => [
        'snippets' => [
            'DeploySnippet' => [
                'file' => 'elements/DeploySnippet.php',
            ],
        ],
        'chunks' => [
            'DeployChunk' => [
                'file' => 'elements/DeployChunk.html',
            ],
        ],
    ],
    'events' => [],
    'listeners' => [],
];
PHP;
    }
}
