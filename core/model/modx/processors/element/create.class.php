<?php
/**
 * Abstract class for Create Element processors. To be extended for each derivative element type.
 * 
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementCreateProcessor extends modProcessor {
    /** @var modElement $element */
    public $element;
    /** @var string $classKey The class key of the Element to create */
    public $classKey;
    /** @var array $languageTopics An array of language topics to load */
    public $languageTopics = array();
    /** @var string $permission The Permission to use when checking against */
    public $permission = '';
    /** @var string $managerAction The manager action to log after creation */
    public $managerAction = 'element_create';
    /** @var string $elementType The element "type", this will be used in various lexicon error strings */
    public $elementType = 'element';
    /** @var string $eventBeforeSave The before-save Event name */
    public $eventBeforeSave = 'OnElementBeforeSave';
    /** @var string $eventAfterSave The after-save event name */
    public $eventAfterSave = 'OnElementSave';

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
        $this->element = $this->modx->newObject($this->classKey);
        return true;
    }

    /**
     * Process the Element processor
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if (!$this->validate()) {
            return $this->failure();
        }

        return $this->saveElement();
    }

    /**
     * Save the Element
     * @return array|string
     */
    public function saveElement() {
        $canSave = $this->fireBeforeSave();
        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        $canSave = $this->preSaveElement();
        if (empty($canSave)) {
            return $this->failure($canSave);
        }
        
        /* save element */
        if ($this->element->save() == false) {
            return $this->failure($this->modx->lexicon($this->elementType.'_err_save'));
        }

        $this->postSaveElement();

        $this->fireAfterSave();
        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function postSaveElement() { return true; }
    
    /**
     * Override in your derivative class to do functionality before save() is run
     * @return boolean
     */
    public function preSaveElement() { return true; }

    /**
     * Cleanup the process and send back the response
     * @return array
     */
    public function cleanup() {
        $this->clearCache();
        $fields = array('id', 'description', 'locked', 'category');
        array_push($fields,($this->classKey == 'modTemplate' ? 'templatename' : 'name'));
        return $this->success('',$this->element->get($fields));
    }

    /**
     * Validate the form
     * @return boolean
     */
    public function validate() {
        $name = $this->getProperty('name');

        /* verify element with that name does not already exist */
        if ($this->alreadyExists($name)) {
            $this->addFieldError('name',$this->modx->lexicon($this->elementType.'_err_exists_name',array(
                'name' => $name,
            )));
        }

        $category = $this->getProperty('category',0);
        if (!empty($category)) {
            /** @var modCategory $category */
            $category = $this->modx->getObject('modCategory',array('id' => $category));
            if (empty($category)) {
                $this->addFieldError('category',$this->modx->lexicon('category_err_nf'));
            }
            if (!$category->checkPolicy('add_children')) {
                $this->addFieldError('category',$this->modx->lexicon('access_denied'));
            }
        }

        $this->element->fromArray($this->getProperties());
        $locked = (boolean)$this->getProperty('locked',false);
        $this->element->set('locked',$locked);

        $this->setElementProperties();
        $this->validateElement();

        return !$this->hasErrors();
    }

    /**
     * Check to see if a Chunk already exists with specified name
     * @param string $name
     * @return bool
     */
    public function alreadyExists($name) {
        if ($this->classKey == 'modTemplate') {
            $c = array('templatename' => $name);
        } else {
            $c = array('name' => $name);
        }
        return $this->modx->getCount($this->classKey,$c) > 0;
    }

    /**
     * Set the properties on the Element
     * @return mixed
     */
    public function setElementProperties() {
        $properties = null;
        $propertyData = $this->getProperty('propdata',null);
        if ($propertyData != null && is_string($propertyData)) {
            $propertyData = $this->modx->fromJSON($propertyData);
        }
        if (is_array($propertyData)) {
            $this->element->setProperties($propertyData);
        }
        return $propertyData;
    }

    /**
     * Run object-level validation on the element
     * @return void
     */
    public function validateElement() {
        if (!$this->element->validate()) {
            /** @var modValidator $validator */
            $validator = $this->element->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }
    }

    /**
     * @return boolean
     */
    public function fireBeforeSave() {
        /** @var boolean|array $OnBeforeElementFormSave */
        $OnBeforeElementFormSave = $this->modx->invokeEvent($this->eventBeforeSave,array(
            'mode'  => modSystemEvent::MODE_NEW,
            'id' => 0,
            'data' => $this->element->toArray(),
            $this->elementType => &$this->element,
        ));
        if (is_array($OnBeforeElementFormSave)) {
            $canSave = false;
            foreach ($OnBeforeElementFormSave as $msg) {
                if (!empty($msg)) {
                    $canSave .= $msg."\n";
                }
            }
        } else {
            $canSave = $OnBeforeElementFormSave;
        }
        return $canSave;
    }

    /**
     * Fire the after save event
     * @return void
     */
    public function fireAfterSave() {
        $this->modx->invokeEvent('OnChunkFormSave',array(
            'mode' => modSystemEvent::MODE_NEW,
            'id'   => $this->element->get('id'),
            $this->elementType => &$this->element,
        ));
    }

    /**
     * Log the manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction($this->managerAction,$this->classKey,$this->element->get('id'));
    }

    /**
     * Clear the cache post-save
     * @return void
     */
    public function clearCache() {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }
    }
}