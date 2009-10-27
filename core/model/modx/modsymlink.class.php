<?php
/**
 * A modResource derivative the represents a symbolic link.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @extends modResource
 */
class modSymLink extends modResource {
    /**
     * Creates a modSymLink instance.
     *
     * {@inheritDoc}
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('type', 'reference');
        $this->set('class_key', 'modSymLink');
    }

    /**
     * Process the modSymLink and forward to the specified resource.
     *
     * {@inheritDoc}
     */
    public function process() {
        $this->_content= $this->get('content');
        if (empty ($this->_content)) {
            $this->xpdo->sendErrorPage();
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $parser= $this->xpdo->getParser();
            $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, true, true, '[[', ']]', array(), $maxIterations);
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $this->_output= $this->_content;
        }
        $this->xpdo->sendForward($this->_output);
    }
}