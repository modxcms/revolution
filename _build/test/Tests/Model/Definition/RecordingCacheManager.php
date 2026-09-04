<?php

namespace MODX\Revolution\Tests\Model\Definition;

use MODX\Revolution\modCacheManager;
use MODX\Revolution\modX;
use xPDO\xPDO;

/**
 * In-memory cache manager double that records every set() with its lifetime.
 *
 * Entries are keyed by the cache partition (OPT_CACHE_KEY) and key, matching
 * how the definition facts cache addresses them. Construct with
 * $unavailable = true to simulate an unavailable cache backend whose get()
 * and set() both throw.
 */
class RecordingCacheManager extends modCacheManager
{
    /** @var array<string, mixed> */
    public array $entries = [];

    /** @var array<int, array{0: string, 1: int}> Recorded [key, lifetime] per set(). */
    public array $sets = [];

    private bool $unavailable;

    public function __construct(modX &$modx, bool $unavailable = false)
    {
        parent::__construct($modx);
        $this->unavailable = $unavailable;
    }

    public function get($key, $options = [])
    {
        if ($this->unavailable) {
            throw new \RuntimeException('cache unavailable');
        }

        return $this->entries[($options[xPDO::OPT_CACHE_KEY] ?? '') . ':' . $key] ?? null;
    }

    public function set($key, &$var, $lifetime = 0, $options = [])
    {
        if ($this->unavailable) {
            throw new \RuntimeException('cache unavailable');
        }
        $this->entries[($options[xPDO::OPT_CACHE_KEY] ?? '') . ':' . $key] = $var;
        $this->sets[] = [$key, $lifetime];

        return true;
    }

    public function setCount(): int
    {
        return count($this->sets);
    }

    /** @return array<int, int> The recorded lifetimes in set() order. */
    public function lifetimes(): array
    {
        return array_column($this->sets, 1);
    }
}
