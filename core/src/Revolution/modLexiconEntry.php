<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Database abstraction of a Lexicon Entry. Used only for overrides on existing entries as a way of allowing
 * customization without sacrificing upgradability of file-based lexicon topics.
 *
 * @property string    $name      The name, or key, of the lexicon entry that is being overridden
 * @property string    $value     The value to override the entry with
 * @property string    $topic     The topic of the overridden entry
 * @property string    $namespace The namespace of the overridden entry
 * @property string    $language  The language of the overridden entry
 * @property string    $createdon The time that this entry was created
 * @property string    $editedon  The last time that this entry was edited
 *
 * @property modX|xPDO $xpdo
 *
 * @package MODX\Revolution
 */
class modLexiconEntry extends xPDOSimpleObject
{
    /**
     * Clears the cache for the entry
     *
     * @access public
     * @return boolean True if successful
     */
    public function clearCache()
    {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('language') . '/' . $this->get('namespace') . '/' . $this->get('topic') . '.cache.php');
        }

        return false;
    }

    /**
     * Ensures required values are present or set to their defaults, when applicable
     *
     * @return boolean True when valid
     */
    private function validateEntry()
    {
        if (empty($this->get('name'))) {
            return false;
        }
        if (empty($this->get('namespace'))) {
            $this->set('namespace', 'core');
        }
        if (empty($this->get('topic'))) {
            $this->set('topic', 'default');
        }
        if (empty($this->get('language'))) {
            $defaultLanguage = $this->xpdo->getOption('cultureKey');
            $language = !empty($defaultLanguage) ? $defaultLanguage : 'en' ;
            $this->set('language', $language);
        }
        return true;
    }

    /**
     * Overrides xPDOObject::save to clear lexicon cache on saving.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if (!$this->validateEntry()) {
            return false;
        }
        if ($this->_new) {
            if (!$this->get('createdon')) {
                $this->set('createdon', date('Y-m-d H:i:s'));
            }
        }
        $saved = parent::save($cacheFlag);
        if ($saved && empty($this->xpdo->config[xPDO::OPT_SETUP])) {
            $this->clearCache();
        }

        return $saved;
    }
}
