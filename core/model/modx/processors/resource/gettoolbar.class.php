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
        $items[] = array(
            'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-level-down" style=" font-size: 1.4em; text-align: center; padding-left: 3px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
            'tooltip' => $this->modx->lexicon('expand_tree'),
            'handler' => 'this.expandAll',
        );
        $items[] = array(
            'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-level-up" style=" font-size: 1.4em; text-align: center; padding-left: 4px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
            'tooltip' => $this->modx->lexicon('collapse_tree'),
            'handler' => 'this.collapseAll',
        );
        $items[] = '-';
        $context = '&context_key=' . $this->modx->getOption('default_context');
        if ($this->modx->hasPermission('new_document')) {
            $items[] = array(
                'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-file" style=" font-size: 1.4em; text-align: center; padding-left: 3px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
                'tooltip' => $this->modx->lexicon('document_new'),
                'handler' => 'new Function("this.redirect(\"index.php?a=resource/create'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_weblink')) {
            $items[] = array(
                'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-link" style=" font-size: 1.4em; text-align: center; padding-left: 2px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
                'tooltip' => $this->modx->lexicon('add_weblink'),
                'handler' => 'new Function("this.redirect(\"index.php?a=resource/create&class_key=modWebLink'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_symlink')) {
            $items[] = array(
                'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-copy" style=" font-size: 1.4em; text-align: center; padding-left: 1px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
                'tooltip' => $this->modx->lexicon('add_symlink'),
                'handler' => 'new Function("this.redirect(\"index.php?a=resource/create&class_key=modSymLink'. $context .'\");");',
            );
        }
        if ($this->modx->hasPermission('new_static_resource')) {
            $items[] = array(
                'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-file-alt" style=" font-size: 1.4em; text-align: center; padding-left: 3px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
                'tooltip' => $this->modx->lexicon('static_resource_new'),
                'handler' => 'new Function("this.redirect(\"index.php?a=resource/create&class_key=modStaticResource'. $context .'\");");',
            );
        }
        unset($context);
        $items[] = '-';

        $items[] = array(
            'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-refresh" style=" font-size: 1.4em; text-align: center; padding-left: 2px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
            'tooltip' => $this->modx->lexicon('refresh_tree'),
            'handler' => 'this.refresh',
        );
        $items[] = array(
            'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-sort" style=" font-size: 1.4em; text-align: center; padding-left: 2px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
            'tooltip' => $this->modx->lexicon('show_sort_options'),
            'handler' => 'this.showFilter',
        );
        if ($this->modx->hasPermission('purge_deleted')) {
            $items[] = '-';
            $items[] = array(
                'html' => '<tbody class="x-btn-small x-btn-icon-small-left"><tr><td class="x-btn-tl"><i>&nbsp;</i></td><td class="x-btn-tc"></td><td class="x-btn-tr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-ml"><i>&nbsp;</i></td><td class="x-btn-mc"><button type="button" id="ext-gen115" class=" x-btn-text" style=" position: relative; padding: 0 !important; text-align: center;"><i class="icon-trash" style=" font-size: 1.4em; text-align: center; padding-left: 3px;">&nbsp;</i></button></td><td class="x-btn-mr"><i>&nbsp;</i></td></tr><tr><td class="x-btn-bl"><i>&nbsp;</i></td><td class="x-btn-bc"></td><td class="x-btn-br"><i>&nbsp;</i></td></tr></tbody>',
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