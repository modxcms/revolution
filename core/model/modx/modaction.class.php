<?php
/**
 * Represents an action to a controller or connector.
 *
 * @package modx
 */
class modAction extends modAccessibleSimpleObject {
    function modAction(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    /**
     * Overrides xPDOObject::save to cache the actionMap.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag = null) {
        $r = parent::save($cacheFlag);
        $this->rebuildCache();
        return $r;
    }

    /**
     * Overrides xPDOObject::save to cache the actionMap.
     *
     * {@inheritdoc}
     */
    function remove($ancestors = array()) {
        $r = parent::remove($ancestors);
        $this->rebuildCache();
        return $r;
    }

    /**
     * Rebuilds the action map cache.
     *
     * @access public
     * @return boolean True if successful.
     */
    function rebuildCache($options = array()) {
        $rebuilt = false;
        if (is_a($this->xpdo, 'modX')) {
            $this->modx =& $this->xpdo;
            $cacheKey= $this->modx->context->get('key') . '/actions';
            $this->modx->getCacheManager();
            if ($this->modx->cacheManager->generateActionMap($cacheKey, $options)) {
                $rebuilt = true;
            }
        }
        return $rebuilt;
    }
}