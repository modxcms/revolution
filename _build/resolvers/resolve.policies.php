<?php
/**
 * Resolve Policies to their PolicyTemplates
 */
$success= false;

/* map of Policy -> Template */
$map = array(
    'Resource' => 'Resource',
    'Administrator' => 'Administrator',
    'Load Only' => 'Object',
    'Load, List and View' => 'Object',
    'Object' => 'Object',
    'Element' => 'Element',
);

$policies = $transport->xpdo->getCollection('modAccessPolicy');
foreach ($policies as $policy) {
    $pk = isset($map[$policy->get('name')]) ? $map[$policy->get('name')] : 'Administrator';
    $template = $transport->xpdo->getObject('modAccessPolicyTemplate',array('name' => $pk));
    $policy->set('template',$template->get('id'));
    $success = $policy->save();
}
return $success;