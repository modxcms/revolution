<?php
include_once dirname(__FILE__) . '/update.class.php';
/**
 * Saves a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetUpdateFromElementProcessor extends modPropertySetUpdateProcessor {
    public $languageTopics = array('propertyset', 'category', 'element');

    /** @var modPropertySet|null */
    public $object;

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize() {
        
        /*
             Determine whether the transferred parameter set ID.
             If so, then the class of the current object left without changes modPropertySet
             If not, change the class of the class of the updated element.
             That is the current element $this->object as the case may be, or modPropertySet or modTemplate | modSnippet etc.
        */

        $id = (int)$this->getProperty($this->primaryKeyField);
        if(!$id){
            $elementType = $this->getProperty('elementType');
            if(!$elementType){
                return $this->modx->lexicon('propertysets_err_item_class_ns');
            }
            
            $this->classKey = $elementType;
            $id = (int)$this->getProperty('elementId');
        }
        
        $this->setProperty($this->primaryKeyField, $id);
        
        return parent::initialize();
    }

    /**
     * Return data as array
     * @return mixed
     */
    public function getData() {
        return $this->modx->fromJSON($this->getProperty('data'));
    }

    public function beforeSet() {
        
        /*
            It is necessary to set these values as their parent processor frays,
            if they were not transferred to the parameters
        */
        
        $this->setDefaultProperties(array(
            'name'  => $this->object->get('name'),
            'category'  => $this->object->get('category'),
        ));
        
        return parent::beforeSet();
    }
    
    /**
     * Convert JSON data to array and unset default properties
     * @return bool
     */
    public function beforeSave() {
        
        $this->object->setProperties($this->getData());
        
        return parent::beforeSave();
    }
    
    /**
     * Log the property set update from element manager action
     * @return void
     */
    public function logManagerAction() {
        $key = $this->object ? $this->object->get($this->primaryKeyField) :
            $this->getProperty('elementType') . ' ' . $this->getProperty('elementId') .  ' Default';
        $this->modx->logManagerAction($this->objectType.'_update_from_element', $this->classKey, $key);
    }
    
    public function cleanup(){
        return $this->success('');
    }
}

return 'modPropertySetUpdateFromElementProcessor';
