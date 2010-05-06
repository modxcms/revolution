<?php
/**
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