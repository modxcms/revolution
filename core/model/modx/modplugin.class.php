<?php
/**
 * Provides a non-cacheable modScript implementation representing plugins.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modPlugin extends modScript {
    function modPlugin(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheable= false;
    }

    /**
     * Get the source content of this plugin.
     */
    function getContent($options = array()) {
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
     * Set the source content of this plugin.
     */
    function setContent($content, $options = array()) {
        return $this->set('plugincode', $content);
    }

    function getPropertySet($setName = null) {
        if (empty($setName) && !empty($this->xpdo->event->propertySet)) {
            $setName = $this->xpdo->event->propertySet;
        }
        return parent :: getPropertySet($setName);
    }
}
?>