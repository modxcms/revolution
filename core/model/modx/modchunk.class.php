<?php
/**
 * Represents a chunk of static HTML content.
 *
 * @package modx
 */
class modChunk extends modElement {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->setToken('$');
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'chunk' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'chunk' => &$this,
                'cacheFlag' => $cacheFlag,
            ));

        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('chunk_err_create') : $this->xpdo->lexicon('chunk_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkBeforeRemove',array(
                'chunk' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnChunkRemove',array(
                'chunk' => &$this,
                'ancestors' => $ancestors,
            ));

        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('chunk_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Overrides modElement::process to initialize the Chunk into the element cache,
     * as well as set placeholders and filter the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            /* copy the content into the output buffer */
            $this->_output= $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* turn the processed properties into placeholders */
                $restore = $this->toPlaceholders($this->_properties);

                /* collect element tags in the output and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
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
     *
     * @access public
     * @param array $options
     * @return string The source content.
     */
    public function getContent(array $options = array()) {
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
     *
     * @access public
     * @param string $content
     * @param array $options
     * @return string True if successfully set
     */
    public function setContent($content, array $options = array()) {
        return $this->set('snippet', $content);
    }
}