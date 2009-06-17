<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconLanguage extends xPDOObject {
    function modLexiconLanguage(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Clears the cache for the language
     *
     * @access public
     * @return boolean True if successful
     */
    function clearCache() {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('language').'/');
        }
        return false;
    }

    /**
     * Overrides xPDOObject::save to clear lexicon cache on saving.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag= null) {
        $rt= parent :: save($cacheFlag);
        $this->clearCache();
        return $rt;
    }
}