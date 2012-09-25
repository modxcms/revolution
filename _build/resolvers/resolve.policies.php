<?php
/**
 * Resolve Default Policies to their PolicyTemplates
 */
$success= false;

/* map of Policy -> Template */
$map = array(
    'Resource' => 'ResourceTemplate',
    'Administrator' => 'AdministratorTemplate',
    'Content Editor' => 'AdministratorTemplate',
    'Load Only' => 'ObjectTemplate',
    'Load, List and View' => 'ObjectTemplate',
    'Object' => 'ObjectTemplate',
    'Context' => 'ContextTemplate',
    'Element' => 'ElementTemplate',
    'Media Source Admin' => 'MediaSourceTemplate',
    'Media Source User' => 'MediaSourceTemplate',
);

$policies = $transport->xpdo->getCollection('modAccessPolicy');
foreach ($policies as $policy) {
    $pk = isset($map[$policy->get('name')]) ? $map[$policy->get('name')] : 'Administrator';
    $template = $transport->xpdo->getObject('modAccessPolicyTemplate',array('name' => $pk));
    if ($template) {
        $policy->set('template',$template->get('id'));
        $success = $policy->save();
    }
}
return $success;