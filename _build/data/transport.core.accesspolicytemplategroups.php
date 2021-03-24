<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modAccessPolicyTemplateGroup;

$templateGroups = [];

$groups = modAccessPolicyTemplateGroup::getCoreGroups();

foreach (modAccessPolicyTemplateGroup::getCoreGroups() as $index => $groupName) {
    $group = $xpdo->newObject(modAccessPolicyTemplateGroup::class);
    $group->fromArray(
        [
            'id' => $index + 1,
            'name' => $groupName,
            'description' => sprintf('policy_template_group_%s_desc', strtolower($groupName)),
        ]
    );

    $templateGroups[] = $group;
}

return $templateGroups;
