<?php
/**
 * Duplicates a resource, and optionally, all of its children.
 *
 * @param integer $id The ID of the resource.
 * @param string $name The new name of the resource that will be created.
 * @param boolean $duplicate_children (optional) If true, will duplicate the
 * resource's children as well. Defaults to false.
 * @return array An array of values of the new resource.
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceDuplicateProcessor extends modProcessor {
    /** @var modResource $oldResource */
    public $oldResource;
    /** @var modResource $newResource */
    public $newResource;
    /** @var modResource $parentResource */
    public $parentResource;

    public function checkPermissions() {
        return $this->modx->hasPermission('resource_duplicate');
    }
    public function getLanguageTopics() {
        return array('resource');
    }
    public function initialize() {
        $id = $this->getProperty('id',false);
        if (empty($id)) return $this->modx->lexicon('resource_err_ns');
        $this->oldResource = $this->modx->getObject('modResource',$id);
        if (empty($this->oldResource)) return $this->modx->lexicon('resource_err_nfs',array('id' => $id));

        if (!$this->oldResource->checkPolicy('copy')) {
            return $this->modx->lexicon('permission_denied');
        }
        if ($this->oldResource->parent === (int) $this->modx->getOption('tree_root_id') && !$this->modx->hasPermission('new_document_in_root')) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        if (!$this->checkParentPermissions()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $this->newResource = $this->oldResource->duplicate(array(
            'newName' => $this->getProperty('name',''),
            'duplicateChildren' => $this->getProperty('duplicate_children',false),
            'prefixDuplicate' => $this->getProperty('prefixDuplicate',false),
            'publishedMode' => $this->getProperty('published_mode','preserve'),
        ));
        if (!($this->newResource instanceof modResource)) {
            return $this->failure($this->newResource);
        }

        $this->fireDuplicateEvent();
        $this->logManagerAction();

        return $this->success('', array ('id' => $this->newResource->get('id')));
    }

    /**
     * Ensure the user can add children to the parent
     * @return boolean
     */
    public function checkParentPermissions() {
        $canAddChildren = true;
        $this->parentResource = $this->oldResource->getOne('Parent');
        if ($this->parentResource && !$this->parentResource->checkPolicy('add_children')) {
            $canAddChildren = false;
        }
        return $canAddChildren;
    }

    /**
     * Fire the OnResourceDuplicate event
     * @return void
     */
    public function fireDuplicateEvent() {
        $this->modx->invokeEvent('OnResourceDuplicate',array(
            'newResource' => &$this->newResource,
            'oldResource' => &$this->oldResource,
            'newName' => $this->getProperty('name',''),
            'duplicateChildren' => $this->getProperty('duplicate_children',false),
            'prefixDuplicate' => $this->getProperty('prefixDuplicate',false),
            'publishedMode' => $this->getProperty('published_mode','preserve'),
        ));
    }

    /**
     * Log the manager action
     *
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('duplicate_resource',$this->newResource->get('class_key'),$this->newResource->get('id'));
    }
}
return 'modResourceDuplicateProcessor';
