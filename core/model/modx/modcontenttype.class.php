<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Represents content types for identifying modResource content.
 *
 * @property string $name The name of the type
 * @property string $description A description of the type
 * @property string $mime_type The MIME type that this Content Type uses
 * @property string $file_extensions The file extension mapped to this Content Type
 * @property string $headers Any HTTP headers to send along with this Content Type
 * @property boolean $binary Whether or not this is a binary Content Type
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
