<?php

namespace MODX\Revolution\Processors\System\Definition;

use MODX\Revolution\Definition\DefinitionRegistryInspector;
use MODX\Revolution\Processors\Processor;
use InvalidArgumentException;

/** Lists active disk-native registry records for Manager inspection without mutation. */
class GetList extends Processor
{
    public $permission = 'view_element';

    public function checkPermissions()
    {
        return $this->modx->hasPermission($this->permission);
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 20,
            'sort' => 'key',
            'dir' => 'ASC',
            'kind' => 'elements',
            'type' => '',
            'package' => '',
            'query' => '',
        ]);

        return true;
    }

    public function process()
    {
        try {
            $data = (new DefinitionRegistryInspector($this->modx))->list($this->getProperties());

            return $this->outputArray($data['results'], $data['total']);
        } catch (InvalidArgumentException $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
