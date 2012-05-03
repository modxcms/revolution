<?php
/**
 * @var modX $this->modx
 * @var modTemplateVar $this
 * 
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderResourceList extends modTemplateVarInputRender {
    public function process($value,array $params = array()) {
        $parents = $this->getInputOptions();
        $parents = !empty($params['parents']) || $params['parents'] === '0' ? explode(',',$params['parents']) : $parents;
        $params['depth'] = !empty($params['depth']) ? $params['depth'] : 10;
        if (empty($parents) || (empty($parents[0]) && $parents[0] !== '0')) {
            $parents = array();
        }
        $parentList = array();
        foreach ($parents as $parent) {
            /** @var modResource $parent */
            $parent = $this->modx->getObject('modResource',$parent);
            if ($parent) $parentList[] = $parent;
        }

        /* get all children */
        $ids = array();
        if (!empty($parentList)) {
            foreach ($parentList as $parent) {
                if (!empty($params['includeParent'])) $ids[] = $parent->get('id');
                $children = $this->modx->getChildIds($parent->get('id'),$params['depth'],array(
                    'context' => $parent->get('context_key'),
                ));
                $ids = array_merge($ids,$children);
            }
            $ids = array_unique($ids);
        }

        $c = $this->modx->newQuery('modResource');
        $c->leftJoin('modResource','Parent');
        if (!empty($ids)) {
            $c->where(array('modResource.id:IN' => $ids));
        } else if (!empty($parents) && $parents[0] == 0) {
            $c->where(array('modResource.parent' => 0));
        }
        if (!empty($params['where'])) {
            $params['where'] = $this->modx->fromJSON($params['where']);
            $c->where($params['where']);
        }
    	if (!empty($params['limitRelatedContext']) && ($params['limitRelatedContext'] == 1 || $params['limitRelatedContext'] == 'true')) {
			$context_key = $this->modx->resource->get('context_key');
            $c->where(array('modResource.context_key' => $context_key));
		}
        $c->sortby('Parent.menuindex,modResource.menuindex','ASC');
        if (!empty($params['limit'])) {
            $c->limit($params['limit']);
        }
        $resources = $this->modx->getCollection('modResource',$c);

        /* iterate */
        $opts = array();
        if (!empty($params['showNone'])) {
            $opts[] = array('value' => '','text' => '-','selected' => $this->tv->get('value') == '');
        }
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            $selected = $resource->get('id') == $this->tv->get('value');
            $opts[] = array(
                'value' => $resource->get('id'),
                'text' => $resource->get('pagetitle').' ('.$resource->get('id').')',
                'selected' => $selected,
            );
        }
        $this->setPlaceholder('opts',$opts);
    }
    public function getTemplate() {
        return 'element/tv/renders/input/resourcelist.tpl';
    }
}
return 'modTemplateVarInputRenderResourceList';