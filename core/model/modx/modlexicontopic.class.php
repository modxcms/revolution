<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconTopic extends xPDOSimpleObject {
    function modLexiconTopic(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Clears the cache for the topic
     *
     * @access public
     * @return boolean True if successful
     */
    function clearCache() {
        if ($this->xpdo && $this->xpdo->lexicon) {
            return $this->xpdo->lexicon->clearCache($this->get('language').'/'.$this->get('namespace').'/'.$this->get('topic'));
        }
        return false;
    }
}