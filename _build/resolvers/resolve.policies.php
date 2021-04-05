<?php
/**
 * Resolve Default Policies to their PolicyTemplates
 *
 * @var xPDOTransport $transport
 */

use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use xPDO\xPDO;

$success= false;

/* map of Policy -> Template */
$map = [
    modAccessPolicy::POLICY_RESOURCE => modAccessPolicyTemplate::TEMPLATE_RESOURCE,
    modAccessPolicy::POLICY_ADMINISTRATOR => modAccessPolicyTemplate::TEMPLATE_ADMINISTRATOR,
    modAccessPolicy::POLICY_CONTENT_EDITOR => modAccessPolicyTemplate::TEMPLATE_ADMINISTRATOR,
    modAccessPolicy::POLICY_DEVELOPER => modAccessPolicyTemplate::TEMPLATE_ADMINISTRATOR,
    modAccessPolicy::POLICY_LOAD_ONLY => modAccessPolicyTemplate::TEMPLATE_OBJECT,
    modAccessPolicy::POLICY_LOAD_LIST_VIEW => modAccessPolicyTemplate::TEMPLATE_OBJECT,
    modAccessPolicy::POLICY_OBJECT => modAccessPolicyTemplate::TEMPLATE_OBJECT,
    modAccessPolicy::POLICY_CONTEXT => modAccessPolicyTemplate::TEMPLATE_CONTEXT,
    modAccessPolicy::POLICY_ELEMENT => modAccessPolicyTemplate::TEMPLATE_ELEMENT,
    modAccessPolicy::POLICY_MEDIA_SOURCE_ADMIN => modAccessPolicyTemplate::TEMPLATE_MEDIA_SOURCE,
    modAccessPolicy::POLICY_MEDIA_SOURCE_USER => modAccessPolicyTemplate::TEMPLATE_MEDIA_SOURCE,
    modAccessPolicy::POLICY_HIDDEN_NAMESPACE => modAccessPolicyTemplate::TEMPLATE_NAMESPACE,
];

$policies = $transport->xpdo->getCollection(modAccessPolicy::class);
foreach ($policies as $policy) {
    if (isset($map[$policy->get('name')])) {
        $template = $transport->xpdo->getObject(modAccessPolicyTemplate::class, ['name' => $map[$policy->get('name')]]);
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
