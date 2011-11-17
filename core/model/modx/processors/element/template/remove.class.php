<?php
require_once (dirname(dirname(__FILE__)).'/remove.class.php');
/**
 * Deletes a template.
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','tv');
    public $permission = 'delete_template';
    public $objectType = 'template';
    public $beforeRemoveEvent = 'OnBeforeTempFormDelete';
    public $afterRemoveEvent = 'OnTempFormDelete';

    public $TemplateVarTemplates = array();
    
    public function beforeRemove() {
        /* check to make sure it doesn't have any resources using it */
        $resources = $this->modx->getCollection('modResource',array(
            'deleted' => 0,
            'template' => $this->object->get('id'),
        ));
        if (count($resources) > 0) {
            $ds = '';
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $ds .= $resource->get('id').' - '.$resource->get('pagetitle')." <br />\n";
            }
            return $this->modx->lexicon('template_err_in_use').$ds;
        }
        
        /* make sure isn't default template */
        if ($this->object->get('id') == $this->modx->getOption('default_template',null,1)) {
            return $this->modx->lexicon('template_err_default_template');
        }

        $this->TemplateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        return true;
    }

    public function afterRemove() {
        /** @var modTemplateVarTemplate $ttv */
        foreach ($this->TemplateVarTemplates as $ttv) {
            if ($ttv->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('tvt_err_remove'));
            }
        }
    }
}
return 'modTemplateRemoveProcessor';