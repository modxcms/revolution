<?php
require_once (dirname(__DIR__).'/duplicate.class.php');
/**
 * Duplicate a TV
 *
 * @param integer $id The ID of the TV to duplicate
 * @param string $name The name of the new, duplicated TV
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modTemplateVarDuplicateProcessor extends modElementDuplicateProcessor {
    public $classKey = 'modTemplateVar';
    public $languageTopics = array('tv');
    public $permission = 'new_tv';
    public $objectType = 'tv';
    public $captionField = 'caption';

    public function afterSave() {
        $this->duplicateTemplates();
        $this->duplicateResources();
        $this->duplicateResourceGroups();
        $this->duplicateMediaSources();
    }

    /**
     * Duplicate Template associations
     * @return void
     */
    public function duplicateTemplates() {
        $templateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        if (is_array($templateVarTemplates) && !empty($templateVarTemplates)) {
            /** @var modTemplateVarTemplate $templateVarTemplate */
            foreach ($templateVarTemplates as $templateVarTemplate) {
                /** @var modTemplateVarTemplate $newTemplateVarTemplate */
                $newTemplateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
                $newTemplateVarTemplate->set('tmplvarid',$this->newObject->get('id'));
                $newTemplateVarTemplate->set('templateid',$templateVarTemplate->get('templateid'));
                $newTemplateVarTemplate->set('rank',$templateVarTemplate->get('rank'));
                $newTemplateVarTemplate->save();
            }
        }
    }

    /**
     * Duplicate the values of the TV across Resources using it
     * @return void
     */
    public function duplicateResources() {
        if ($this->getProperty('duplicateValues',true)) {
            $templateVarResources = $this->object->getMany('TemplateVarResources');
            if (is_array($templateVarResources) && !empty($templateVarResources)) {
                /** @var modTemplateVarResource $templateVarResource */
                foreach ($templateVarResources as $templateVarResource) {
                    /** @var modTemplateVarResource $newTemplateVarResource */
                    $newTemplateVarResource = $this->modx->newObject('modTemplateVarResource');
                    $newTemplateVarResource->set('tmplvarid',$this->newObject->get('id'));
                    $newTemplateVarResource->set('contentid',$templateVarResource->get('contentid'));
                    $newTemplateVarResource->set('value',$templateVarResource->get('value'));
                    $newTemplateVarResource->save();
                }
            }
        }
    }

    /**
     * Duplicate Resource Group associations
     * @return void
     */
    public function duplicateResourceGroups() {
        $resourceGroups = $this->object->getMany('TemplateVarResourceGroups');
        if (is_array($resourceGroups) && !empty($resourceGroups)) {
            /** @var modTemplateVarResourceGroup $resourceGroup */
            foreach ($resourceGroups as $resourceGroup) {
                /** @var modTemplateVarResourceGroup $newResourceGroup */
                $newResourceGroup = $this->modx->newObject('modTemplateVarResourceGroup');
                $newResourceGroup->set('tmplvarid',$this->newObject->get('id'));
                $newResourceGroup->set('documentgroup',$resourceGroup->get('documentgroup'));
                $newResourceGroup->save();
            }
        }
    }

    /**
     * Duplicate all media source associations
     * @return void
     */
    public function duplicateMediaSources() {
        $sourceElements = $this->modx->getCollection('sources.modMediaSourceElement',array(
            'object' => $this->object->get('id'),
            'object_class' => 'modTemplateVar',
        ));
        if (is_array($sourceElements) && !empty($sourceElements)) {
            /** @var modMediaSourceElement $sourceElement */
            foreach ($sourceElements as $sourceElement) {
                /** @var modMediaSourceElement $newSourceElement */
                $newSourceElement = $this->modx->newObject('sources.modMediaSourceElement');
                $newSourceElement->fromArray(array(
                    'object' => $this->newObject->get('id'),
                    'object_class' => 'modTemplateVar',
                    'context_key' => $sourceElement->get('context_key'),
                    'source' => $sourceElement->get('source'),
                ),'',true,true);
                $newSourceElement->save();
            }
        }
    }

    /**
     * Get the new caption for the duplicate
     * @return string
     */
    public function getNewCaption()
    {
        $caption = $this->getProperty($this->captionField);
        $newCaption = !empty($caption)
            ? $caption
            : $this->modx->lexicon('duplicate_of', array('name' => $this->object->get($this->captionField)));

        return $newCaption;
    }

    /**
     * Set the new caption to the new object
     * @param $caption
     * @return string
     * @internal param string $name
     */
    public function setNewCaption($caption)
    {
        return $this->newObject->set($this->captionField, $caption);
    }

    /**
     * Run any logic before the object has been duplicated. May return false to prevent duplication.
     * @return boolean
     */
    public function beforeSave()
    {
        $caption = $this->getNewCaption();
        $this->setNewCaption($caption);

        return parent::beforeSave();
    }
}

return 'modTemplateVarDuplicateProcessor';
