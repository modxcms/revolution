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
    'Hidden Namespace' => 'NamespaceTemplate',
);

$policies = $transport->xpdo->getCollection('modAccessPolicy');
foreach ($policies as $policy) {
    if (isset($map[$policy->get('name')])) {
        $template = $transport->xpdo->getObject('modAccessPolicyTemplate',array('name' => $map[$policy->get('name')]));
        if ($template) {
            $policy->set('template',$template->get('id'));
            $success = $policy->save();
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Core AccessPolicyTemplate {$map[$policy->get('name')]} is missing! Could not resolve AccessPolicy {$policy->get('name')}.");
        }
    } else {
        $success = true;
    }
}
return $success;