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
        
        //$items[] = array(
        //    'icon'      => $p.'arrow_down.png',
        //    'tooltip'   => $this->modx->lexicon('expand_tree'),
        //    'handler'   => 'this.expandAll',
        //);
        
        //$items[] = array(
        //    'icon'      => $p.'arrow_up.png',
        //    'tooltip'   => $this->modx->lexicon('collapse_tree'),
        //    'handler'   => 'this.collapseAll',
        //);
        
        $items[] = '-';

        if ($this->modx->hasPermission('new_document')) {
            $record = '{web: \"' . $this->modx->getOption('default_context') . '\"}';
            
            $items[] = array(
                'cls'       => 'tree-new-resource',
                'tooltip'   => $this->modx->lexicon('document_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        if ($this->modx->hasPermission('new_weblink')) {
            $record = '{web: \"' . $this->modx->getOption('default_context') . '\", class_key : \"modWebLink\"}';
            
            $items[] = array(
                'cls'       => 'tree-new-weblink',
                'tooltip'   => $this->modx->lexicon('add_weblink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        if ($this->modx->hasPermission('new_symlink')) {
            $record = '{web: \"' . $this->modx->getOption('default_context') . '\", class_key : \"modSymLink\"}';
            
            $items[] = array(
                'cls'       => 'tree-new-symlink',
                'tooltip'   => $this->modx->lexicon('add_symlink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $record = '{web: \"' . $this->modx->getOption('default_context') . '\", class_key : \"modSymLink\"}';
            
            $items[] = array(
                'cls'       => 'tree-new-static-resource',
                'tooltip'   => $this->modx->lexicon('static_resource_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            );
        }
        
        //$items[] = '-';

        //$items[] = array(
        //    'icon'      => $p.'refresh.png',
        //    'tooltip'   => $this->modx->lexicon('refresh_tree'),
        //    'handler'   => 'this.refresh',
        //);
        
        //$items[] = array(
        //    'xtype'     => 'modx-tree-sort-by',
        //    'icon'      => $p.'unzip.gif',
        //    'tooltip'   => $this->modx->lexicon('show_sort_options'),
        //    'handler'   => 'this.showFilter',
        //);

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
