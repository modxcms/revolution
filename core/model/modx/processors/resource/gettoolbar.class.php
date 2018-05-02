<?php
/**
 * Gets a dynamic toolbar for the Resource tree.
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
class modResourceGetToolbarProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('resource_tree');
    }

    public function getLanguageTopics() {
        return array('resource');
    }

    public function process() {
        $p = $this->modx->getOption('manager_url').'templates/default/images/restyle/icons/';
        
        $items = array();

        $items[] = '-';

        if ($this->modx->hasPermission('new_document')) {
            $record = '{context_key: \"' . $this->modx->getOption('default_context') . '\", parent: 0}';
            
            $items[] = array(
                'cls'       => 'tree-new-resource',
                'tooltip'   => $this->modx->lexicon('document_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        if ($this->modx->hasPermission('new_weblink')) {
            $record = '{class_key: \"modWebLink\", context_key: \"' . $this->modx->getOption('default_context') . '\", parent: 0}';
            
            $items[] = array(
                'cls'       => 'tree-new-weblink',
                'tooltip'   => $this->modx->lexicon('add_weblink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        if ($this->modx->hasPermission('new_symlink')) {
            $record = '{class_key: \"modSymLink\", context_key: \"' . $this->modx->getOption('default_context') . '\", parent: 0}';
            
            $items[] = array(
                'cls'       => 'tree-new-symlink',
                'tooltip'   => $this->modx->lexicon('add_symlink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        if ($this->modx->hasPermission('new_static_resource')) {
            $record = '{class_key: \"modSymLink\", context_key: \"' . $this->modx->getOption('default_context') . '\", parent: 0}';
            
            $items[] = array(
                'cls'       => 'tree-new-static-resource',
                'tooltip'   => $this->modx->lexicon('static_resource_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }

        $items[] = '->';
        
        if ($this->modx->hasPermission('purge_deleted')) {
            $deletedResources = $this->modx->getCount('modResource', array(
                'deleted' => 1
            ));

            $items[] = array(
                'id'        => 'emptifier',
                'cls'       => 'tree-trash',
                'tooltip'   => $this->modx->lexicon('empty_recycle_bin') . ' (' . $deletedResources . ')',
                'disabled'  => (int) $deletedResources === 0 ? true : false,
                'handler'   => 'this.emptyRecycleBin'
            );
        }

        $this->modx->invokeEvent('OnResourceToolbarLoad',array(
            'items' => &$items,
        ));

        return $this->modx->error->success('',$items);
    }
}

return 'modResourceGetToolbarProcessor';
