<?php
/**
 * Resolve PolicyTemplates to their PolicyTemplateGroups
 *
 * @package modx
 * @subpackage build
 */
$success= false;

/* map of Policy -> Template */
$map = array(
    'Resource' => 'Object',
    'Administrator' => 'Admin',
    'Load Only' => 'Object',
    'Element' => 'Object',
);

$templates = $transport->xpdo->getCollection('modAccessPolicyTemplate');
foreach ($templates as $template) {
    $pk = isset($map[$template->get('name')]) ? $map[$template->get('name')] : 'Admin';
    $templateGroup = $transport->xpdo->getObject('modAccessPolicyTemplateGroup',array('name' => $pk));
    $template->set('template_group',$templateGroup->get('id'));
    $success = $template->save();
}
return $success;