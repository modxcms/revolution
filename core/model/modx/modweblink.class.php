<?php
/**
 * A modResource derivative the represents a redirect link.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modWebLink extends modResource {
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('type', 'reference');
        $this->set('class_key', 'modWebLink');
    }

    /**
     * Process the modWebLink and redirect to the specified resource.
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
            $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, true, true, '[[', ']]', array(), $maxIterations);
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $this->_output= $this->_content;
        }
        $this->xpdo->sendRedirect($this->_output);
    }
}