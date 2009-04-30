<?php
/**
 * Represents content types for identifying modResource content.
 *
 * @package modx
 */
class modContentType extends xPDOSimpleObject {
    function modContentType(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    function getExtension() {
        $extension= '';
        if ($extensions= explode(',', $this->get('file_extensions'))) {
            $extension= $extensions[0];
        }
        return $extension;
    }
}