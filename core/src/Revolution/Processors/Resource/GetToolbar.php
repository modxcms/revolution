<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modWebLink;
use MODX\Revolution\modSymLink;
use MODX\Revolution\modStaticResource;

/**
 * Gets a dynamic toolbar for the Resource tree.
 */
class GetToolbar extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resource_tree');
    }

    public function getLanguageTopics()
    {
        return ['resource', 'trash'];
    }

    public function process()
    {
        $items = [];
        //$context = '&context_key=' . $this->modx->getOption('default_context');
        $items[] = '-';

        if ($this->modx->hasPermission('new_document')) {
            $record = json_encode([
                'context_key'   => $this->modx->getOption('default_context'),
                'parent'        => 0
            ]);
            $items[] = [
                'cls'       => 'tree-new-resource',
                'tooltip'   => $this->modx->lexicon('document_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            ];
        }
        if ($this->modx->hasPermission('new_weblink')) {
            $record = json_encode([
                'class_key'     => modWebLink::class,
                'context_key'   => $this->modx->getOption('default_context'),
                'parent'        => 0
            ]);
            $items[] = [
                'cls'       => 'tree-new-weblink',
                'tooltip'   => $this->modx->lexicon('add_weblink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            ];
        }
        if ($this->modx->hasPermission('new_symlink')) {
            $record = json_encode([
                'class_key'     => modSymLink::class,
                'context_key'   => $this->modx->getOption('default_context'),
                'parent'        => 0
            ]);
            $items[] = [
                'cls'       => 'tree-new-symlink',
                'tooltip'   => $this->modx->lexicon('add_symlink'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            ];
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $record = json_encode([
                'class_key'     =>  modStaticResource::class,
                'context_key'   =>  $this->modx->getOption('default_context'),
                'parent'        =>  0
            ]);
            $items[] = [
                'cls'       => 'tree-new-static-resource',
                'tooltip'   => $this->modx->lexicon('static_resource_new'),
                'handler'   => 'new Function("MODx.createResource(' . $record . ');");'
            ];
        }
        unset($context);
        $items[] = '->';
        if ($this->modx->hasPermission('purge_deleted')) {
            $deletedResources = $this->modx->getCount(modResource::class, ['deleted' => 1]);

            $items[] = [
                'id' => 'emptifier',
                'cls' => 'tree-trash',
                'tooltip' => $this->modx->lexicon('trash.manage_recycle_bin_tooltip', [
                    'count' => $deletedResources
                ]),
                'disabled' => ($deletedResources == 0) ? true : false,
                'handler' => 'new Function("this.redirect(\"?a=resource/trash\");");'
            ];
        }

        $this->modx->invokeEvent('OnResourceToolbarLoad', [
            'items' => &$items,
        ]);

        return $this->modx->error->success('', $items);
    }
}
