<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Defines criteria a principal must satisfy in order to access an object.
 *
 * @property string            $name        The name of this Policy
 * @property string            $description The description of this Policy.
 * @property int               $parent      The parent Policy of this Policy. Not currently used.
 * @property int               $template    The Access Policy Template this Policy belongs to
 * @property string            $class       Deprecated
 * @property array             $data        JSON object with all Permissions for this Policy
 * @property string            $lexicon     Optional. Lexicon for translated names/descriptions of Permissions
 *
 * @property modAccessPolicy[] $Children
 *
 * @property modX|xPDO         $xpdo
 *
 * @package MODX\Revolution
 */
class modAccessPolicy extends xPDOSimpleObject
{
    public const POLICY_RESOURCE = 'Resource';
    public const POLICY_ADMINISTRATOR = 'Administrator';
    public const POLICY_LOAD_ONLY = 'Load Only';
    public const POLICY_LOAD_LIST_VIEW = 'Load, List and View';
    public const POLICY_OBJECT = 'Object';
    public const POLICY_ELEMENT = 'Element';
    public const POLICY_CONTENT_EDITOR = 'Content Editor';
    public const POLICY_MEDIA_SOURCE_ADMIN = 'Media Source Admin';
    public const POLICY_MEDIA_SOURCE_USER = 'Media Source User';
    public const POLICY_DEVELOPER = 'Developer';
    public const POLICY_CONTEXT = 'Context';
    public const POLICY_HIDDEN_NAMESPACE = 'Hidden Namespace';

    /**
     * Returns list of core Policies
     *
     * @return array
     */
    public static function getCorePolicies(): array
    {
        return [
            self::POLICY_RESOURCE,
            self::POLICY_ADMINISTRATOR,
            self::POLICY_LOAD_ONLY,
            self::POLICY_LOAD_LIST_VIEW,
            self::POLICY_OBJECT,
            self::POLICY_ELEMENT,
            self::POLICY_CONTENT_EDITOR,
            self::POLICY_MEDIA_SOURCE_ADMIN,
            self::POLICY_MEDIA_SOURCE_USER,
            self::POLICY_DEVELOPER,
            self::POLICY_CONTEXT,
            self::POLICY_HIDDEN_NAMESPACE,
        ];
    }

    /**
     * @param $name string The name of access policy
     *
     * @return bool
     */
    public function isCorePolicy(string $name): bool
    {
        return in_array($name, static::getCorePolicies(), true);
    }

    /**
     * {@inheritdoc}
     * Ensures 'data' always returns an array per @property contract (xPDO json type may return string/null).
     */
    public function get($k, $format = null, $formatTemplate = null)
    {
        $value = parent::get($k, $format, $formatTemplate);
        if (is_array($k)) {
            if (!is_array($value)) {
                return $value;
            }
            if (array_key_exists('data', $value) && !is_array($value['data'])) {
                $value['data'] = [];
            }
            return $value;
        }
        if ($k === 'data') {
            return is_array($value) ? $value : [];
        }
        return $value;
    }

    /**
     * Get the permissions for this access policy, in array format.
     *
     * @return array An array of access permissions for this Policy.
     */
    public function getPermissions(): array
    {
        $template = $this->getOne('Template');

        if ($template === null) {
            return [];
        }

        /* get permissions for policy */
        $c = $this->xpdo->newQuery(modAccessPermission::class);
        $c->sortby('name', 'ASC');
        $permissions = $template->getMany('Permissions', $c);

        $data = $this->get('data');
        $lexicon = $template->get('lexicon');
        $list = [];
        /** @var modAccessPermission $permission */
        foreach ($permissions as $permission) {
            $permissionName = $permission->get('name');
            $descriptionKey = $permission->get('description');
            $desc = $descriptionKey;
            if (!empty($lexicon) && $this->xpdo->lexicon) {
                if (strpos($lexicon, ':') !== false) {
                    $this->xpdo->lexicon->load($lexicon);
                } else {
                    $this->xpdo->lexicon->load('core:' . $lexicon);
                }
                $desc = $this->xpdo->lexicon($descriptionKey);
            }
            $active = array_key_exists($permissionName, $data) && $data[$permissionName] ? 1 : 0;
            $list[] = [
                $permissionName,
                $descriptionKey,
                $desc,
                $permission->get('value'),
                $active,
            ];
        }

        return $list;
    }
}
