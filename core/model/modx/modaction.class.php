<?php
/**
 * Represents an action to a controller or connector.
 *
 * @package modx
 */
class modAction extends modAccessibleSimpleObject {

    /**
     * Overrides xPDOObject::save to cache the actionMap.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $saved = parent::save($cacheFlag);
        if ($saved && empty($this->xpdo->config[xPDO::OPT_SETUP])) {
            $this->rebuildCache();
        }
        return $saved;
    }

    /**
     * Overrides xPDOObject::save to cache the actionMap.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors = array()) {
        $removed = parent::remove($ancestors);
        if ($removed && empty($this->xpdo->config[xPDO::OPT_SETUP])) {
            $this->rebuildCache();
        }
        return $removed;
    }

    /**
     * Rebuilds the action map cache.
     *
     * @access public
     * @return boolean True if successful.
     */
    public function rebuildCache(array $options = array()) {
        $rebuilt = false;
        $this->modx =& $this->xpdo;
        $cacheKey= $this->modx->context->get('key') . '/actions';
        $this->modx->getCacheManager();
        if ($this->modx->cacheManager->generateActionMap($cacheKey, $options)) {
            $rebuilt = true;
        }
        return $rebuilt;
    }
}