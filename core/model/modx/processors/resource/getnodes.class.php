<?php
/**
 * Get nodes for the resource tree
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */

class modResourceGetNodesProcessor extends modProcessor {
    /** @var int $defaultRootId */
    public $defaultRootId;
    public $itemClass;
    public $contextKey = false;
    public $startNode = 0;
    public $items = array();
    public $permissions = array();

    public function checkPermissions() {
        return $this->modx->hasPermission('resource_tree');
    }
    public function getLanguageTopics() {
        return array('resource','context');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'sortBy' => $this->modx->getOption('tree_default_sort',null,'menuindex'),
            'sortDir' => 'ASC',
            'stringLiterals' => false,
            'noMenu' => false,
            'debug' => false,
            'nodeField' => $this->modx->getOption('resource_tree_node_name',null,'pagetitle'),
            'nodeFieldFallback' => $this->modx->getOption('resource_tree_node_name_fallback',null,'pagetitle'),
            'qtipField' => $this->modx->getOption('resource_tree_node_tooltip',null,''),
            'currentResource' => false,
            'currentAction' => false,
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function process() {
        $this->getRootNode();
        $this->prepare();

        if (empty($this->contextKey) || $this->contextKey == 'root') {
            $c = $this->getContextQuery();
        } else {
            $c = $this->getResourceQuery();
        }

        $collection = $this->modx->getCollection($this->itemClass, $c);
        $search = $this->getProperty('search','');
        if (!empty($search) && empty($node) && (empty($this->contextKey) || $this->contextKey == 'root')) {
            $this->search($search);
        }

        $this->iterate($collection);

        if ($this->getProperty('stringLiterals',false)) {
            return $this->modx->toJSON($this->items);
        } else {
            return $this->toJSON($this->items);
        }
    }

    /**
     * Prepare the tree nodes, by getting the permissions
     * @return void
     */
    public function prepare() {
        $this->permissions = array(
            'save_document' => $this->modx->hasPermission('save_document') ? 'psave' : '',
            'view_document' => $this->modx->hasPermission('view_document') ? 'pview' : '',
            'edit_document' => $this->modx->hasPermission('edit_document') ? 'pedit' : '',
            'delete_document' => $this->modx->hasPermission('delete_document') ? 'pdelete' : '',
            'undelete_document' => $this->modx->hasPermission('undelete_document') ? 'pundelete' : '',
            'publish_document' => $this->modx->hasPermission('publish_document') ? 'ppublish' : '',
            'unpublish_document' => $this->modx->hasPermission('unpublish_document') ? 'punpublish' : '',
            'resource_duplicate' => $this->modx->hasPermission('resource_duplicate') ? 'pduplicate' : '',
            'resource_quick_create' => $this->modx->hasPermission('resource_quick_create') ? 'pqcreate' : '',
            'resource_quick_update' => $this->modx->hasPermission('resource_quick_update') ? 'pqupdate' : '',
            'edit_context' => $this->modx->hasPermission('edit_context') ? 'pedit' : '',
            'new_context' => $this->modx->hasPermission('new_context') ? 'pnew' : '',
            'delete_context' => $this->modx->hasPermission('delete_context') ? 'pdelete' : '',

            'new_context_document' => $this->modx->hasPermission('new_document') ? 'pnewdoc pnew_modDocument' : '',
            'new_context_symlink' => $this->modx->hasPermission('new_symlink') ? 'pnewdoc pnew_modSymLink' : '',
            'new_context_weblink' => $this->modx->hasPermission('new_weblink') ? 'pnewdoc pnew_modWebLink' : '',
            'new_context_static_resource' => $this->modx->hasPermission('new_static_resource') ? 'pnewdoc pnew_modStaticResource' : '',

            'new_static_resource' => $this->modx->hasPermission('new_static_resource') ? 'pnew pnew_modStaticResource' : '',
            'new_symlink' => $this->modx->hasPermission('new_symlink') ? 'pnew pnew_modSymLink' : '',
            'new_weblink' => $this->modx->hasPermission('new_weblink') ? 'pnew pnew_modWebLink' : '',
            'new_document' => $this->modx->hasPermission('new_document') ? 'pnew pnew_modDocument' : '',
        );

    }

    /**
     * Determine the context and root and start nodes for the tree
     *
     * @return void
     */
    public function getRootNode() {
        $this->defaultRootId = $this->modx->getOption('tree_root_id',null,0);

        $id = $this->getProperty('id');
        if (empty($id) || $id == 'root') {
            $this->startNode = $this->defaultRootId;
        } else {
            $parts= explode('_',$id);
            $this->contextKey= isset($parts[0]) ? $parts[0] : false;
            $this->startNode = !empty($parts[1]) ? intval($parts[1]) : 0;
        }
        if ($this->getProperty('debug')) {
            echo '<p style="width: 800px; font-family: \'Lucida Console\'; font-size: 11px">';
        }
    }

    /**
     * Get the query object for grabbing Contexts in the tree
     * @return xPDOQuery
     */
    public function getContextQuery() {
        $this->itemClass= 'modContext';
        $c= $this->modx->newQuery($this->itemClass, array('key:!=' => 'mgr'));
        if (!empty($this->defaultRootId)) {
            $c->where(array(
                "(SELECT COUNT(*) FROM {$this->modx->getTableName('modResource')} WHERE context_key = modContext.{$this->modx->escape('key')} AND id IN ({$this->defaultRootId})) > 0",
            ));
        }
        if ($this->modx->getOption('context_tree_sort',null,false)) {
            $ctxSortBy = $this->modx->getOption('context_tree_sortby',null,'key');
            $ctxSortDir = $this->modx->getOption('context_tree_sortdir',null,'ASC');
            $c->sortby($this->modx->getSelectColumns('modContext','modContext','',array($ctxSortBy)),$ctxSortDir);
        }
        return $c;
    }

    /**
     * Get the query object for grabbing Resources in the tree
     * @return xPDOQuery
     */
    public function getResourceQuery() {
        $resourceColumns = array(
            'id'
            ,'template'
            ,'pagetitle'
            ,'longtitle'
            ,'alias'
            ,'description'
            ,'parent'
            ,'published'
            ,'deleted'
            ,'isfolder'
            ,'menuindex'
            ,'menutitle'
            ,'hidemenu'
            ,'class_key'
            ,'context_key'
        );
        $this->itemClass= 'modResource';
        $c= $this->modx->newQuery($this->itemClass);
        $c->leftJoin('modResource', 'Child', array('modResource.id = Child.parent'));
        $c->select($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns));
        $c->select(array(
            'childrenCount' => 'COUNT(Child.id)',
        ));
        $c->where(array(
            'context_key' => $this->contextKey,
            'show_in_tree' => true,
        ));
        if (empty($this->startNode) && !empty($this->defaultRootId)) {
            $c->where(array(
                'id:IN' => explode(',', $this->defaultRootId),
                'parent:NOT IN' => explode(',', $this->defaultRootId),
            ));
        } else {
            $c->where(array(
                'parent' => $this->startNode,
            ));
        }
        $c->groupby($this->modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns), '');
        $sortBy = $this->modx->escape($this->getProperty('sortBy'));
        $c->sortby('modResource.' . $sortBy,$this->getProperty('sortDir'));
        return $c;
    }

    /**
     * Add search results to tree nodes
     *
     * @param string $query
     * @return void
     */
    public function search($query) {
        /* first check to see if search results */
        $searchNode = array(
            'text' => $this->modx->lexicon('search_results'),
            'id' => 'search_results',
            'leaf' => false,
            'cls' => 'search-results-node',
            'type' => 'none',
            'expanded' => true,
            'children' => array(),
        );

        $s = $query;

        $c = $this->modx->newQuery('modResource');
        $c->select($this->modx->getSelectColumns('modResource','modResource','',array(
            'id'
            ,'template'
            ,'pagetitle'
            ,'longtitle'
            ,'alias'
            ,'description'
            ,'parent'
            ,'published'
            ,'deleted'
            ,'isfolder'
            ,'menuindex'
            ,'menutitle'
            ,'hidemenu'
            ,'class_key'
            ,'context_key'
        )));
        $c->where(array(
            'pagetitle:LIKE' => '%'.$s.'%',
            'OR:longtitle:LIKE' => '%'.$s.'%',
            'OR:alias:LIKE' => '%'.$s.'%',
            'OR:menutitle:LIKE' => '%'.$s.'%',
            'OR:description:LIKE' => '%'.$s.'%',
            'OR:content:LIKE' => '%'.$s.'%',
        ));
        $c->where(array(
            'show_in_tree' => true,
        ));
        $c->limit($this->modx->getOption('resource_tree_num_search_results',null,15));
        $searchResults = $this->modx->getCollection('modResource',$c);

        /** @var modResource $item */
        foreach ($searchResults as $item) {
            $itemArray = $this->prepareResourceNode($item);
            if (!empty($itemArray)) {
                $itemArray['leaf'] = true;
                $searchNode['children'][] = $itemArray;
            }
        }

        $searchNode['children'][] = array(
            'text' => $this->modx->lexicon('more_search_results'),
            'id' => 'more-search-results-node',
            'leaf' => true,
            'expanded' => false,
            'children' => array(),
            'allowDrop' => false,
            'cls' => 'search-results-node search-more-node',
            'page' => '?a=search&q='.urlencode($s),
        );

        $this->items[] = $searchNode;
    }

    /**
     * Iterate across the collection of items from the query
     *
     * @param array $collection
     * @return void
     */
    public function iterate(array $collection = array()) {
        /* now process actual tree nodes */
        $item = reset($collection);
        /** @var modContext|modResource $item */
        while ($item) {
            $canList = $item->checkPolicy('list');
            if (!$canList) {
                $item = next($collection);
                continue;
            }

            if ($this->itemClass == 'modContext') {
                $itemArray = $this->prepareContextNode($item);
                if (!empty($itemArray)) {
                    $this->items[] = $itemArray;
                }
            } else {
                $itemArray = $this->prepareResourceNode($item);
                if (!empty($itemArray)) {
                    $this->items[] = $itemArray;
                }
            }
            $item = next($collection);
        }
    }

    /**
     * Prepare a Context for being shown in the tree
     *
     * @param modContext $context
     * @return array
     */
    public function prepareContextNode(modContext $context) {
        $class = array('tree-pseudoroot-node');

        $createRoot =  $this->modx->hasPermission('new_document_in_root');

        $class[] = !empty($this->permissions['edit_context']) ? $this->permissions['edit_context'] : '';
        $class[] = !empty($this->permissions['new_context']) ? $this->permissions['new_context'] : '';
        $class[] = !empty($this->permissions['delete_context']) ? $this->permissions['delete_context'] : '';
        $class[] = !empty($this->permissions['new_context_document']) && $createRoot
            ? $this->permissions['new_context_document']
            : '';
        $class[] = !empty($this->permissions['new_context_symlink']) && $createRoot
            ? $this->permissions['new_context_symlink']
            : '';
        $class[] = !empty($this->permissions['new_context_weblink']) && $createRoot
            ? $this->permissions['new_context_weblink']
            : '';
        $class[] = !empty($this->permissions['new_context_static_resource']) && $createRoot
            ? $this->permissions['new_context_static_resource']
            : '';
        $class[] = !empty($this->permissions['resource_quick_create']) && $createRoot
            ? $this->permissions['resource_quick_create']
            : '';

        $context->prepare();
        return array(
            'text' => $context->get('name') != '' ? $context->get('name') : $context->get('key'),
            'id' => $context->get('key') . '_0',
            'pk' => $context->get('key'),
            'ctx' => $context->get('key'),
            'settings' => array(
                'default_template' => $context->getOption('default_template'),
                'richtext_default' => $context->getOption('richtext_default'),
                'hidemenu_default' => $context->getOption('hidemenu_default'),
                'search_default' => $context->getOption('search_default'),
                'cache_default' => $context->getOption('cache_default'),
                'publish_default' => $context->getOption('publish_default'),
                'default_content_type' => $context->getOption('default_content_type'),
            ),
            'leaf' => false,
            'cls' => implode(' ', $class),
            'iconCls' => $context->getOption('mgr_tree_icon_context', 'tree-context'),
            'qtip' => $context->get('description') != '' ? strip_tags($context->get('description')) : '',
            'type' => 'modContext',
            'pseudoroot' => true,
    //        'page' => !$this->getProperty('noHref') ? '?a=context/update&key='.$context->get('key') : '',
        );
    }

    /**
     * Prepare a Resource for being shown in the tree
     *
     * @param modResource $resource
     * @return array
     */
    public function prepareResourceNode(modResource $resource) {
        $qtipField = $this->getProperty('qtipField');
        $nodeField = $this->getProperty('nodeField');
        $nodeFieldFallback = $this->getProperty('nodeFieldFallback');
        $noHref = $this->getProperty('noHref',false);

        $hasChildren = $resource->get('childrenCount') > 0;

        $class = array();
        if (!$resource->isfolder) {
            $class[] = 'x-tree-node-leaf';
        }
        if (!$resource->get('published')) $class[] = 'unpublished';
        if ($resource->get('deleted')) $class[] = 'deleted';
        if ($resource->get('hidemenu')) $class[] = 'hidemenu';

        if (!empty($this->permissions['save_document'])) $class[] = $this->permissions['save_document'];
        if (!empty($this->permissions['view_document'])) $class[] = $this->permissions['view_document'];
        if (!empty($this->permissions['edit_document'])) $class[] = $this->permissions['edit_document'];
        if (!empty($this->permissions['resource_duplicate'])) {
            if ($resource->parent != $this->defaultRootId || $this->modx->hasPermission('new_document_in_root')) {
                $class[] = $this->permissions['resource_duplicate'];
            }
        }
        if ($resource->allowChildrenResources) {
            if (!empty($this->permissions['new_document'])) $class[] = $this->permissions['new_document'];
            if (!empty($this->permissions['new_symlink'])) $class[] = $this->permissions['new_symlink'];
            if (!empty($this->permissions['new_weblink'])) $class[] = $this->permissions['new_weblink'];
            if (!empty($this->permissions['new_static_resource'])) $class[] = $this->permissions['new_static_resource'];
            if (!empty($this->permissions['resource_quick_create'])) $class[] = $this->permissions['resource_quick_create'];
        }
        if (!empty($this->permissions['resource_quick_update'])) $class[] = $this->permissions['resource_quick_update'];
        if (!empty($this->permissions['delete_document'])) $class[] = $this->permissions['delete_document'];
        if (!empty($this->permissions['undelete_document'])) $class[] = $this->permissions['undelete_document'];
        if (!empty($this->permissions['publish_document'])) $class[] = $this->permissions['publish_document'];
        if (!empty($this->permissions['unpublish_document'])) $class[] = $this->permissions['unpublish_document'];

        $active = false;
        if ($this->getProperty('currentResource') == $resource->id && $this->getProperty('currentAction') == 'resource/update') {
            $active = true;
        }

        $qtip = '';
        if (!empty($qtipField) && !empty($resource->$qtipField)) {
            $qtip = '<b>'.strip_tags($resource->$qtipField).'</b>';
        } else {
            if ($resource->longtitle != '') {
                $qtip = '<b>'.strip_tags($resource->longtitle).'</b><br />';
            }
            if ($resource->description != '') {
                $qtip = '<i>'.strip_tags($resource->description).'</i>';
            }
        }

        // Check for an icon class on the resource template
        $tplIcon = $resource->Template ? $resource->Template->icon : '';

        // Assign an icon class based on the class_key
        $classKey = strtolower($resource->get('class_key'));
        if (substr($classKey, 0, 3) == 'mod') {
            $classKey = substr($classKey, 3);
        }

        $classKeyIcon = $this->modx->getOption('mgr_tree_icon_' . $classKey, null, 'tree-resource', true);

        if (!empty($tplIcon)) {
            $iconCls[] = $tplIcon;
        } else {
            $iconCls[] = $classKeyIcon;
        }

        switch($classKey) {
            case 'weblink':
                $iconCls[] = $this->modx->getOption('mgr_tree_icon_weblink', null, 'tree-weblink');
                break;

            case 'symlink':
                $iconCls[] = $this->modx->getOption('mgr_tree_icon_symlink', null, 'tree-symlink');
                break;

            case 'staticresource':
                $iconCls[] = $this->modx->getOption('mgr_tree_icon_staticresource', null, 'tree-static-resource');
                break;
        }

        // Icons specific with the context and resource ID for super specific tweaks
        $iconCls[] = 'icon-' . $resource->get('context_key') . '-' . $resource->get('id');
        $iconCls[] = 'icon-parent-' . $resource->get('context_key') . '-'  . $resource->get('parent');

        // Modifiers to indicate resource _state_
        if ($hasChildren || $resource->isfolder) {
            if (empty($tplIcon) && $classKeyIcon == 'tree-resource') {
                $iconCls[] = $this->modx->getOption('mgr_tree_icon_folder', null, 'tree-folder');
            }

            $iconCls[] = 'parent-resource';
        }

        // Add icon class - and additional description to the tooltip - if the resource is locked.
        $locked = $resource->getLock();
        if ($locked && $locked != $this->modx->user->get('id')) {
            $iconCls[] = 'locked-resource';
            /** @var modUser $lockedBy */
            $lockedBy = $this->modx->getObject('modUser',$locked);

            if ($lockedBy) {
                $qtip .= ' - ' . $this->modx->lexicon('locked_by',array('username' => $lockedBy->get('username')));
            }
        }

        // Add the ID to the item text if the user has the permission
        $idNote = $this->modx->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">('.$resource->id.')</span>' : '';

        // Used in the preview_url, if sessions are disabled on the resource context we add an extra url param
        $sessionEnabled = '';
        if ($ctxSetting = $this->modx->getObject('modContextSetting', array('context_key' => $resource->get('context_key'), 'key' => 'session_enabled'))) {
            $sessionEnabled = $ctxSetting->get('value') == 0 ? array('preview' => 'true') : '';
        }

        $text = strip_tags($resource->get($nodeField));
        if (empty($text)) {
            $text = $resource->get($nodeFieldFallback);
            $text = strip_tags($text);
        }
        $itemArray = array(
            'text' => $text.$idNote,
            'id' => $resource->context_key . '_'.$resource->id,
            'pk' => $resource->id,
            'cls' => implode(' ',$class),
            'iconCls' => implode(' ',$iconCls),
            'type' => 'modResource',
            'selected' => $active,
            'classKey' => $resource->class_key,
            'ctx' => $resource->context_key,
            'hide_children_in_tree' => $resource->hide_children_in_tree,
            'qtip' => $qtip,
            'preview_url' => (!$resource->get('deleted')) ? $this->modx->makeUrl($resource->get('id'), $resource->get('context_key'), $sessionEnabled, 'full', array('xhtml_urls' => false)) : '',
            'page' => empty($noHref) ? '?a='.(!empty($this->permissions['edit_document']) ? 'resource/update' : 'resource/data').'&id='.$resource->id : '',
            'allowDrop' => true,
        );
        if (!$hasChildren) {
            $itemArray['hasChildren'] = false;
            $itemArray['children'] = array();
            $itemArray['expanded'] = true;
        } else {
            $itemArray['hasChildren'] = true;
            $itemArray['childCount']  = $resource->get('childrenCount');
        }
        $itemArray = $resource->prepareTreeNode($itemArray);
        return $itemArray;
    }
}
return 'modResourceGetNodesProcessor';
