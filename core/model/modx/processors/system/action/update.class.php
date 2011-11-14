<?php
/**
 * Updates an action
 *
 * @param string $controller The controller location
 * @param boolean $loadheaders Whether or not to load header templates for the
 * action
 * @param string $namespace The namespace for the action
 * @param string $lang_topics The lexicon topics for the action
 * @param string $assets
 * @param integer $parent (optional) The parent for the action. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu','namespace');
    public $permission = 'actions';
    public $objectType = 'action';

    public function beforeSave() {
        $hasLayout = (boolean)$this->getProperty('haslayout');
        $this->object->set('haslayout',$hasLayout);

        $controller = $this->getProperties('controller');
        if (empty($controller)) {
            $this->addFieldError('controller',$this->modx->lexicon('controller_err_ns'));
        }

        /* verify parent */
        $parent = $this->getProperty('parent',null);
        if (!empty($parent)) {
            $parent = $this->modx->getObject('modAction',$parent);
            if (empty($parent)) {
                $this->addFieldError('parent',$this->modx->lexicon('action_parent_err_nf'));
            }
        }

        /* verify namespace */
        $namespace = $this->getProperty('namespace');
        if (empty($namespace)) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        }
        $namespace = $this->modx->getObject('modNamespace',$namespace);
        if (empty($namespace)) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        }

        return parent::beforeSave();
    }
}
return 'modActionUpdateProcessor';