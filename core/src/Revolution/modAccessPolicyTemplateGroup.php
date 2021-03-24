<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A grouping class used for classifying what targets Access Policies (and their Templates) should be applied towards.
 *
 * @property string                    $name        The name of the Group
 * @property string                    $description A description of the Group
 *
 * @property modAccessPolicyTemplate[] $Templates
 *
 * @package MODX\Revolution
 */
class modAccessPolicyTemplateGroup extends xPDOSimpleObject
{
    const GROUP_ADMINISTRATOR = 'Administrator';
    const GROUP_OBJECT = 'Object';
    const GROUP_RESOURCE = 'Resource';
    const GROUP_ELEMENT = 'Element';
    const GROUP_MEDIA_SOURCE = 'MediaSource';
    const GROUP_NAMESPACE = 'Namespace';
    const GROUP_CONTEXT = 'Context';

    /**
     * Returns list of core Policy Template Groups
     * @return array
     */
    public static function getCoreGroups()
    {
        return [
            self::GROUP_ADMINISTRATOR,
            self::GROUP_OBJECT,
            self::GROUP_RESOURCE,
            self::GROUP_ELEMENT,
            self::GROUP_MEDIA_SOURCE,
            self::GROUP_NAMESPACE,
            self::GROUP_CONTEXT,
        ];
    }

    /**
     * @param $name string The name of access policy template group
     *
     * @return bool
     */
    public function isCoreGroup($name)
    {
        return in_array($name, static::getCoreGroups(), true);
    }
}
