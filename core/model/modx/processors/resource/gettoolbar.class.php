<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
        return array('resource', 'trash');
    }

    public function process()
    {
        $items = array();
        $context = '&context_key=' . $this->modx->getOption('default_context');
        if ($this->modx->hasPermission('new_document')) {
            $items[] = array(
                'cls' => 'tree-new-resource',
                'tooltip' => $this->modx->lexicon('document_new'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_weblink')) {
            $items[] = array(
                'cls' => 'tree-new-weblink',
                'tooltip' => $this->modx->lexicon('add_weblink'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modWebLink'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_symlink')) {
            $items[] = array(
                'cls' => 'tree-new-symlink',
                'tooltip' => $this->modx->lexicon('add_symlink'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modSymLink'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $items[] = array(
                'cls' => 'tree-new-static-resource',
                'tooltip' => $this->modx->lexicon('static_resource_new'),
                'handler' => 'new Function("this.redirect(\"?a=resource/create&class_key=modStaticResource'. $context .'\");");',
            );
        }
        unset($context);
        $items[] = '->';
        if ($this->modx->hasPermission('purge_deleted')) {
            $deletedResources = $this->modx->getCount('modResource', array('deleted' => 1));

            $items[] = array(
                'id' => 'emptifier',
                'cls' => 'tree-trash',
                'tooltip' => $this->modx->lexicon('trash.manage_recycle_bin_tooltip', array(
                    'count' => $deletedResources
                )),
                'disabled' => ($deletedResources == 0) ? true : false,
                'handler' => 'new Function("this.redirect(\"?a=resource/trash\");");'
            );
        }

        $this->modx->invokeEvent('OnResourceToolbarLoad',array(
            'items' => &$items,
        ));

        return $this->modx->error->success('',$items);
    }

}
return 'modResourceGetToolbarProcessor';
