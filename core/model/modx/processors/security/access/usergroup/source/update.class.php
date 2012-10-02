<?php
/**
 * @package modx
 * @subpackage processors.security.group.source
 */
class modSecurityAccessUserGroupSourceUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'sources.modAccessMediaSource';
    public $languageTopics = array('source','access','user');
    public $permission = 'access_permissions';
    public $objectType = 'source';

    public function beforeSave() {
        $policyId = $this->getProperty('policy');
        $principalId = $this->getProperty('principal');
        $target = $this->getProperty('target');

        if ($principalId == null) {
            $this->addFieldError('principal',$this->modx->lexicon('usergroup_err_ns'));
        }
        if (empty($policyId)) {
            $this->addFieldError('policy',$this->modx->lexicon('access_policy_err_ns'));
        }

        /* validate for invalid data */
        if (!empty($target)) {
            /** @var modMediaSource $mediaSource */
            $mediaSource = $this->modx->getObject('sources.modMediaSource',$target);
            if (empty($mediaSource)) $this->addFieldError('target',$this->modx->lexicon('source_err_nf'));
            if (!$mediaSource->checkPolicy('view')) $this->addFieldError('target',$this->modx->lexicon('access_denied'));
        }

        $policy = $this->modx->getObject('modAccessPolicy',$policyId);
        if (empty($policy)) $this->addFieldError('policy',$this->modx->lexicon('access_policy_err_nf'));

        $alreadyExists = $this->modx->getObject('modAccessCategory',array(
            'principal' => $principalId,
            'principal_class' => 'modUserGroup',
            'target' => $target,
            'policy' => $policyId,
            'context_key' => $this->getProperty('context_key'),
            'id:!=' => $this->object->get('id'),
        ));
        if ($alreadyExists) $this->addFieldError('context_key',$this->modx->lexicon('access_source_err_ae'));

        $this->object->set('principal_class','modUserGroup');
        return parent::beforeSave();
    }
}
return 'modSecurityAccessUserGroupSourceUpdateProcessor';