<?php
/**
 * @deprecated 2.0.0-rc-2
 * @package modx
 */
class modLexiconTopic extends xPDOSimpleObject {
    /**
     * Clears the cache for the topic
     *
     * @access public
     * @return boolean True if successful
     */
    public function clearCache() {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('language').'/'.$this->get('namespace').'/'.$this->get('name'));
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