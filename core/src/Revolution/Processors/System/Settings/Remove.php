<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\System\Settings;

use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modSystemSetting;

/**
 * Remove a system setting
 * @property string $key The key of the setting
 * @package MODX\Revolution\Processors\System\Settings
 */
class Remove extends RemoveProcessor
{
    public $classKey = modSystemSetting::class;
    public $languageTopics = ['setting', 'namespace'];
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /**
     * @return bool
     */
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
        $entry = $this->modx->getObject(modLexiconEntry::class, [
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_' . $this->object->get('key'),
        ]);
        if ($entry !== null) {
            $entry->remove();
        }

        /** @var modLexiconEntry $description */
        $description = $this->modx->getObject(modLexiconEntry::class, [
            'namespace' => $this->object->get('namespace'),
            'name' => 'setting_' . $this->object->get('key') . '_desc',
        ]);
        if ($description !== null) {
            $description->remove();
        }
    }
}
