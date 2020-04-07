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
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Creates a menu item
 * @param string $text The text of the menu button.
 * @param string $icon
 * @param string $params (optional) Any parameters to be sent over GET when clicking the menu
 * @param string $handler (optional) A custom javascript handler for the menu item
 * @param integer $action_id (optional) The ID of the action. Defaults to 0.
 * @param integer $parent (optional) The parent menu to create from. Defaults to 0.
 * @package MODX\Revolution\Processors\System\Menu
 */
class Create extends CreateProcessor
{
    public $classKey = modMenu::class;
    public $languageTopics = ['action', 'menu'];
    public $permission = 'menus';
    public $objectType = 'menu';
    public $primaryKeyField = 'text';

    /**
     * @return bool|string|null
     */
    public function beforeSet()
    {
        /* verify action */
        $action_id = $this->getProperty('action_id');
        if (!isset($action_id)) {
            return $this->modx->lexicon('action_err_ns');
        }

        $text = $this->getProperty('text');
        if (empty($text)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        /* verify parent */
        $parent = $this->getProperty('parent');
        if (!empty($parent)) {
            $parent = $this->modx->getObject($this->classKey, $parent);
            if ($parent === null) {
                return $this->modx->lexicon($this->objectType . '_parent_err_nf');
            }
        }

        if ($this->doesAlreadyExist(['text' => $text])) {
            return $this->modx->lexicon($this->objectType . '_err_ae');
        }

        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $menuIndex = $this->modx->getCount($this->classKey, ['parent' => $this->getProperty('parent')]);
        $this->object->set('menuindex', $menuIndex);
        $this->object->set('action', $this->getProperty('action_id'));
        $this->object->set('text', $this->getProperty('text'));

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'menu' => [],
        ]);

        return parent::afterSave();
    }

}
