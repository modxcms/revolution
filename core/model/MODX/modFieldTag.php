<?php

namespace MODX;

/**
 * Tag representing a modResource field from the current MODX resource.
 *
 * [[*content]] Represents the content field from modResource.
 *
 * @uses MODX::$resource The modResource instance being processed by MODX.
 * @package modx
 */
class modFieldTag extends modTag
{
    /**
     * Overrides modTag::__construct to set the Field Tag token
     * {@inheritdoc}
     */
    function __construct(MODX & $modx)
    {
        parent:: __construct($modx);
        $this->setToken('*');
    }


    /**
     * Process the modFieldTag and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties = null, $content = null)
    {
        if ($this->get('name') === 'content') $this->setCacheable(false);
        parent:: process($properties, $content);
        if (!$this->_processed) {
            $this->_output = $this->_content;
            if (is_string($this->_output) && !empty ($this->_output)) {
                /* collect element tags in the content and process them */
                $maxIterations = intval($this->modx->getOption('parser_max_iterations', null, 10));
                $this->modx->parser->processElementTags(
                    $this->_tag,
                    $this->_output,
                    $this->modx->parser->isProcessingUncacheable(),
                    $this->modx->parser->isRemovingUnprocessed(),
                    '[[',
                    ']]',
                    [],
                    $maxIterations
                );
            }
            $this->filterOutput();
            $this->cache();
            $this->_processed = true;
        }

        /* finally, return the processed element content */

        return $this->_output;
    }


    /**
     * Get the raw source content of the field.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = [])
    {
        if (!$this->isCacheable() || !is_string($this->_content) || $this->_content === '') {
            if (isset($options['content']) && !empty($options['content'])) {
                $this->_content = $options['content'];
            } elseif ($this->modx->resource instanceof modResource) {
                if ($this->get('name') == 'content') {
                    $this->_content = $this->modx->resource->getContent($options);
                } else {
                    $this->_content = $this->modx->resource->get($this->get('name'));
                }
            }
        }

        return $this->_content;
    }
}