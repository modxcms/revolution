<?php

/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashPurgeProcessor extends modProcessor {

    /** @var modResource $resource */
    public $resource;

    /**
     * @var int The id of the resource to be deleted.
     */
    public $id;


    public function checkPermissions() {
        return $this->modx->hasPermission( 'purge_deleted' );
    }

    public function getLanguageTopics() {
        return array( 'resource' );
    }

    /**
     * @return bool|null|string
     */
    public function initialize() {
        $this->id = $this->getProperty( 'id', false );
        if ( empty( $this->id ) ) return $this->modx->lexicon( 'resource_err_ns' );

        $this->resource = $this->modx->getObject( 'modResource', $this->id );
        if ( empty( $this->resource ) ) return $this->modx->lexicon( 'resource_err_nfs', array( 'id' => $this->id ) );

        /* validate resource can be deleted */
        //if (!$this->resource->checkPolicy(array('save' => true, 'delete' => true))) {
        //    return $this->modx->lexicon('permission_denied');
        //}
        return true;
    }

    public function process() {
        /* get resources */
        $id = $this->getProperty( 'id', false );
        if ( empty( $id ) ) return $this->modx->lexicon( 'resource_err_ns' );

        $resources = $this->modx->getCollection( 'modResource', array(
                'deleted' => true,
                'id:IN' => explode(',',$id ))
        );
        $count = count( $resources );
        $this->modx->log( 1, "Resources found: " . $count );

        //$this->resource = $this->modx->getObject('modResource', $id);

        // prepare for multiple purge at once
        $ids = array();
        /** @var modResource $resource */
        foreach ( $resources as $resource ) {
            $ids[] = $resource->get( 'id' );
        }

        $this->modx->invokeEvent( 'OnBeforeEmptyTrash', array(
            'ids' => &$ids,
            'resources' => &$resources,
        ) );

        reset( $resources );
        $ids = array();
        /** @var modResource $resource */
        foreach ( $resources as $resource ) {
            if ( !$resource->checkPolicy( 'delete' ) ) continue;

            $resourceGroupResources = $resource->getMany( 'ResourceGroupResources' );
            $templateVarResources = $resource->getMany( 'TemplateVarResources' );

            /** @var modResourceGroupResource $resourceGroupResource */
            foreach ( $resourceGroupResources as $resourceGroupResource ) {
                $resourceGroupResource->remove();
            }

            /** @var modTemplateVarResource $templateVarResource */
            foreach ( $templateVarResources as $templateVarResource ) {
                $templateVarResource->remove();
            }

            if ( $resource->remove() == false ) {
                return $this->failure( $this->modx->lexicon( 'resource_err_delete' ) );
            } else {
                $ids[] = $resource->get( 'id' );
            }
        }

        $this->modx->invokeEvent( 'OnEmptyTrash', array(
            'num_deleted' => $count,
            'resources' => &$resources,
            'ids' => &$ids,
        ) );

        $this->modx->logManagerAction( 'empty_trash', 'modResource', implode( ',', $ids ) );

        return $this->success();
    }
}

return 'modResourceTrashPurgeProcessor';