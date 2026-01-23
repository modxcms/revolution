<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * A collection of modAccessPermission records that are used as a Template for custom modAccessPolicy objects. Is
 * grouped into Access Policy Template Groups to provide targeted policy access implementations.
 *
 * @property int $template_group The group that this template is a part of, used for targeting usage of applied Policies
 * @property string $name The name of the Policy Template
 * @property string $description A description of the Policy Template
 * @property string $lexicon Optional. A lexicon that may be loaded to provide translations for all included Permissions
 *
 * @property modAccessPermission[] $Permissions
 * @property modAccessPolicy[] $Policies
 *
 * @property modX|xPDO $xpdo
 *
 * @package MODX\Revolution
 */
class modAccessPolicyTemplate extends xPDOSimpleObject
{
    public const TEMPLATE_ADMINISTRATOR = 'AdministratorTemplate';
    public const TEMPLATE_CONTEXT = 'ContextTemplate';
    public const TEMPLATE_ELEMENT = 'ElementTemplate';
    public const TEMPLATE_MEDIA_SOURCE = 'MediaSourceTemplate';
    public const TEMPLATE_NAMESPACE = 'NamespaceTemplate';
    public const TEMPLATE_OBJECT = 'ObjectTemplate';
    public const TEMPLATE_RESOURCE = 'ResourceTemplate';

    /**
     * Returns list of core Policy Templates
     *
     * @return array
     */
    public static function getCoreTemplates()
    {
        return [
            self::TEMPLATE_ADMINISTRATOR,
            self::TEMPLATE_RESOURCE,
            self::TEMPLATE_OBJECT,
            self::TEMPLATE_ELEMENT,
            self::TEMPLATE_MEDIA_SOURCE,
            self::TEMPLATE_CONTEXT,
            self::TEMPLATE_NAMESPACE,
        ];
    }

    /**
     * @param $name string The name of access policy template
     *
     * @return bool
     */
    public function isCoreTemplate($name)
    {
        return in_array($name, static::getCoreTemplates(), true);
    }
}
