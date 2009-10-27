<?php
/**
 * modContentType
 *
 * @package modx
 */
/**
 * Represents content types for identifying modResource content.
 *
 * @package modx
 */
class modContentType extends xPDOSimpleObject {

    /**
     * Returns the first extension of this Content Type.
     *
     * @access public
     * @return string
     */
    public function getExtension() {
        $extension= '';
        if ($extensions= explode(',', $this->get('file_extensions'))) {
            $extension= $extensions[0];
        }
        return $extension;
    }
}