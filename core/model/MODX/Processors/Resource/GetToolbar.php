<?php

namespace MODX\Processors\Resource;

use MODX\Processors\modProcessor;

/**
 * Gets a dynamic toolbar for the Resource tree.
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
class GetToolbar extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resource_tree');
    }


    public function getLanguageTopics()
    {
        return ['resource'];
    }


    public function process()
    {
        $items = [];
        $context = '&context_key=' . $this->modx->getOption('default_context');
        if ($this->modx->hasPermission('new_document')) {
            $items[] = [
                'cls' => 'tree-new-resource',
                'tooltip' => $this->modx->lexicon('document_new'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create' . $context . '\");");',
            ];
        }
        if ($this->modx->hasPermission('new_weblink')) {
            $items[] = [
                'cls' => 'tree-new-weblink',
                'tooltip' => $this->modx->lexicon('add_weblink'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modWebLink' . $context . '\");");',
            ];
        }
        if ($this->modx->hasPermission('new_symlink')) {
            $items[] = [
                'cls' => 'tree-new-symlink',
                'tooltip' => $this->modx->lexicon('add_symlink'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modSymLink' . $context . '\");");',
            ];
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $items[] = [
                'cls' => 'tree-new-static-resource',
                'tooltip' => $this->modx->lexicon('static_resource_new'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modStaticResource' . $context . '\");");',
            ];
        }
        unset($context);
        $items[] = '-';
//
//        $items[] = array(
//            'icon' => $p.'refresh.png',
//            'tooltip' => $this->modx->lexicon('refresh_tree'),
//            'handler' => 'this.refresh',
//        );
//        $items[] = array(
//            'xtype' => 'modx-tree-sort-by'
//            'icon' => $p.'unzip.gif',
//            'tooltip' => $this->modx->lexicon('show_sort_options'),
//            'handler' => 'this.showFilter',
//        );

        $items[] = '->';
        if ($this->modx->hasPermission('purge_deleted')) {
            $deletedResources = $this->modx->getCount('modResource', ['deleted' => 1]);

            $items[] = [
                'id' => 'emptifier',
                'cls' => 'tree-trash',
                'tooltip' => $this->modx->lexicon('empty_recycle_bin') . ' (' . $deletedResources . ')',
                'disabled' => ($deletedResources == 0) ? true : false,
                'handler' => 'this.emptyRecycleBin',
            ];
        }

        $this->modx->invokeEvent('OnResourceToolbarLoad', [
            'items' => &$items,
        ]);

        return $this->modx->error->success('', $items);
    }

}