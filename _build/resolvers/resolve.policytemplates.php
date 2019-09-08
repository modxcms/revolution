<?php
/**
 * Resolve PolicyTemplates to their PolicyTemplateGroups
 *
 * @var xPDOTransport $transport
 * @package modx
 * @subpackage build
 */

use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAccessPolicyTemplateGroup;
use xPDO\xPDO;

$success= false;

/* map of Template -> TemplateGroup */
$map = array(
    'ResourceTemplate' => 'Resource',
    'AdministratorTemplate' => 'Admin',
    'ObjectTemplate' => 'Object',
    'ContextTemplate' => 'Object',
    'ElementTemplate' => 'Element',
    'MediaSourceTemplate' => 'MediaSource',
    'NamespaceTemplate' => 'Namespace',
);

$templates = $transport->xpdo->getCollection(modAccessPolicyTemplate::class);
foreach ($templates as $template) {
    if (isset($map[$template->get('name')])) {
        $templateGroup = $transport->xpdo->getObject(modAccessPolicyTemplateGroup::class,array('name' => $map[$template->get('name')]));
        if ($templateGroup) {
            $template->set('template_group',$templateGroup->get('id'));
            $success = $template->save();
        } else {
            $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Core AccessPolicyTemplateGroup {$map[$template->get('name')]} is missing!");
        }
    } else {
        $success = true;
    }
}
return $success;
