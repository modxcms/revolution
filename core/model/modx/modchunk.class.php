<?php
/**
 * Represents a chunk of static HTML content.
 *
 * @package modx
 */
class modChunk extends modElement {
    function modChunk(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_token = '$';
    }

	/**
	 * Overrides modElement::process to initialize the Chunk into the element cache,
	 * as well as set placeholders and filter the output.
	 *
	 * {@inheritdoc}
	 */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            /* copy the content into the output buffer */
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* turn the processed properties into placeholders */
                $restore = $this->toPlaceholders($this->_properties);

                /* collect element tags in the output and process them */
                $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
                
                /* remove the placeholders set from the properties of this element and restore global values */
                $this->xpdo->unsetPlaceholders(array_keys($this->_properties));
                if ($restore) $this->xpdo->toPlaceholders($restore);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }

        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the source content of this chunk.
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
     * Set the source content of this chunk.
     */
    function setContent($content, $options = array()) {
        return $this->set('snippet', $content);
    }
}
?>