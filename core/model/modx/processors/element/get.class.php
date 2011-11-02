<?php
abstract class modElementGetProcessor extends modProcessor {
    /** @var modElement $element */
    public $element;
    /** @var string $classKey The class key of the Element to create */
    public $classKey;
    /** @var array $languageTopics An array of language topics to load */
    public $languageTopics = array();
    /** @var string $permission The Permission to use when checking against */
    public $permission = '';
    /** @var string $elementType The element "type", this will be used in various lexicon error strings */
    public $elementType = 'element';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }
    public function getLanguageTopics() {
        return $this->languageTopics;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon($this->elementType.'_err_ns');
        $this->element = $this->modx->getObject($this->classKey,$id);
        if (empty($this->element)) return $this->modx->lexicon($this->elementType.'_err_nfs',array('id' => $id));

        if (!$this->element->checkPolicy('view')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $this->getElementProperties();
        $this->beforeOutput();
        return $this->success('',$this->element->toArray());
    }

    /**
     * Used for adding custom data in derivative types
     * @return void
     */
    public function beforeOutput() {}

    /**
     * Get the properties of the element
     * @return array
     */
    public function getElementProperties() {
        $properties = $this->element->get('properties');
        if (!is_array($properties)) $properties = array();

        /* process data */
        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                $property['type'],
                $property['options'],
                $property['value'],
                $property['lexicon'],
                false, /* overridden set to false */
                $property['desc'],
            );
        }

        $this->element->set('data','(' . $this->modx->toJSON($data) . ')');
        return $data;
    }
}