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

$templateGroups = array();

/* administrator group templates */
$templateGroups['1']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['1']->fromArray(array(
    'id' => 1,
    'name' => 'Admin',
    'description' => 'All admin policy templates.',
));

/* Object group templates */
$templateGroups['2']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['2']->fromArray(array(
    'id' => 2,
    'name' => 'Object',
    'description' => 'All Object-based policy templates.',
));

/* Resource group templates */
$templateGroups['3']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['3']->fromArray(array(
    'id' => 3,
    'name' => 'Resource',
    'description' => 'All Resource-based policy templates.',
));

/* Element group templates */
$templateGroups['4']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['4']->fromArray(array(
    'id' => 4,
    'name' => 'Element',
    'description' => 'All Element-based policy templates.',
));

/* Media Source group templates */
$templateGroups['5']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['5']->fromArray(array(
    'id' => 5,
    'name' => 'MediaSource',
    'description' => 'All Media Source-based policy templates.',
));

/* Namespace group templates */
$templateGroups['6']= $xpdo->newObject(modAccessPolicyTemplateGroup::class);
$templateGroups['6']->fromArray(array(
    'id' => 6,
    'name' => 'Namespace',
    'description' => 'All Namespace based policy templates.',
));

return $templateGroups;
