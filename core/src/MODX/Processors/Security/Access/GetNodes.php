<?php

namespace MODX\Processors\Security\Access;

use MODX\modAccess;
use MODX\Processors\modObjectProcessor;

/**
 * Gets a node list of ACLs
 *
 * @param string $id The parent ID.
 *
 * @package modx
 * @subpackage processors.security.access.target
 */
class GetNodes extends modObjectProcessor
{
    public $permission = 'access_permissions';
    public $languageTopics = ['access'];
    public $defaultSortField = 'target';


    public function initialize()
    {
        if (!$this->getProperty($this->primaryKeyField)) {
            return $this->modx->lexicon($this->objectType . '_type_err_ns');
        }

        return parent::initialize();
    }


    public function process()
    {
        $targetAttr = explode('_', $this->getProperty($this->primaryKeyField));
        $targetClass = count($targetAttr) == 3 ? $targetAttr[1] : '';

        $da = [];
        if (empty($targetClass)) {
            $da[] = [
                'text' => $this->modx->lexicon('contexts'),
                'id' => 'n_modContext_0',
                'cls' => 'icon-context folder',
                'target' => '0',
                'target_cls' => 'modContext',
                'leaf' => 0,
                'type' => 'modAccessContext',
            ];
            $da[] = [
                'text' => $this->modx->lexicon('resource_groups'),
                'id' => 'n_modResourceGroup_0',
                'cls' => 'icon-resourcegroup folder',
                'target' => '0',
                'target_cls' => 'modResourceGroup',
                'leaf' => 0,
                'type' => 'modAccessResourceGroup',
            ];
        } else {
            $targets = $this->modx->getCollection($targetClass);
            /** @var modAccess $target */
            foreach ($targets as $targetKey => $target) {
                switch ($targetClass) {
                    case 'modContext' :
                        $nodeText = $target->get('key');
                        break;
                    default :
                        $nodeText = $target->get('name');
                        break;
                }
                $da[] = [
                    'text' => $nodeText,
                    'id' => 'n_' . $targetClass . '_' . $targetKey,
                    'leaf' => true,
                    'type' => 'modAccess' . substr($targetClass, 3),
                    'cls' => 'file',
                ];
            }
        }

        return json_encode($da);
    }
}

