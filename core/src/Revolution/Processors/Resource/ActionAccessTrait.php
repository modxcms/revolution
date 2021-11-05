<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\modStaticResource;
use MODX\Revolution\modSymLink;
use MODX\Revolution\modWebLink;

trait ActionAccessTrait
{
    private $permissionsMap = [
        modWebLink::class => 'weblink',
        modSymLink::class => 'symlink',
        modStaticResource::class => 'static_resource',
    ];

    /**
     * Checks if specific action allowed for requested class key object
     *
     * @param string $classKey
     * @param string $action
     *
     * @return bool
     */
    protected function checkActionPermission(string $classKey, string $action): bool
    {
        $permissions = [$this->permission];

        $map = array_map(static function ($v) use ($action) {
            return implode('_', [$action, $v]);
        }, $this->permissionsMap);


        if (array_key_exists($classKey, $map)) {
            $permissions[] = $map[$classKey];
        }

        foreach ($permissions as $permission) {
            if (!$this->modx->hasPermission($permission)) {
                // allow the error message shown to the user (in modProcessor->run())
                // to show the right failed permission
                $this->permission = $permission;
                return false;
            }
        }

        return true;
    }
}
