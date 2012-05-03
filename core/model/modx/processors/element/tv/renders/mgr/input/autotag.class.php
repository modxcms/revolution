<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderAutoTag extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/autotag.tpl';
    }
    public function process($value,array $params = array()) {
        if (empty($params['parent_resources'])) $params['parent_resources'] = '';
        $value = explode(",",$value);

        $default = explode("||",$this->tv->get('default_text'));

        $options = $this->getInputOptions();

        /** @var xPDOQuery $c */
        $c = $this->modx->newQuery('modTemplateVarResource');
        $c->innerJoin('modTemplateVar','TemplateVar');
        $c->innerJoin('modResource','Resource');
        $c->where(array(
            'tmplvarid' => $this->tv->get('id'),
        ));
        if (!empty($params['parent_resources'])) {
            $ids = array();
            $parents = explode(',',$params['parent_resources']);

            $currCtx = 'web';
            $this->modx->switchContext('web');
            foreach ($parents as $id) {
                /** @var modResource $r */
                $r = $this->modx->getObject('modResource',$id);
                if ($r && $currCtx != $r->get('context_key')) {
                    $this->modx->switchContext($r->get('context_key'));
                    $currCtx = $r->get('context_key');
                }
                if ($r) {
                    $pids = $this->modx->getChildIds($id,10,array('context' => $r->get('context_key')));
                    $ids = array_merge($ids,$pids);
                }
                $ids[] = $id;
            }
            $this->modx->switchContext('mgr');
            $ids = array_unique($ids);
            $c->where(array(
                'Resource.id:IN' => $ids,
            ));
        }
        $c->sortby('value','ASC');
        $tags = $this->modx->getCollection('modTemplateVarResource',$c);
        $options = array();
        /** @var modTemplateVarResource $tag */
        foreach ($tags as $tag) {
            $vs = explode(',',$tag->get('value'));
            $options = array_merge($options,$vs);
        }
        $options = array_unique($options);
        ksort($options);
        $opts = array();
        $defaults = array();
        $i = 0;
        foreach ($options as $tag) {
            $checked = false;

            if (in_array($tag,$value)) { $checked = true; }
            if (in_array($tag,$default)) {
                $defaults[] = 'tv'.$this->tv->get('id').'-'.$i;
            }

            $opts[] = array(
                'value' => htmlspecialchars($tag,ENT_COMPAT,'UTF-8'),
                'checked' => $checked,
            );
            $i++;
        }

        $this->setPlaceholder('cbdefaults',implode(',',$defaults));
        $this->setPlaceholder('opts',$opts);
    }
}
return 'modTemplateVarInputRenderAutoTag';