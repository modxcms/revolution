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
        $actions = $this->modx->request->getAllActionIDs();

        $items = array();
        $items[] = array(
            'icon' => $p.'arrow_down.png',
            'tooltip' => $this->modx->lexicon('expand_tree'),
            'handler' => 'this.expandAll',
        );
        $items[] = array(
            'icon' => $p.'arrow_up.png',
            'tooltip' => $this->modx->lexicon('collapse_tree'),
            'handler' => 'this.collapseAll',
        );
        $items[] = '-';
        if ($this->modx->hasPermission('new_document')) {
            $items[] = array(
                'icon' => $p.'folder_page_add.png',
                'tooltip' => $this->modx->lexicon('document_new'),
                'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'\");");',
            );
        }
        if ($this->modx->hasPermission('new_weblink')) {
            $items[] = array(
                'icon' => $p.'page_white_link.png',
                'tooltip' => $this->modx->lexicon('add_weblink'),
                'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'&class_key=modWebLink\");");',
            );
        }
        if ($this->modx->hasPermission('new_symlink')) {
            $items[] = array(
                'icon' => $p.'page_white_copy.png',
                'tooltip' => $this->modx->lexicon('add_symlink'),
                'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'&class_key=modSymLink\");");',
            );
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $items[] = array(
                'icon' => $p.'page_white_gear.png',
                'tooltip' => $this->modx->lexicon('static_resource_new'),
                'handler' => 'new Function("this.redirect(\"index.php?a='.$actions['resource/create'].'&class_key=modStaticResource\");");',
            );
        }
        $items[] = '-';

        $items[] = array(
            'icon' => $p.'refresh.png',
            'tooltip' => $this->modx->lexicon('refresh_tree'),
            'handler' => 'this.refresh',
        );
        $items[] = array(
            'icon' => $p.'unzip.gif',
            'tooltip' => $this->modx->lexicon('show_sort_options'),
            'handler' => 'this.showFilter',
        );
        if ($this->modx->hasPermission('purge_deleted')) {
            $items[] = '-';
            $items[] = array(
                'icon' => $p.'trash.png',
                'tooltip' => $this->modx->lexicon('empty_recycle_bin'),
                'handler' => 'this.emptyRecycleBin',
            );
        }

        $this->modx->invokeEvent('OnResourceToolbarLoad',array(
            'items' => &$items,
        ));

        return $this->modx->error->success('',$items);
    }
    
}
return 'modResourceGetToolbarProcessor';