<?php
/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceEmptyRecycleBinProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('purge_deleted');
    }
    public function getLanguageTopics() {
        return array('resource');
    }

    public function process() {
        /* get resources */
        $resources = $this->modx->getCollection('modResource',array('deleted' => true));
        $count = count($resources);

        $ids = array();
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            $ids[] = $resource->get('id');
        }

        $this->modx->invokeEvent('OnBeforeEmptyTrash',array(
            'ids' => &$ids,
            'resources' => &$resources,
        ));

        reset($resources);
        $ids = array();
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            if (!$resource->checkPolicy('delete')) continue;

            $resourceGroupResources = $resource->getMany('ResourceGroupResources');
            $templateVarResources = $resource->getMany('TemplateVarResources');

            /** @var modResourceGroupResource $resourceGroupResource */
            foreach ($resourceGroupResources as $resourceGroupResource) {
               $resourceGroupResource->remove();
            }

            /** @var modTemplateVarResource $templateVarResource */
            foreach ($templateVarResources as $templateVarResource) {
                $templateVarResource->remove();
            }

            if ($resource->remove() == false) {
                return $this->failure($this->modx->lexicon('resource_err_delete'));
            } else {
                $ids[] = $resource->get('id');
            }
        }

        $this->modx->invokeEvent('OnEmptyTrash',array(
            'num_deleted' => $count,
            'resources' => &$resources,
            'ids' => &$ids,
        ));

        return $this->success();
    }
}
return 'modResourceEmptyRecycleBinProcessor';