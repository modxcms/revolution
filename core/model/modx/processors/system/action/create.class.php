<?php
/**
 * Create an action
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
class modActionCreateProcessor extends modProcessor {
    /** @var modAction $action */
    public $action;

    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }
    public function getLanguageTopics() {
        return array('action','menu','namespace');
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function initialize() {
        $id = $this->getProperty('id');
        if (empty($id)) return $this->modx->lexicon('action_err_ns');
        $this->action = $this->modx->getObject('modAction',$id);
        if (empty($this->action)) return $this->modx->lexicon('action_err_nf');
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return array|string
     */
    public function process() {
        $fields = $this->getProperties();
        $fields['haslayout'] = !empty($fields['haslayout']);

        if (!$this->validate($fields)) {
            return $this->failure();
        }

        /* @var modAction $action */
        $this->action = $this->modx->newObject('modAction');
        $this->action->fromArray($fields);
        if (empty($fields['lang_topics'])) {
            $action->set('lang_topics','');
        }
        if ($this->action->save() == false) {
            return $this->failure($this->modx->lexicon('action_err_create'));
        }
        
        $this->logManagerAction();

        return $this->success('',$this->action);
    }

    /**
     * Validate the incoming data
     * @param array $fields
     * @return boolean
     */
    public function validate(array $fields) {
        if (empty($fields['controller'])) {
            $this->addFieldError('controller',$this->modx->lexicon('controller_err_ns'));
        }

        /* verify parent */
        if (!isset($fields['parent'])) {
            $this->addFieldError('parent',$this->modx->lexicon('action_parent_err_ns'));
        } else if (!empty($fields['parent'])) {
            $parent = $this->modx->getObject('modAction',$fields['parent']);
            if (empty($parent)) {
                $this->addFieldError('parent',$this->modx->lexicon('action_parent_err_nf'));
            }
        }

        /* verify namespace */
        if (empty($fields['namespace'])) $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        $namespace = $this->modx->getObject('modNamespace',$fields['namespace']);
        if (empty($namespace)) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        }

        return !$this->hasErrors();
    }

    /**
     * Get the parent action and set fields if found
     *
     * @return null|modAction
     */
    public function getParent() {
        /* get parent */
        $parent = $this->action->getOne('Parent');
        if ($parent != null) {
            $this->action->set('parent',$parent->get('id'));
            $this->action->set('parent_controller',$parent->get('controller'));
        }
        return $parent;
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('action_create','modAction',$this->action->get('id'));
    }
}
return 'modActionCreateProcessor';