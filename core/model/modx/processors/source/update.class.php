<?php
/**
 * Updates a Media Source
 *
 * @param integer $id The ID of the Source
 *
 * @package modx
 * @subpackage processors.source
 */
class modSourceUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'sources.modMediaSource';
    public $languageTopics = array('source');
    public $permission = 'source_save';
    public $objectType = 'source';
    public $beforeSaveEvent = 'OnMediaSourceBeforeFormSave';
    public $afterSaveEvent = 'OnMediaSourceFormSave';

    /** @var modMediaSource $object */
    public $object;

    public function beforeSave() {
        $this->setSourceProperties();
        return parent::beforeSave();
    }

    public function afterSave() {
       $this->setAccess();
       return parent::afterSave();
    }

    /**
     * Sets the properties on the source
     * @return void
     */
    public function setSourceProperties() {
        $properties = $this->getProperty('properties');
        if (!empty($properties)) {
            $properties = is_array($properties) ? $properties : $this->modx->fromJSON($properties);
            $this->object->setProperties($properties);
        }
    }

    /**
     * Sets access permissions for the source
     * @return void
     */
    public function setAccess() {
        $access = $this->getProperty('access',null);
        if ($access !== null) {
            $acls = $this->modx->getCollection('sources.modAccessMediaSource',array(
                'target' => $this->object->get('id'),
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
                        'target' => $this->object->get('id'),
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
}
return 'modSourceUpdateProcessor';