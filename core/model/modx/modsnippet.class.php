<?php
/**
 * A modScript derivative representing a MODx PHP code snippet.
 *
 * @package modx
 */
class modSnippet extends modScript {
    function modSnippet(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Get the source content of this snippet.
     */
    function getContent($options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('snippet');
            }
        }
        return $this->_content;
    }
    
    /**
     * Set the source content of this snippet.
     */
    function setContent($content, $options = array()) {
        return $this->set('snippet', $content);
    }
}
?>