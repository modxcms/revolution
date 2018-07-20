<?php
/**
 * Resolve PolicyTemplates to their PolicyTemplateGroups
 *
 * @var xPDOTransport $transport
 * @package    modx
 * @subpackage build
 */

use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use xPDO\xPDO;

$success = false;

/* map of Template -> TemplateGroup */
$map = [
    modAccessPolicyTemplate::TEMPLATE_RESOURCE => modAccessPolicyTemplateGroup::GROUP_RESOURCE,
    modAccessPolicyTemplate::TEMPLATE_ADMINISTRATOR => modAccessPolicyTemplateGroup::GROUP_ADMINISTRATOR,
    modAccessPolicyTemplate::TEMPLATE_OBJECT => modAccessPolicyTemplateGroup::GROUP_OBJECT,
    modAccessPolicyTemplate::TEMPLATE_CONTEXT => modAccessPolicyTemplateGroup::GROUP_CONTEXT,
    modAccessPolicyTemplate::TEMPLATE_ELEMENT => modAccessPolicyTemplateGroup::GROUP_ELEMENT,
    modAccessPolicyTemplate::TEMPLATE_MEDIA_SOURCE => modAccessPolicyTemplateGroup::GROUP_MEDIA_SOURCE,
    modAccessPolicyTemplate::TEMPLATE_NAMESPACE => modAccessPolicyTemplateGroup::GROUP_NAMESPACE,
];

$templates = $transport->xpdo->getIterator(modAccessPolicyTemplate::class);

/** @var modAccessPolicyTemplate $template */
foreach ($templates as $template) {
    if (isset($map[$template->get('name')])) {
        $templateGroup = $transport->xpdo->getObject(
            modAccessPolicyTemplateGroup::class,
            ['name' => $map[$template->get('name')]]
        );
        if ($templateGroup) {
            $template->set('template_group', $templateGroup->get('id'));
            $success = $template->save();
        } else {
            $transport->xpdo->log(
                xPDO::LOG_LEVEL_ERROR,
                "Core AccessPolicyTemplateGroup {$map[$template->get('name')]} is missing!"
            );
        }
    } else {
        $success = true;
    }
}

return $success;
