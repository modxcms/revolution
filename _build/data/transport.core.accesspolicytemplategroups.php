<?php
/**
 * @package modx
 * @subpackage build
 */
$templateGroups = array();

/* administrator group templates */
$templateGroups['1']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['1']->fromArray(array(
    'id' => 1,
    'name' => 'Admin',
    'description' => 'All admin policy templates.',
));

/* Object group templates */
$templateGroups['2']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['2']->fromArray(array(
    'id' => 2,
    'name' => 'Object',
    'description' => 'All Object-based policy templates.',
));

/* Resource group templates */
$templateGroups['3']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['3']->fromArray(array(
    'id' => 3,
    'name' => 'Resource',
    'description' => 'All Resource-based policy templates.',
));

/* Element group templates */
$templateGroups['4']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['4']->fromArray(array(
    'id' => 4,
    'name' => 'Element',
    'description' => 'All Element-based policy templates.',
));

/* Media Source group templates */
$templateGroups['5']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['5']->fromArray(array(
    'id' => 5,
    'name' => 'MediaSource',
    'description' => 'All Media Source-based policy templates.',
));

return $templateGroups;