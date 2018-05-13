<?php
/**
 * Resolve PolicyTemplates to their PolicyTemplateGroups
 *
 * @package modx
 * @subpackage build
 */
$success = false;

/* map of Template -> TemplateGroup */
$map = [
    'ResourceTemplate' => 'Resource',
    'AdministratorTemplate' => 'Admin',
    'ObjectTemplate' => 'Object',
    'ContextTemplate' => 'Object',
    'ElementTemplate' => 'Element',
    'MediaSourceTemplate' => 'MediaSource',
    'NamespaceTemplate' => 'Namespace',
];

$templates = $transport->xpdo->getCollection('MODX\modAccessPolicyTemplate');
foreach ($templates as $template) {
    if (isset($map[$template->get('name')])) {
        $templateGroup = $transport->xpdo->getObject('MODX\modAccessPolicyTemplateGroup', ['name' => $map[$template->get('name')]]);
        if ($templateGroup) {
            $template->set('template_group', $templateGroup->get('id'));
            $success = $template->save();
        } else {
            $transport->xpdo->log(xPDO\xPDO::LOG_LEVEL_ERROR, "Core AccessPolicyTemplateGroup {$map[$template->get('name')]} is missing!");
        }
    } else {
        $success = true;
    }
}

return $success;