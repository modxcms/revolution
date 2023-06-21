<?php

namespace MODX\Revolution;

use xPDO\xPDO;

/**
 * {@inheritdoc}
 *
 * This modResource derivative represents a traditional web document that stores
 * it's primary content in the modX database container.
 *
 * @package MODX\Revolution
 */
class modDocument extends modResource
{
    /**
     * Overrides modResource::__construct to set the class key for this Resource type
     *
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(& $xpdo)
    {
        parent:: __construct($xpdo);
        $this->set('class_key', self::class);
        $this->showInContextMenu = true;
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
            'text_create' => $this->xpdo->lexicon('document'),
            'text_create_here' => $this->xpdo->lexicon('document_create_here'),
        ];
    }
}
