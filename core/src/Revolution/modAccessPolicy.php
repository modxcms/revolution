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
 * @property array             $data        A JSON object that contains all Permissions loaded in this Policy
 * @property string            $lexicon     Optional. The lexicon to load to provide the translated names/descriptions for the
 * Permissions included
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
            self::POLICY_ELEMENT ,
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
     * Core policies that map 1:1 to a core template (rename fallback only when unique).
     *
     * @return array<string, string> policy name => template name
     */
    private static function getUniqueCorePolicyTemplates(): array
    {
        return [
            self::POLICY_RESOURCE => modAccessPolicyTemplate::TEMPLATE_RESOURCE,
            self::POLICY_ELEMENT => modAccessPolicyTemplate::TEMPLATE_ELEMENT,
            self::POLICY_CONTEXT => modAccessPolicyTemplate::TEMPLATE_CONTEXT,
            self::POLICY_HIDDEN_NAMESPACE => modAccessPolicyTemplate::TEMPLATE_NAMESPACE,
        ];
    }

    /**
     * Resolve a uniquely-templated core Access Policy by its shipped name.
     * If renamed and exactly one policy remains on that core template, return it.
     * Ambiguous templates (multiple policies) fail closed with null.
     *
     * @param xPDO $xpdo
     * @param string $name Core policy name (e.g. modAccessPolicy::POLICY_RESOURCE)
     *
     * @return static|null
     */
    public static function getPolicy(xPDO $xpdo, string $name): ?self
    {
        $policy = $xpdo->getObject(static::class, ['name' => $name]);
        if ($policy) {
            return $policy;
        }

        $templateName = static::getUniqueCorePolicyTemplates()[$name] ?? null;
        if ($templateName === null) {
            return null;
        }

        $template = $xpdo->getObject(modAccessPolicyTemplate::class, ['name' => $templateName]);
        if (!$template) {
            return null;
        }

        $policies = $xpdo->getCollection(static::class, [
            'template' => $template->get('id'),
        ]);
        if (count($policies) !== 1) {
            return null;
        }

        return reset($policies) ?: null;
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
            $desc = $permission->get('description');
            if (!empty($lexicon) && $this->xpdo->lexicon) {
                if (strpos($lexicon, ':') !== false) {
                    $this->xpdo->lexicon->load($lexicon);
                } else {
                    $this->xpdo->lexicon->load('core:' . $lexicon);
                }
                $desc = $this->xpdo->lexicon($desc);
            }
            $active = array_key_exists($permission->get('name'), $data) && $data[$permission->get('name')] ? 1 : 0;
            $list[] = [
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
                $active,
            ];
        }

        return $list;
    }
}
