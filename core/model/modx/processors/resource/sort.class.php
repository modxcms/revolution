<?php
/**
 * Sorts the resource tree
 *
 * @param string $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
class modResourceSortProcessor extends modProcessor {
    public $nodes = array();
    public $nodesAffected = array();
    public $contexts = array();
    public $contextsAffected = array();
    
    public function checkPermissions() {
        return $this->modx->hasPermission('save_document');
    }
    public function getLanguageTopics() {
        return array('resource','context');
    }
    public function process() {
        $data = urldecode($this->getProperty('data',''));
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));
        $data = $this->modx->fromJSON($data);
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));

        $this->getNodesFormatted($data,0);

        $this->fireBeforeSort();

        /* sort contexts */
        foreach ($this->contexts as $key => $value) {
            $context = $this->modx->getObject('modContext',array(
                'key' => $value
            ));
            if ($context !== null) {
                $context->set('rank', $key);
                $context->save();
            }
        }
        
        /* readjust cache */
        $nodeErrors = array();
        $dontChangeParents = array();
        foreach ($this->nodes as $ar_node) {
            if (!is_array($ar_node) || empty($ar_node['id'])) continue;
            /** @var modResource $node */
            $node = $this->modx->getObject('modResource',$ar_node['id']);
            if (empty($node)) continue;

            if (empty($ar_node['context'])) continue;
            if (in_array($ar_node['parent'],$dontChangeParents)) continue;

            $old_parent_id = $node->get('parent');

            if ($old_parent_id != $ar_node['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if ($ar_node['parent'] != 0) {
                    /** @var modResource $parent */
                    $parent = $this->modx->getObject('modResource',$ar_node['parent']);
                    if ($parent == null) {
                        $nodeErrors[] = $this->modx->lexicon('resource_err_new_parent_nf', array('id' => $ar_node['parent']));
                        continue;
                    }
                    if (!$parent->checkPolicy('add_children')) {
                        $nodeErrors[] = $this->modx->lexicon('resource_add_children_access_denied');
                        continue;
                    }
                } else {
                    $context = $this->modx->getObject('modContext',$ar_node['context']);
                    if (empty($context)) {
                        $nodeErrors[] = $this->modx->lexicon('context_err_nfs', array('key' => $ar_node['context']));
                        continue;
                    }
                    if (!$this->modx->hasPermission('new_document_in_root')) {
                        $nodeErrors[] = $this->modx->lexicon('resource_add_children_access_denied');
                        continue;
                    }
                }

                /* save new parent */
                $node->set('parent',$ar_node['parent']);
            }
            $old_context_key = $node->get('context_key');
            $this->contextsAffected[$old_context_key] = true;
            if ($old_context_key != $ar_node['context'] && !empty($ar_node['context'])) {
                $node->set('context_key',$ar_node['context']);
                $this->contextsAffected[$ar_node['context']] = true;
                $dontChangeParents[] = $node->get('id'); /* prevent children from reverting back */
            }
            $node->set('menuindex',$ar_node['order']);
            $this->nodesAffected[] = $node;
        }
        if (!empty($this->nodesAffected)) {
            /** @var modResource $modifiedNode */
            foreach ($this->nodesAffected as $modifiedNode) {
                if (!$modifiedNode->checkPolicy('save')) {
                    $nodeErrors[] = $this->modx->lexicon('resource_err_save');
                }
            }
        }
        if (!empty($nodeErrors)) {
            return $this->modx->error->failure(implode("\n", array_unique($nodeErrors)));
        }
        if (!empty($this->nodesAffected)) {
            foreach ($this->nodesAffected as $modifiedNode) {
                $modifiedNode->save();
            }
        }

        $this->fireAfterSort();

        /* empty cache */
        $this->clearCache();

        return $this->success();
    }

    protected function getNodesFormatted($currentLevel,$parent = 0) {
        $order = 0;
        $previousContext = null;
        foreach ($currentLevel as $id => $children) {
            $explodedArray = explode('_',$id);
            if ($explodedArray[1] != '0') {
                $explodedParentArray = explode('_',$parent);
                $this->nodes[] = array(
                    'id' => $explodedArray[1],
                    'context' => $explodedParentArray[0],
                    'parent' => $explodedParentArray[1],
                    'order' => $order,
                );
                $order++;
            } else {
                if ($previousContext !== $explodedArray[0]) {
                    $this->contexts[] = $explodedArray[0];
                }
                $previousContext = $explodedArray[0];
            }
            $this->getNodesFormatted($children,$id);
        }
    }

    public function fireBeforeSort() {
        $this->modx->invokeEvent('OnResourceBeforeSort',array(
            'nodes' => &$this->nodes,
            'contexts' => &$this->contexts,
        ));
    }

    public function fireAfterSort() {
        $this->modx->invokeEvent('OnResourceSort', array(
            'nodes' => &$this->nodes,
            'nodesAffected' => &$this->nodesAffected,
            'contexts' => &$this->contexts,
            'contexts' => &$this->contextsAffected,
            'modifiedNodes' => &$this->nodesAffected, /* backward compat */
        ));

    }

    public function clearCache() {
        $this->modx->cacheManager->refresh(array(
            'db' => array(),
            'auto_publish' => array('contexts' => $this->contextsAffected),
            'context_settings' => array('contexts' => $this->contextsAffected),
            'resource' => array('contexts' => $this->contextsAffected),
        ));
    }

}
return 'modResourceSortProcessor';