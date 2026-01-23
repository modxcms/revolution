<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * Represents content types for identifying modResource content.
 *
 * @property string  $name            The name of the type
 * @property string  $description     A description of the type
 * @property string  $mime_type       The MIME type that this Content Type uses
 * @property string  $file_extensions The file extension mapped to this Content Type
 * @property string  $headers         Any HTTP headers to send along with this Content Type
 * @property boolean $binary          Whether or not this is a binary Content Type
 *
 * @package MODX\Revolution
 */
class modContentType extends xPDOSimpleObject
{
    public const CORE_TYPES = [
        'HTML',
        'XML',
        'Text',
        'CSS',
        'JavaScript',
        'RSS',
        'JSON',
        'PDF'
    ];

    /**
     * Returns the first extension of this Content Type.
     *
     * @access public
     * @return string
     */
    public function getExtension()
    {
        $extension = '';
        if ($extensions = explode(',', $this->get('file_extensions'))) {
            $extension = $extensions[0];
        }

        return $extension;
    }

    /**
     * Returns a list of core Dashboards
     *
     * @return array
     */
    public static function getCoreContentTypes(): array
    {
        return self::CORE_TYPES;
    }

    /**
     * @param string $name The name of the Dashboard
     *
     * @return bool
     */
    public function isCoreContentType($name): bool
    {
        return in_array($name, static::getCoreContentTypes(), true);
    }
}
