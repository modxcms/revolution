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
 * Database abstraction of a Lexicon Entry. Used only for overrides on existing entries as a way of allowing
 * customization without sacrificing upgradability of file-based lexicon topics.
 *
 * @property string $name The name, or key, of the lexicon entry that is being overridden
 * @property string $value The value to override the entry with
 * @property string $topic The topic of the overridden entry
 * @property string $namespace The namespace of the overridden entry
 * @property string $language The language of the overridden entry
 * @property datetime $createdon The time that this entry was created
 * @property string $editedon The last time that this entry was edited
 * @see modLexicon
 * @package modx
 */
class modLexiconEntry extends xPDOSimpleObject {
    /**
     * Clears the cache for the entry
     *
     * @access public
     * @return boolean True if successful
     */
    public function clearCache() {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('language').'/'.$this->get('namespace').'/'.$this->get('topic').'.cache.php');
        }
        return false;
    }

    /**
     * Overrides xPDOObject::save to clear lexicon cache on saving.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        if ($saved && empty($this->xpdo->config[xPDO::OPT_SETUP])) {
            $this->clearCache();
        }
        return $saved;
    }
}
