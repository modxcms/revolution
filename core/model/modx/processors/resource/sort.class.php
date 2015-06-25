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

    public $source;
    public $target;
    public $point;
    
    public $autoIsFolder = true;


    public function checkPermissions() {
        return $this->modx->hasPermission('save_document');
    }
    public function getLanguageTopics() {
        return array('resource','context');
    }
    public function process() {
        $this->autoIsFolder = $this->modx->getOption('auto_isfolder', null, true);

        // Because of BC
        $data = urldecode($this->getProperty('data',''));
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));
        $data = $this->modx->fromJSON($data);
        if (empty($data)) $this->failure($this->modx->lexicon('invalid_data'));

        // Because of BC
        $this->getNodesFormatted($data, $this->getProperty('parent', 0));

        $this->fireBeforeSort();

        $target = $this->getProperty('target', '');
        $source = $this->getProperty('source', '');
        $point = $this->getProperty('point', '');

        if (empty ($target)) {
            return $this->failure('Target not set');
        }

        if (empty($source)) {
            return $this->failure('Source not set');
        }

        if (empty($point)) {
            return $this->failure('Point not set');
        }

        $this->point = $point;
        $this->parseNodes($source, $target);

        $sorted = $this->sort();
        if ($sorted !== true) return $this->failure($sorted);

        $this->fireAfterSort();

        $this->clearCache();

        $action = 'resource_sort';
        if ($this->getProperty('source_type') == 'modContext') {
            $action = 'context_sort';
        }
        $this->modx->logManagerAction(
            $action,
            $this->getProperty('source_type'),
            $this->getProperty('source_pk')
        );

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
            'contextsAffected' => &$this->contextsAffected,
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

    public function fixParents($node) {
        $nodeObject = $this->modx->getObject('modResource', $node->id);

        /** @var modResource $oldParent */
        $oldParent = $this->modx->getObject('modResource', $nodeObject->parent);

        /** @var modResource $newParent */
        $newParent = $this->modx->getObject('modResource', $node->parent);

        if (empty($oldParent) && empty($newParent)) return;
        if ($oldParent->id == $newParent->id) return;

        if (!empty($oldParent)) {
            $oldParentChildrenCount = $this->modx->getCount('modResource', array('parent' => $oldParent->get('id'), 'id:!=' => $node->id));
            if ($oldParentChildrenCount <= 0 || $oldParentChildrenCount == null) {
                $oldParent->set('isfolder', false);
                $oldParent->save();
            }
        }

        if (!empty($newParent)) {
            $newParent->set('isfolder', true);
            $newParent->save();
        }
    }

    public function parseNodes($source, $target) {
        $source = explode('_', $source);
        $target = explode('_', $target);

        if (intval($source[1]) == 0) {
            $this->source = $this->modx->getObject('modContext', $source[0]);
        } else {
            $this->source = $this->modx->getObject('modResource', $source[1]);
        }

        if (intval($target[1]) == 0) {
            $this->target = $this->modx->getObject('modContext', $target[0]);
        } else {
            $this->target = $this->modx->getObject('modResource', $target[1]);
        }
    }

    public function sortContexts() {
        $lastRank = $this->target->rank;

        if ($this->point == 'above') {
            $this->moveContext('source', $lastRank);
            $this->moveContext('target', $lastRank + 1);
        }

        if ($this->point == 'below') {
            $this->moveContext('source', $lastRank + 1);
        }

        $this->moveAffectedContexts($lastRank);

        return true;
    }

    public function moveToContext() {
        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            'context_key' => $this->target->key,
            'parent' => 0,
        ));
        $c->sortby('menuindex', 'DESC');
        $c->limit(1);

        /** @var modResource $lastResource */
        $lastResource = $this->modx->getObject('modResource', $c);

        if ($lastResource) {
            $this->source->set('menuindex', $lastResource->menuindex + 1);
        } else {
            $this->source->set('menuindex', 0);
        }

        $this->contextsAffected[$this->source->key] = true;
        $this->contextsAffected[$this->target->key] = true;

        $this->source->set('context_key', $this->target->key);
        $this->source->set('parent', 0);

        if (!$this->source->checkPolicy('save')) {
            return $this->modx->lexicon('resource_err_save');
        }

        $this->source->save();

        $this->nodesAffected[] = $this->source;

        return true;
    }

    public function sortResources() {
        $lastRank = $this->target->menuindex;

        if ($this->point == 'above') {
            return $this->moveResourceAbove($lastRank);
        }

        if ($this->point == 'below') {
            return $this->moveResourceBelow($lastRank);
        }

        if ($this->point == 'append') {
            return $this->appendResource();
        }

        return false;
    }

    public function moveAffectedResources($lastRank){
        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            'id:NOT IN' => array($this->source->id, $this->target->id),
            'menuindex:>=' => $lastRank,
            'parent' => $this->target->parent,
            'context_key' => $this->target->context_key,
        ));
        $c->sortby('menuindex', 'ASC');

        $resourcesToSort = $this->modx->getIterator('modResource', $c);
        $lastRank = $lastRank + 2;

        /** @var modResource $resource */
        foreach ($resourcesToSort as $resource) {
            $resource->set('menuindex', $lastRank);

            if (!$resource->checkPolicy('save')) {
                return $this->modx->lexicon('resource_err_save');
            }

            $resource->save();
            $this->nodesAffected[] = $resource;
            $this->contextsAffected[$resource->context_key] = true;
            $lastRank++;
        }

        return true;
    }

    public function moveResource($type, $rank){
        $this->$type->set('menuindex', $rank);
        $this->$type->set('parent', $this->target->parent);

        $this->contextsAffected[$this->$type->context_key] = true;
        $this->$type->set('context_key', $this->target->context_key);

        if (!$this->source->checkPolicy('save')) {
            return $this->modx->lexicon('resource_err_save');
        }

        $this->$type->save();
        $this->nodesAffected[] = $this->$type;
        $this->contextsAffected[$this->$type->context_key] = true;

        return true;
    }

    public function moveResourceAbove($lastRank){
        $moved = $this->moveResource('source', $lastRank);
        if ($moved !== true) return $moved;

        $moved = $this->moveResource('target', $lastRank + 1);
        if ($moved !== true) return $moved;

        return $this->moveAffectedResources($lastRank);
    }

    public function moveResourceBelow($lastRank) {
        $this->moveResource('source', $lastRank + 1);

        return $this->moveAffectedResources($lastRank);
    }

    public function appendResource() {
        $c = $this->modx->newQuery('modResource');
        $c->where(array(
            'parent' => $this->target->id,
            'context_key' => $this->target->context_key,
        ));
        $c->sortby('menuindex', 'DESC');
        $c->limit(1);

        /** @var modResource $lastResource */
        $lastResource = $this->modx->getObject('modResource', $c);

        if ($lastResource) {
            $this->source->set('menuindex', $lastResource->menuindex + 1);
        } else {
            $this->source->set('menuindex', 0);
        }

        $this->source->set('parent', $this->target->id);

        $this->contextsAffected[$this->source->context_key] = true;

        $this->source->set('context_key', $this->target->context_key);

        if (!$this->source->checkPolicy('save')) {
            return $this->modx->lexicon('resource_err_save');
        }

        $this->source->save();
        $this->nodesAffected[] = $this->source;
        $this->contextsAffected[$this->source->context_key] = true;

        return true;
    }

    public function moveContext($type, $rank){
        $this->$type->set('rank', $rank);
        $this->$type->save();
        $this->contextsAffected[$this->$type->key] = true;
    }

    public function moveAffectedContexts($lastRank) {
        $c = $this->modx->newQuery('modContext');
        $c->where(array(
            'key:NOT IN' => array('mgr', $this->source->key, $this->target->key),
            'rank:>=' => $lastRank,
        ));
        $c->sortby('rank', 'ASC');

        $contextsToSort = $this->modx->getIterator('modContext', $c);
        $lastRank = $lastRank + 2;

        /** @var modContext $context */
        foreach ($contextsToSort as $context) {
            $context->set('rank', $lastRank);
            $context->save();
            $this->contextsAffected[$context->key] = true;
            $lastRank++;
        }
    }

    public function sort(){
        if (($this->source instanceof modContext) && ($this->target instanceof modContext)) {
            return $this->sortContexts();
        }

        if (($this->source instanceof modResource) && ($this->target instanceof modContext)) {
            if ($this->autoIsFolder) {
                $this->fixParents($this->source);
            }
            
            return $this->moveToContext();
        }

        if (($this->source instanceof modResource) && ($this->target instanceof modResource)) {
            if ($this->autoIsFolder) {
                $this->fixParents($this->source);
            }
            
            return $this->sortResources();
        }

        return false;
    }
}
return 'modResourceSortProcessor';
