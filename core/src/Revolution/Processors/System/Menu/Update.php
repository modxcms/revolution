<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Menu;

use MODX\Revolution\modMenu;
use MODX\Revolution\Processors\Model\UpdateProcessor;

/**
 * Update a menu item
 * @param string $text The text of the menu button.
 * @param string $icon
 * @param string $params (optional) Any parameters to be sent over GET when clicking the menu
 * @param string $handler (optional) A custom javascript handler for the menu item
 * @param integer $action_id (optional) The ID of the action. Defaults to 0.
 * @param integer $parent (optional) The parent menu to create from. Defaults to 0.
 * @package MODX\Revolution\Processors\System\Menu
 */
class Update extends UpdateProcessor
{
    public $classKey = modMenu::class;
    public $objectType = 'menu';
    public $primaryKeyField = 'previous_text';
    public $languageTopics = ['action', 'menu'];
    public $permission = 'menus';
    public $isRename = false;

    /**
     * @return bool|string|null
     */
    public function beforeSet()
    {
        // Setup to allow PK change
        $oldName = $this->getProperty('previous_text');
        $newName = $this->getProperty('text');
        if (empty($newName)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        $this->setProperty('newName', $newName);

        if ($oldName && $oldName !== $newName) {
            $this->isRename = true;
            $this->setProperty('text', $oldName);
        }

        /* verify action */
        $action_id = $this->getProperty('action_id');
        if (!isset($action_id)) {
            return $this->modx->lexicon('action_err_ns');
        }

        /* verify parent */
        $parent = $this->getProperty('parent');
        if (!empty($parent)) {
            $parent = $this->modx->getObject($this->classKey, $parent);
            if ($parent === null) {
                return $this->modx->lexicon($this->objectType . '_parent_err_nf');
            }
        }

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('action', $this->getProperty('action_id'));
        $this->object->set('text', $this->getProperty('text'));

        return parent::beforeSave();
    }

    /**
     * @return bool|string|null
     */
    public function afterSave()
    {
        /* if changing key */
        if ($this->isRename) {
            $newName = $this->getProperty('newName');
            if ($this->doesAlreadyExist(['text' => $newName])) {
                return $this->modx->lexicon($this->objectType . '_err_ae');
            }

            $children = $this->modx->getIterator($this->classKey, [
                'parent' => $this->object->get('text'),
            ]);

            /** @var modMenu $newMenu */
            $newMenu = $this->modx->newObject($this->classKey);
            $newMenu->fromArray($this->object->toArray());
            $newMenu->set('text', $newName);

            if ($newMenu->save()) {
                /** @type modMenu $child */
                foreach ($children as $child) {
                    $child->set('parent', $newName);
                    $child->save();
                }
                $this->object->remove();
                $this->object = $newMenu;
            }

        }

        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'menu' => [],
        ]);

        return parent::afterSave();
    }
}
