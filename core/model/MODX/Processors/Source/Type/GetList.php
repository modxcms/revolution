<?php

namespace MODX\Processors\Source\Type;

use xPDO\Om\xPDOObject;
use MODX\Processors\modProcessor;
use MODX\Sources\modMediaSource;

/**
 * Gets a list of media source types
 *
 * @package modx
 * @subpackage processors.source.type
 */
class GetList extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('sources');
    }


    public function getLanguageTopics()
    {
        return ['source'];
    }


    public function process()
    {
        $this->modx->setPackage('Sources', MODX_CORE_PATH . 'model/MODX/');
        $descendants = $this->modx->getDescendants('MODX\modMediaSource');

        $list = [];
        foreach ($descendants as $descendant) {
            /** @var xPDOObject|modMediaSource $obj */
            if (!$obj = $this->modx->newObject($descendant)) {
                continue;
            }

            $list[] = [
                'class' => $descendant,
                'name' => $obj->getTypeName(),
                'description' => $obj->getTypeDescription(),
            ];
        }

        return $this->outputArray($list);
    }
}