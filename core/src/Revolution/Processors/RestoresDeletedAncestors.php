<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors;

use MODX\Revolution\modResource;

/**
 * Restores deleted parents so a child is not left live under a trashed parent (#14167).
 */
trait RestoresDeletedAncestors
{
    /**
     * @return int[]|null Restored ancestor ids, or null if a deleted ancestor could not be restored.
     */
    protected function restoreDeletedAncestors(modResource $resource): ?array
    {
        $restoredIds = [];
        $parentId = (int)$resource->get('parent');
        $guard = 0;
        while ($parentId > 0 && $guard < 100) {
            $guard++;
            $parent = $this->modx->getObject(modResource::class, $parentId);
            if (!$parent instanceof modResource) {
                break;
            }
            if ($parent->get('deleted')) {
                if (!$parent->checkPolicy(['save' => true, 'undelete' => true])) {
                    return null;
                }
                $parent->set('deleted', false);
                $parent->set('deletedby', 0);
                $parent->set('deletedon', 0);
                if ($parent->save() === false) {
                    return null;
                }
                $this->modx->invokeEvent('OnResourceUndelete', [
                    'id' => $parent->get('id'),
                    'resource' => &$parent,
                ]);
                $restoredIds[] = (int)$parent->get('id');
            }
            $parentId = (int)$parent->get('parent');
        }

        return $restoredIds;
    }
}
