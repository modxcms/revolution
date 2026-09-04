<?php

namespace MODX\Revolution\Definition;

use RuntimeException;
use Throwable;

class DefinitionRegistryArtifact
{
    /**
     * Load a compiled catalog. A caller that has already attested the exact file
     * identity may supply that identity and release hash to avoid recalculating
     * the canonical release hash on every warm request.
     */
    public function load(string $path, ?string $validatedIdentity = null, ?string $validatedReleaseHash = null): array
    {
        [$realPath, $identity] = $this->resolve($path);

        return $this->loadResolved(
            $realPath,
            $validatedIdentity !== null && hash_equals($validatedIdentity, $identity)
                ? $validatedReleaseHash
                : null
        );
    }

    /**
     * Load a catalog from an already resolved real path. When the caller proved
     * on a prior request that this exact file identity validated against the
     * supplied release hash, only the attested hash is re-checked; otherwise the
     * catalog undergoes full canonical hash validation.
     */
    public function loadResolved(string $realPath, ?string $validatedReleaseHash = null): array
    {
        try {
            $catalog = @require $realPath;
        } catch (Throwable $exception) {
            throw new RuntimeException("Compiled definition registry is invalid: {$realPath}", 0, $exception);
        }
        if (
            $validatedReleaseHash !== null
            && is_array($catalog)
            && is_string($catalog['release_hash'] ?? null)
            && hash_equals($validatedReleaseHash, $catalog['release_hash'])
        ) {
            try {
                DefinitionRegistry::assertValidCatalog($catalog, true);
            } catch (RuntimeException $exception) {
                throw new RuntimeException("Compiled definition registry is invalid: {$realPath}", 0, $exception);
            }

            return $catalog;
        }
        if (!$this->isValidCatalog($catalog)) {
            throw new RuntimeException("Compiled definition registry is invalid: {$realPath}");
        }

        return $catalog;
    }

    public function identity(string $path): string
    {
        [, $identity] = $this->resolve($path);

        return $identity;
    }

    public function resolveIdentity(string $path): array
    {
        return $this->resolve($path);
    }

    /**
     * Whether a path's basename is content-addressed as `<64-hex>.php` — for the
     * exact release hash when one is supplied, or any release hash otherwise.
     */
    public static function isContentAddressedBasename(string $path, ?string $expectedHash = null): bool
    {
        $basename = basename($path);
        if ($expectedHash !== null) {
            return preg_match('/\A[a-f0-9]{64}\z/', $expectedHash) === 1
                && $basename === $expectedHash . '.php';
        }

        return preg_match('/\A[a-f0-9]{64}\.php\z/', $basename) === 1;
    }

    public function write(string $path, array $catalog): void
    {
        $temporary = $this->writeTemporaryArtifact($path, $catalog);
        if (!@rename($temporary, $path)) {
            @unlink($temporary);
            throw new RuntimeException("Could not atomically write compiled definition registry: {$path}");
        }
    }

    public function writeImmutable(string $path, array $catalog): bool
    {
        $releaseHash = $catalog['release_hash'] ?? null;
        if (!is_string($releaseHash) || !self::isContentAddressedBasename($path, $releaseHash)) {
            throw new RuntimeException(
                "Immutable definition registry path must be content-addressed by its release hash: {$path}"
            );
        }
        $temporary = $this->writeTemporaryArtifact($path, $catalog);
        if ($this->publishHardLink($temporary, $path)) {
            unlink($temporary);

            return true;
        }
        if ($this->publishAtomically($temporary, $path)) {
            @unlink($temporary);
            $published = $this->load($path);
            if (hash_equals($catalog['release_hash'], $published['release_hash'])) {
                return true;
            }

            throw new DefinitionRegistryArtifactConflictException(
                "Content-addressed definition registry already exists with another hash: {$path}"
            );
        }
        @unlink($temporary);
        if (is_link($path)) {
            throw new RuntimeException("Content-addressed definition registry path must not be a symlink: {$path}");
        }
        if (is_file($path)) {
            $existing = $this->load($path);
            if (hash_equals($catalog['release_hash'], $existing['release_hash'])) {
                return false;
            }

            throw new DefinitionRegistryArtifactConflictException(
                "Content-addressed definition registry already exists with another hash: {$path}"
            );
        }

        throw new RuntimeException("Could not atomically publish immutable definition registry: {$path}");
    }

    private function writeTemporaryArtifact(string $path, array $catalog): string
    {
        if (!$this->isValidCatalog($catalog)) {
            throw new RuntimeException('Cannot write an invalid definition registry catalog.');
        }
        $directory = dirname($path);
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException("Compiled definition registry directory is not writable: {$directory}");
        }
        $temporary = @tempnam($directory, '.definition-registry-');
        if ($temporary === false) {
            throw new RuntimeException("Could not create a temporary registry artifact in {$directory}");
        }
        $content = "<?php\n\nreturn " . var_export($catalog, true) . ";\n";
        if (@file_put_contents($temporary, $content, LOCK_EX) === false) {
            @unlink($temporary);
            throw new RuntimeException("Could not write temporary definition registry: {$path}");
        }

        return $temporary;
    }

    private function isValidCatalog(mixed $catalog): bool
    {
        if (!$this->hasValidStructure($catalog)) {
            return false;
        }

        return hash_equals($catalog['release_hash'], DefinitionManifestCompiler::calculateReleaseHash($catalog));
    }

    private function hasValidStructure(mixed $catalog): bool
    {
        if (
            !(
            is_array($catalog)
            && ($catalog['schema'] ?? null) === 1
            && isset($catalog['definitions'], $catalog['events'], $catalog['listeners'], $catalog['inventory'])
            && is_array($catalog['definitions'])
            && is_array($catalog['events'])
            && is_array($catalog['listeners'])
            && is_array($catalog['inventory'])
            && isset($catalog['release_hash'])
            && is_string($catalog['release_hash'])
            && preg_match('/\A[a-f0-9]{64}\z/', $catalog['release_hash'])
            )
        ) {
            return false;
        }
        foreach ($catalog['definitions'] as $definitions) {
            if (!is_array($definitions)) {
                return false;
            }
            foreach ($definitions as $definition) {
                if (
                    !is_array($definition)
                    || array_key_exists('override_database', $definition)
                    || array_key_exists('override_requested', $definition)
                    || array_key_exists('override_authorized', $definition)
                ) {
                    return false;
                }
            }
        }

        try {
            DefinitionRegistry::assertValidCatalog($catalog, true);
        } catch (RuntimeException) {
            return false;
        }

        return true;
    }

    /** @return array{string, string} */
    private function resolve(string $path): array
    {
        $realPath = realpath($path);
        if ($realPath === false || !is_file($realPath) || !is_readable($realPath)) {
            throw new RuntimeException("Compiled definition registry is not readable: {$path}");
        }
        clearstatcache(true, $realPath);
        $stat = @stat($realPath);
        if ($stat === false) {
            throw new RuntimeException("Could not identify compiled definition registry: {$path}");
        }

        return [$realPath, hash('sha256', implode(':', [
            $realPath,
            $stat['dev'] ?? '',
            $stat['ino'] ?? '',
            $stat['size'] ?? '',
            $stat['mtime'] ?? '',
            $stat['ctime'] ?? '',
        ]))];
    }

    protected function publishHardLink(string $temporary, string $path): bool
    {
        return @link($temporary, $path);
    }

    /**
     * Fallback for filesystems without hard-link publication. A rename keeps the
     * final path atomic; content addressing makes a same-path publisher race benign.
     */
    protected function publishAtomically(string $temporary, string $path): bool
    {
        if (!self::isContentAddressedBasename($path)) {
            throw new RuntimeException(
                "Immutable definition registry fallback requires a content-addressed path: {$path}"
            );
        }
        $lock = @fopen(dirname($path) . '/.definition-registry-publish.lock', 'c');
        if ($lock === false || !flock($lock, LOCK_EX)) {
            if (is_resource($lock)) {
                fclose($lock);
            }
            throw new RuntimeException("Could not lock immutable definition registry publication: {$path}");
        }
        try {
            if (is_file($path) || is_link($path)) {
                return false;
            }

            return @rename($temporary, $path);
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
