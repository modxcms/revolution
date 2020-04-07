<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access;

use MODX\Revolution\modAccess;
use MODX\Revolution\modContext;
use MODX\Revolution\Processors\ModelProcessor;
use MODX\Revolution\modResourceGroup;

/**
 * Gets a node list of ACLs
 * @param string $id The parent ID.
 * @package MODX\Revolution\Processors\Security\Access
 */
class GetNodes extends ModelProcessor
{
    public $permission = 'access_permissions';
    public $languageTopics = ['access'];
    public $defaultSortField = 'target';

    /**
     * @return bool|string|null
     */
    public function initialize()
    {
        if (!$this->getProperty($this->primaryKeyField)) {
            return $this->modx->lexicon($this->objectType . '_type_err_ns');
        }

        return parent::initialize();
    }

    /**
     * @return mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $targetAttr = explode('_', $this->getProperty($this->primaryKeyField));
        $targetClass = count($targetAttr) === 3 ? $targetAttr[1] : '';

        $da = [];
        if (empty($targetClass)) {
            $da[] = [
                'text' => $this->modx->lexicon('contexts'),
                'id' => 'n_modContext_0',
                'cls' => 'icon-context folder',
                'target' => '0',
                'target_cls' => modContext::class,
                'leaf' => 0,
                'type' => 'modAccessContext',
            ];
            $da[] = [
                'text' => $this->modx->lexicon('resource_groups'),
                'id' => 'n_modResourceGroup_0',
                'cls' => 'icon-resourcegroup folder',
                'target' => '0',
                'target_cls' => modResourceGroup::class,
                'leaf' => 0,
                'type' => 'modAccessResourceGroup',
            ];
        } else {
            $targets = $this->modx->getCollection($targetClass);
            /** @var modAccess $target */
            foreach ($targets as $targetKey => $target) {
                $da[] = [
                    'text' => $targetClass === modContext::class ? $target->get('key') : $target->get('name'),
                    'id' => 'n_' . $targetClass . '_' . $targetKey,
                    'leaf' => true,
                    'type' => 'modAccess' . substr($targetClass, 3), // todo: remove full path?
                    'cls' => 'file',
                ];
            }
        }

        return $this->modx->toJSON($da);
    }
}
