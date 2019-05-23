<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution;

/**
 * Represents Lexicon tags, for localized strings.
 *
 * [[%word_or_phase]] Returns the lexicon representation of 'word_or_phrase' for
 * the currently loaded language.
 *
 * @package MODX\Revolution
 */
class modLexiconTag extends modTag
{
    /**
     * Overrides modTag::__construct to set the Lexicon Tag token
     * {@inheritdoc}
     */
    function __construct(modX & $modx)
    {
        parent:: __construct($modx);
        $this->setToken('%');
    }

    /**
     * Processes a modLexiconTag, recursively processing nested tags.
     *
     * {@inheritdoc}
     */
    public function process($properties = null, $content = null)
    {
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
     * Get the raw source content of the link.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = [])
    {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                if (!is_object($this->modx->lexicon)) {
                    $this->modx->getService('lexicon', 'modLexicon');
                }
                $topic = !empty($this->_properties['topic']) ? $this->_properties['topic'] : 'default';
                $namespace = !empty($this->_properties['namespace']) ? $this->_properties['namespace'] : 'core';
                $language = !empty($this->_properties['language']) ? $this->_properties['language'] : $this->modx->getOption('cultureKey',
                    null, 'en');
                $this->modx->lexicon->load($language . ':' . $namespace . ':' . $topic);

                $this->_content = $this->modx->lexicon($this->get('name'), $this->_properties, $language);
            }
        }

        return $this->_content;
    }
}
