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
 * Remove a system setting
 *
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class modSystemSettingsRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('setting','namespace');
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    public function afterRemove() {
        $this->removeRelatedLexiconEntries();
        $this->modx->reloadConfig();
        return true;
    }

    /**
     * Remove all Lexicon Entries related to the setting
     * @return void
     */
    public function removeRelatedLexiconEntries() {
        /** @var modLexiconEntry $entry */
        $entry = $this->modx->getObject('modLexiconEntry',array(
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_'.$this->object->get('key'),
        ));
        if (!empty($entry)) {
            $entry->remove();
        }

        /** @var modLexiconEntry $description */
        $description = $this->modx->getObject('modLexiconEntry',array(
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_'.$this->object->get('key').'_desc',
        ));
        if (!empty($description)) {
            $description->remove();
        }
    }
}
return 'modSystemSettingsRemoveProcessor';
