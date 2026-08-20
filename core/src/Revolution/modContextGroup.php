<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
namespace MODX\Revolution;

/**
 * A named group of Contexts for manager tree organization and filtering.
 *
 * Membership is a loose association (aggregate): removing a group must not
 * cascade-delete Contexts. After a successful remove, member Contexts are
 * unassigned (context_group = 0). ACL rows still cascade via the Acls composite.
 *
 * @property string       $name
 * @property string       $description
 * @property integer      $rank
 *
 * @property modContext[] $Contexts
 *
 * @package MODX\Revolution
 */
class modContextGroup extends \xPDO\Om\xPDOSimpleObject
{
    /**
     * Remove the group (and composite ACLs), then unassign former members.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors = [])
    {
        $groupId = $this->get('id');
        if (!parent::remove($ancestors)) {
            return false;
        }

        $this->xpdo->updateCollection(modContext::class, [
            'context_group' => 0,
        ], [
            'context_group' => $groupId,
        ]);

        return true;
    }
}
