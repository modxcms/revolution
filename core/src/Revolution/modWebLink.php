<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * A modResource derivative the represents a redirect link.
 * @package MODX\Revolution
 */
class modWebLink extends modResource
{
    /**
     * Overrides modResource::__construct to set the class key for this Resource type
     *
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    public function __construct(xPDO $xpdo)
    {
        parent:: __construct($xpdo);
        $this->set('type', 'reference');
        $this->set('class_key', __CLASS__);
        $canCreate = (bool)$this->xpdo->hasPermission('new_weblink');
        $this->allowListingInClassKeyDropdown = $canCreate;
        $this->showInContextMenu = $canCreate;
    }

    /**
     * Process the modWebLink and redirect to the specified resource.
     */
    public function process()
    {
        $this->_content = $this->get('content');
        if (empty ($this->_content)) {
            $this->xpdo->sendErrorPage();
        }
        if (!is_numeric($this->_content)) {
            $this->xpdo->getParser();
            $maxIterations = isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, true, true, '[[', ']]', [],
                $maxIterations);
        }
        if (is_numeric($this->_content)) {
            $this->_output = $this->xpdo->makeUrl(intval($this->_content), '', '', 'full');
        } else {
            $this->_output = $this->_content;
        }
        $this->xpdo->sendRedirect($this->_output, [
            'responseCode' => $this->getProperty('responseCode', 'core',
                $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently'),
        ]);
    }

    /**
     * Get the full controller path for managing WebLinks in MODX
     *
     * @static
     *
     * @param xPDO $modx A reference to the modX instance
     *
     * @return string The absolute path to the controller for managing WebLinks
     */
    public static function getControllerPath(xPDO &$modx)
    {
        $path = modResource::getControllerPath($modx);

        return $path . 'weblink/';
    }

    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true.
     *
     * @return array
     */
    public function getContextMenuText()
    {
        return [
            'text_create' => $this->xpdo->lexicon('weblink'),
            'text_create_here' => $this->xpdo->lexicon('weblink_create_here'),
        ];
    }

    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     *
     * @return string
     */
    public function getResourceTypeName()
    {
        return $this->xpdo->lexicon('weblink');
    }
}
