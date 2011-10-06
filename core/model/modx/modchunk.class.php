<?php
/**
 * @package modx
 */
/**
 * Represents a chunk of static HTML content.
 *
 * @property string $name The name of the Chunk.
 * @property string $description A user-provided description of the Chunk
 * @property int $editor_type Deprecated
 * @property int $category The ID of the Category this chunk resides in. Defaults to 0.
 * @property boolean $cache_type Deprecated
 * @property string $snippet The contents of the Chunk
 * @property boolean $locked Whether or not this chunk can only be edited by Administrators
 * @property array $properties An array of default properties for this Chunk
 *
 * @package modx
 */
class modChunk extends modElement {
    /**
     * Overrides modElement::__construct to set the tag token for this Element
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
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

        $saved = parent::save($cacheFlag);

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
                $scope = $this->xpdo->toPlaceholders($this->_properties, '', '.', true);

                /* collect element tags in the output and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->xpdo->parser->isProcessingUncacheable(),
                    $this->xpdo->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    array(),
                    $maxIterations
                );

                /* remove the placeholders set from the properties of this element and restore global values */
                if (isset($scope['keys'])) $this->xpdo->unsetPlaceholders($scope['keys']);
                if (isset($scope['restore'])) $this->xpdo->toPlaceholders($scope['restore']);
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed= true;
        }

        /* finally, return the processed element content */
        return $this->_output;
    }
}
