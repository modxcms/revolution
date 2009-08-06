<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconEntry extends xPDOSimpleObject {
    function modLexiconEntry(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Clears the cache for the entry
     *
     * @access public
     * @return boolean True if successful
     */
    function clearCache() {
    	if ($this->xpdo && $this->xpdo->lexicon) {
            $topic = $this->getOne('Topic');
            if ($topic == null) return false;

    		return $this->xpdo->lexicon->clearCache($this->get('language').'/'.$this->get('namespace').'/'.$topic->get('name').'.cache.php');
        }
        return false;
    }

    /**
     * Overrides xPDOObject::save to clear lexicon cache on saving.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $rt= parent :: save($cacheFlag);
        $this->clearCache();
        return $rt;
    }
}