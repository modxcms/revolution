<?php
/**
 * @deprecated 2.0.0-rc-2
 * @package modx
 */
class modLexiconLanguage extends xPDOObject {

    /**
     * Clears the cache for the language
     *
     * @access public
     * @return boolean True if successful
     */
    public function clearCache() {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('name').'/');
        }
        return false;
    }

    /**
     * Overrides xPDOObject::save to clear lexicon cache on saving.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag= null) {
        $saved = parent :: save($cacheFlag);
        if ($saved && empty($this->xpdo->config[xPDO::OPT_SETUP])) {
            $this->clearCache();
        }
        return $saved;
    }
}