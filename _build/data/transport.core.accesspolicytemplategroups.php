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
    'name' => 'Administrator',
    'description' => 'All administrator policy templates.',
));

/* Object group templates */
$templateGroups['2']= $xpdo->newObject('modAccessPolicyTemplateGroup');
$templateGroups['2']->fromArray(array(
    'id' => 2,
    'name' => 'Object',
    'description' => 'All Object-based policy templates.',
));

return $templateGroups;