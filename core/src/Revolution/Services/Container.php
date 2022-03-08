<?php

namespace MODX\Revolution\Services;

use Exception;
use Psr\Container\ContainerInterface;

class Container extends \Pimple\Container implements ContainerInterface
{
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
