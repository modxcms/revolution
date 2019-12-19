<?php


namespace MODX\Revolution\Services;


use Psr\Container\ContainerInterface;

class Container extends \Pimple\Container implements ContainerInterface
{
    public function add($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if ($this->has($id)) {
            try {
                return $this->offsetGet($id);
            } catch (\Exception $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }
        throw new NotFoundException("Dependency not found with key {$id}.");
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
