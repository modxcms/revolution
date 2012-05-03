<?php
/**
 * Export a policy template.
 *
 * @package modx
 * @subpackage processors.security.access.policy.template
 */
class modAccessPolicyTemplateExportProcessor extends modObjectExportProcessor {
    public $objectType = 'policy_template';
    public $classKey = 'modAccessPolicyTemplate';
    public $permission = 'policy_template_view';
    public $languageTopics = array('policy');

    public function prepareXml() {
        $this->xml->startElement('policy_template');

        $this->addTemplateGroup();

        $this->xml->writeElement('name',$this->object->get('name'));
        $this->xml->writeElement('description',$this->object->get('description'));
        $this->xml->writeElement('lexicon',$this->object->get('lexicon'));

        $this->addPermissions();

        $this->xml->endElement(); // end policy_template
    }

    public function addTemplateGroup() {
        $templateGroup = $this->object->getOne('TemplateGroup');
        if ($templateGroup) {
            $this->xml->writeElement('template_group',$templateGroup->get('name'));
        }
    }

    public function addPermissions() {
        $this->xml->startElement('permissions');
        $permissions = $this->object->getMany('Permissions');
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
return 'modAccessPolicyTemplateExportProcessor';