<?php

namespace MODX\Revolution\Services;

use Exception;
use Psr\Container\ContainerInterface;

class Container extends \Pimple\Container implements ContainerInterface
{
    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * Get the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     *
     * @param Container|null $container
     * @return static
     */
    public static function setInstance(ContainerInterface $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Add an entry to the container.
     *
     * @param string $id
     * @param mixed $value
     * @return void
     */
    public function add(string $id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            try {
                return $this->offsetGet($id);
            } catch (Exception $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }
        throw new NotFoundException("Dependency not found with key {$id}.");
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->offsetExists($id);
    }
}
