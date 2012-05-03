<?php
/**
 * Export a policy template.
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyExportProcessor extends modObjectExportProcessor {
    public $objectType = 'policy';
    public $classKey = 'modAccessPolicy';
    public $permission = 'policy_view';
    public $languageTopics = array('policy');

    public function prepareXml() {
        $this->xml->startElement('policy');

        $this->addTemplate();

        $this->xml->writeElement('name',$this->object->get('name'));
        $this->xml->writeElement('description',$this->object->get('description'));
        $this->xml->writeElement('class',$this->object->get('class'));

        $this->addPermissions();

        $this->xml->endElement(); // end policy
    }

    public function addTemplate() {
        /** @var modAccessPolicyTemplate $template */
        $template = $this->object->getOne('Template');
        if ($template) {
            $this->xml->startElement('template');
            $this->xml->writeElement('name',$template->get('name'));
            $this->xml->writeElement('description',$template->get('description'));
            $this->xml->writeElement('lexicon',$template->get('lexicon'));

            $templateGroup = $template->getOne('TemplateGroup');
            if ($templateGroup) {
                $this->xml->writeElement('template_group',$templateGroup->get('name'));
            }

            $this->addTemplatePermissions($template);

            $this->xml->endElement();
        }
    }

    public function addPermissions() {
        $permissions = $this->object->get('data');

        $this->xml->startElement('permissions');
        foreach ($permissions as $k => $v) {
            $this->xml->startElement('permission');
            $this->xml->writeElement('name',$k);
            $this->xml->writeElement('value',$v);
            $this->xml->endElement();
        }
        $this->xml->endElement();
    }

    public function addTemplatePermissions(modAccessPolicyTemplate $template) {
        $this->xml->startElement('permissions');
        $permissions = $template->getMany('Permissions');
        /** @var modAccessPermission $permission */
        foreach ($permissions as $permission) {
            $this->xml->startElement('permission');
            $this->xml->writeElement('name',$permission->get('name'));
            $this->xml->writeElement('description',$permission->get('description'));
            $this->xml->writeElement('value',$permission->get('value'));
            $this->xml->endElement();
        }
        $this->xml->endElement();
    }
}
return 'modAccessPolicyExportProcessor';