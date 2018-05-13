<?php

namespace MODX\Processors\System\Settings;


use MODX\modLexiconEntry;
use MODX\Processors\modObjectRemoveProcessor;

/**
 * Remove a system setting
 *
 * @param string $key The key of the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modSystemSetting';
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';


    public function afterRemove()
    {
        $this->removeRelatedLexiconEntries();
        $this->modx->reloadConfig();

        return true;
    }


    /**
     * Remove all Lexicon Entries related to the setting
     *
     * @return void
     */
    public function removeRelatedLexiconEntries()
    {
        /** @var modLexiconEntry $entry */
        $entry = $this->modx->getObject('modLexiconEntry', [
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_' . $this->object->get('key'),
        ]);
        if (!empty($entry)) {
            $entry->remove();
        }

        /** @var modLexiconEntry $description */
        $description = $this->modx->getObject('modLexiconEntry', [
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_' . $this->object->get('key') . '_desc',
        ]);
        if (!empty($description)) {
            $description->remove();
        }
    }
}