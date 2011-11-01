<?php
/**
 * Updates a Media Source
 *
 * @param integer $id The ID of the Source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceUpdateProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;

    public function checkPermissions() {
        return $this->modx->hasPermission('source_save');
    }

    public function getLanguageTopics() {
        return array('source');
    }

    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('source_err_ns');
        /** @var modMediaSource $source */
        $this->source = $this->modx->getObject('sources.modMediaSource',$id);
        if (empty($this->source)) {
            return $this->modx->lexicon('source_err_nf',array('id' => $id));
        }
        if (!$this->source->checkPolicy('save')) {
            return $this->modx->lexicon('permission_denied');
        }

        return true;
    }

    public function process() {
        $this->source->fromArray($this->getProperties());

        $this->setSourceProperties();

        if ($this->source->save() == false) {
            return $this->failure($this->modx->lexicon('source_err_save'));
        }

        $this->setAccess();
        $this->logManagerAction();
        return $this->success('',$this->source);
    }

    /**
     * Sets the properties on the source
     * @return void
     */
    public function setSourceProperties() {
        $properties = $this->getProperty('properties');
        if (!empty($properties)) {
            $properties = is_array($properties) ? $properties : $this->modx->fromJSON($properties);
            $this->source->setProperties($properties);
        }
    }

    /**
     * Sets access permissions for the source
     * @return void
     */
    public function setAccess() {
        $access = $this->getProperty('access');
        if (!empty($access)) {
            $acls = $this->modx->getCollection('sources.modAccessMediaSource',array(
                'target' => $this->source->get('id'),
            ));
            /** @var modAccessMediaSource $acl */
            foreach ($acls as $acl) {
                $acl->remove();
            }

            $access = is_array($access) ? $access : $this->modx->fromJSON($access);
            if (!empty($access) && is_array($access)) {
                foreach ($access as $data) {
                    $acl = $this->modx->newObject('sources.modAccessMediaSource');
                    $acl->fromArray(array(
                        'target' => $this->source->get('id'),
                        'principal_class' => $data['principal_class'],
                        'principal' => $data['principal'],
                        'authority' => $data['authority'],
                        'policy' => $data['policy'],
                        'context_key' => $data['context_key'],
                    ),'',true,true);
                    $acl->save();
                }
            }
        }
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('source_update','sources.modMediaSource',$this->source->get('id'));
    }
}
return 'modSourceUpdateProcessor';