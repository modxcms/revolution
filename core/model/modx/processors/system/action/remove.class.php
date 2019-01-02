<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Removes an action
 *
 * @param integer $id The ID of the action
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu');
    public $permission = 'actions';
    public $objectType = 'action';
}
return 'modActionRemoveProcessor';
