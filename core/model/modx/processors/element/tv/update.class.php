<?php
/**
 * Updates a TV
 *
 * @param string $name The name of the TV
 * @param string $caption (optional) A short caption for the TV.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param string $els (optional)
 * @param integer $rank (optional) The rank of the TV
 * @param string $display (optional) The type of output render
 * @param string $display_params (optional) Any display rendering parameters
 * @param string $default_text (optional) The default value for the TV
 * @param json $templates (optional) Templates associated with the TV
 * @param json $resource_groups (optional) Resource Groups associated with the
 * TV.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modElementTvUpdateProcessor extends modProcessor {
    /** @var modTemplateVar $tv */
    public $tv;
    /** @var modCategory $category */
    public $category;

    public function checkPermissions() {
        return $this->modx->hasPermission('save_tv');
    }
    public function getLanguageTopics() {
        return array('tv','category');
    }

    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('tv_err_ns');
        $this->tv = $this->modx->getObject('modTemplateVar',$id);
        if (empty($this->tv)) return $this->modx->lexicon('tv_err_nf');

        if (!$this->tv->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        if ($this->isLocked()) {
            $this->failure($this->modx->lexicon('tv_err_locked'));
        }

        /* category */
        $this->getCategory();

        $fields = $this->getProperties();
        $fields = $this->preFormat($fields);

        if (!$this->validate($fields)) {
            return $this->failure();
        }

        $previousCategory = $this->tv->get('category');
        $this->tv->fromArray($fields);

        /* validate TV via model */
        if (!$this->tv->validate()) {
            /** @var modValidator $validator */
            $validator = $this->tv->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }

        /* if error, return */
        if ($this->hasErrors()) {
            return $this->failure();
        }

        $canSave = $this->fireBeforeSave();
        if (!empty($canSave)) {
            return $this->failure($canSave);
        }

        /* save TV */
        if ($this->tv->save() === false) {
            return $this->failure($this->modx->lexicon('tv_err_save'));
        }

        $this->setTemplateAccess();
        $this->setResourceGroups();
        $this->setMediaSources();
        $this->fireOnSave();
        $this->logManagerAction();

        /* empty cache */
        if (!empty($fields['clearCache'])) {
            $this->modx->cacheManager->refresh();
        }

        return $this->success('', array('previous_category' => $previousCategory));
    }

    /**
     * Pre-format incoming data to properly be set
     * @param array $fields
     * @return array
     */
    public function preFormat(array $fields) {
        if (empty($fields['name'])) $fields['name'] = $this->modx->lexicon('untitled_tv');

        $fields['output_properties'] = array();
        $outputPropertyFound = false;
        foreach ($fields as $key => $value) {
            $res = strstr($key,'prop_');
            if ($res !== false) {
                $fields['output_properties'][str_replace('prop_','',$key)] = $value;
                $outputPropertyFound = true;
            }
        }
        if (!$outputPropertyFound) {
            unset($fields['output_properties']);
        }

        $fields['input_properties'] = array();
        $inputPropertyFound = false;
        foreach ($fields as $key => $value) {
            $res = strstr($key,'inopt_');
            if ($res !== false) {
                $fields['input_properties'][str_replace('inopt_','',$key)] = $value;
                $inputPropertyFound = true;
            }
        }
        if (!$inputPropertyFound) {
            unset($fields['input_properties']);
        }


        if (isset($fields['els'])) {
            $fields['elements'] = $fields['els'];
            unset($fields['els']);
        }
        if (isset($fields['locked'])) {
            $fields['locked'] = !empty($fields['locked']);
        }
        return $fields;
    }

    /**
     * Validate form fields
     *
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        /* check to make sure name doesn't already exist */
        if ($this->alreadyExists($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('tv_err_exists_name',array('name' => $fields['name'])));
        }
        return !$this->hasErrors();
    }

    /**
     * See if a TV already exists with the new name
     * @param string $name
     * @return bool
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modTemplateVar',array(
            'id:!=' => $this->tv->get('id'),
            'name' => $name,
        )) > 0;
    }

    /**
     * Fire the OnBeforeTVFormSave event and see if it prevents saving
     *
     * @return mixed
     */
    public function fireBeforeSave() {
        /** @var array|string $OnBeforeTVFormSave */
        $OnBeforeTVFormSave = $this->modx->invokeEvent('OnBeforeTVFormSave',array(
            'mode' => modSystemEvent::MODE_UPD,
            'id' => $this->tv->get('id'),
            'tv' => &$this->tv,
        ));
        if (is_array($OnBeforeTVFormSave)) {
            $canSave = false;
            foreach ($OnBeforeTVFormSave as $msg) {
                if (!empty($msg)) {
                    $canSave .= $msg."\n";
                }
            }
        } else {
            $canSave = $OnBeforeTVFormSave;
        }
        return $canSave;
    }

    /**
     * Fire OnTVFormSave event
     * @return void
     */
    public function fireOnSave() {
        /* invoke OnTVFormSave event */
        $this->modx->invokeEvent('OnTVFormSave',array(
            'mode' => modSystemEvent::MODE_UPD,
            'id' => $this->tv->get('id'),
            'tv' => &$this->tv,
        ));
    }

    /**
     * Get the specified Category object
     * @return modCategory
     */
    public function getCategory() {
        $category = $this->getProperty('category');
        if (!empty($category)) {
            $this->category = $this->modx->getObject('modCategory',array('id' => $category));
            if (empty($this->category)) {
                $this->addFieldError('category',$this->modx->lexicon('category_err_nf'));
            }
        }
        return $this->category;
    }

    /**
     * See if this element is locked for editing
     * @return boolean
     */
    public function isLocked() {
        return $this->tv->get('locked') && $this->modx->hasPermission('edit_locked') == false;
    }

    /**
     * Set the Templates, if passed, for the TV
     * @return void
     */
    public function setTemplateAccess() {
        $templates = $this->getProperty('templates',false);
        /* change template access to tvs */
        if (!empty($templates)) {
            $templateVariables = $this->modx->fromJSON($templates);
            if (is_array($templateVariables)) {
                foreach ($templateVariables as $id => $template) {
                    if (!is_array($template)) continue;

                    if ($template['access']) {
                        /** @var modTemplateVarTemplate $templateVarTemplate */
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
                            'tmplvarid' => $this->tv->get('id'),
                            'templateid' => $template['id'],
                        ));
                        if (empty($templateVarTemplate)) {
                            $templateVarTemplate = $this->modx->newObject('modTemplateVarTemplate');
                        }
                        $templateVarTemplate->set('tmplvarid',$this->tv->get('id'));
                        $templateVarTemplate->set('templateid',$template['id']);
                        $templateVarTemplate->save();
                    } else {
                        $templateVarTemplate = $this->modx->getObject('modTemplateVarTemplate',array(
                            'tmplvarid' => $this->tv->get('id'),
                            'templateid' => $template['id'],
                        ));
                        if ($templateVarTemplate && $templateVarTemplate instanceof modTemplateVarTemplate) {
                            $templateVarTemplate->remove();
                        }
                    }
                }
            }
        }
    }

    /**
     * Set the Resource Groups, if passed, for the TV
     * @return void
     */
    public function setResourceGroups() {
        if ($this->modx->hasPermission('access_permissions')) {
            $resourceGroups = $this->getProperty('resource_groups',false);
            if (!empty($resourceGroups)) {
                $docgroups = $this->modx->fromJSON($resourceGroups);
                if (!is_array($docgroups)) {
                    foreach ($docgroups as $id => $group) {
                        if (!is_array($group)) continue;
                        /** @var modTemplateVarResourceGroup $templateVarResourceGroup */
                        $templateVarResourceGroup = $this->modx->getObject('modTemplateVarResourceGroup',array(
                            'tmplvarid' => $this->tv->get('id'),
                            'documentgroup' => $group['id'],
                        ));

                        if ($group['access'] == true) {
                            if (!empty($templateVarResourceGroup)) continue;
                            $templateVarResourceGroup = $this->modx->newObject('modTemplateVarResourceGroup');
                            $templateVarResourceGroup->set('tmplvarid',$this->tv->get('id'));
                            $templateVarResourceGroup->set('documentgroup',$group['id']);
                            $templateVarResourceGroup->save();
                        } else if ($templateVarResourceGroup && $templateVarResourceGroup instanceof modTemplateVarResourceGroup) {
                            $templateVarResourceGroup->remove();
                        }
                    }
                }
            }
        }
    }

    /**
     * Set the Media Source attributions, if passed, for the TV
     * @return void
     */
    public function setMediaSources() {
        $sources = $this->getProperty('sources',false);
        if (!empty($sources)) {
            $sources = $this->modx->fromJSON($sources);
            if (is_array($sources)) {
                $sourceElements = $this->modx->getCollection('sources.modMediaSourceElement',array(
                    'object' => $this->tv->get('id'),
                    'object_class' => 'modTemplateVar',
                ));
                /** @var modMediaSourceElement $sourceElement */
                foreach ($sourceElements as $sourceElement) {
                    $sourceElement->remove();
                }

                foreach ($sources as $id => $source) {
                    if (!is_array($source)) continue;

                    /** @var modMediaSourceElement $sourceElement */
                    $sourceElement = $this->modx->getObject('sources.modMediaSourceElement',array(
                        'object' => $this->tv->get('id'),
                        'object_class' => $this->tv->_class,
                        'context_key' => $source['context_key'],
                    ));
                    if (!$sourceElement) {
                        $sourceElement = $this->modx->newObject('sources.modMediaSourceElement');
                        $sourceElement->fromArray(array(
                            'object' => $this->tv->get('id'),
                            'object_class' => $this->tv->_class,
                            'context_key' => $source['context_key'],
                        ),'',true,true);
                    }
                    $sourceElement->set('source',$source['source']);
                    $sourceElement->save();
                }
            }
        }
    }

    /**
     * Log the manager action for the processor
     * 
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('tv_update','modTemplateVar',$this->tv->get('id'));
    }
}
return 'modElementTvUpdateProcessor';