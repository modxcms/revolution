<?php
/**
 * Assign or unassigns a resource group to a resource.
 *
 * @param integer $id The resource group to assign to.
 * @param integer $resource The modResource ID to associate with.
 * @param boolean $access Either true or false whether the resource has access
 * to the group specified.
 *
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */

class modResourceGroupUpdateFromGridProcessor extends modObjectProcessor {
    /** @var array $languageTopics */
    public $languageTopics = array('save_document');
    /** @var string $permission */
    public $permission = 'resource';
    /** @var modResource $resource */
    public $resource;
    
    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        if (empty($data['id'])) {
            return $this->failure($this->modx->lexicon('resource_group_err_ns'));
        }
        $this->setProperties($data);
        $this->unsetProperty('data');
        return parent::initialize();
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {

        /* get resource */
        $this->resource = $this->getResource();
        if (!is_object($this->resource) || !($this->resource instanceof modResource)) {
            return $this->failure($this->resource);
        }

        /* get resource group */
        $resourceGroup = $this->modx->getObject('modResourceGroup',$this->getProperty('id'));
        if (empty($resourceGroup)) {
            return $this->failure($this->modx->lexicon('resource_group_err_nf'));
        }

        /* get access */
        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
            'document' => $this->resource->get('id'),
            'document_group' => $resourceGroup->get('id'),
        ));

        if ($this->getProperty('access') == true && $resourceGroupResource != null) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_ae'));
        }
        if ($this->getProperty('access') == false && $resourceGroupResource == null) {
            return $this->failure($this->modx->lexicon('resource_group_resource_err_nf'));
        }
        if ($this->getProperty('access') == true) {
            $resourceGroupResource = $this->modx->newObject('modResourceGroupResource');
            $resourceGroupResource->set('document',$this->resource->get('id'));
            $resourceGroupResource->set('document_group',$resourceGroup->get('id'));
            $resourceGroupResource->save();
        } else if ($resourceGroupResource instanceof modResourceGroupResource) {
            $resourceGroupResource->remove();
        }

        return $this->success();
    }

    /**
     * Get the Resource associated
     * 
     * @return modResource|string
     */
    public function getResource() {
        $resource_id = $this->getProperty('resource');
        if (empty($resource_id)) {
            return $this->modx->lexicon('resource_err_ns');
        }
        $this->resource = $this->modx->getObject('modResource',$resource_id);
        if (empty($resourcegroup)) {
            return $this->modx->lexicon('resource_err_nf');
        }
        /* check access */
        if (!$this->resource->checkPolicy('save')) {
            return $this->modx->lexicon('permission_denied');
        }
        return $this->resource;
    }
}
return 'modResourceGroupUpdateFromGridProcessor';
