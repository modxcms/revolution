<?php
/**
 * @package modx
 */
/**
 * Represents an action to a controller or connector.
 *
 * @property string $namespace The key of the Namespace this action belongs to.
 * @property int $parent The ID of the parent action of this action.
 * @property string $controller The name of the controller to use
 * @property boolean $haslayout Whether or not to load the header/footer of the action. Deprecated (use
 * modManagerController class properties instead).
 * @property string $lang_topics Any lexicon topics to load in conjunction with the specified controller. Deprecated
 * (use modManagerController class method instead)
 * @property string $assets Any action-specific assets. Not used.
 * @property string $help_url An absolute URL that this Action can use for displaying a Help box
 *
 * @see modManagerController
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
     * @see modCacheManager::generateActionMap
     * 
     * @access public
     * @param array $options An array of options to pass to the cacheManager->generateActionMap method
     * @return boolean True if successful.
     */
    public function rebuildCache(array $options = array()) {
        $rebuilt = false;
        $cacheKey= $this->xpdo->context->get('key') . '/actions';
        $this->xpdo->getCacheManager();
        if ($this->xpdo->cacheManager->generateActionMap($cacheKey, $options)) {
            $rebuilt = true;
        }
        return $rebuilt;
    }
}