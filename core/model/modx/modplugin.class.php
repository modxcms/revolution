<?php
/**
 * Provides a non-cacheable modScript implementation representing plugins.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @extends modScript
 */
class modPlugin extends modScript {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheable= false;
    }

    /**
     * Overrides modElement::save to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        $success = parent::save($cacheFlag);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('plugin_err_create') : $this->xpdo->lexicon('plugin_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        return $success;
    }

    /**
     * Overrides modElement::remove to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        $success = parent :: remove($ancestors);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('plugin_err_remove').$this->toArray());
        }

        return $success;
    }

    /**
     * Overrides modElement::getContent to get the source content of this
     * plugin.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('plugincode');
            }
        }
        return $this->_content;
    }

    /**
     * Overrides modElement::setContent to set the source content of this
     * plugin.
     *
     * {@inheritdoc}
     */
    public function setContent($content, array $options = array()) {
        return $this->set('plugincode', $content);
    }

    /**
     * Overrides modElement::getPropertySet to handle separate plugin event
     * property set calls.
     *
     * {@inheritdoc}
     */
    public function getPropertySet($setName = null) {
        if (empty($setName) && !empty($this->xpdo->event->propertySet)) {
            $setName = $this->xpdo->event->propertySet;
        }
        return parent :: getPropertySet($setName);
    }
}