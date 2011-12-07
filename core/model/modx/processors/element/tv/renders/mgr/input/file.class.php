<?php
/**
 * @var modX $this->modx
 * @var modTemplateVar $this
 * @var array $params
 * 
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderFile extends modTemplateVarInputRender {
    public function process($value,array $params = array()) {
        $this->modx->getService('fileHandler','modFileHandler', '', array('context' => $this->modx->context->get('key')));

        /** @var modMediaSource $source */
        $source = $this->tv->getSource($this->modx->resource->get('context_key'));
        if (!$source) return '';
        if (!$source->getWorkingContext()) {
            return '';
        }
        $source->setRequestProperties($_REQUEST);
        $source->initialize();
        $this->modx->controller->setPlaceholder('source',$source->get('id'));
        $params = array_merge($source->getPropertyList(),$params);
        $res_id = $this->modx->resource->get('id');

        if (!$source->checkPolicy('view') || ($params['autoResourceFolders'] == 'true' && empty($res_id))) {
            $this->modx->controller->setPlaceholder('disabled',true);
            $this->tv->set('disabled',true);
            $this->tv->set('relativeValue',$this->tv->get('value'));
        } else {
            $this->modx->controller->setPlaceholder('disabled',false);
            $this->tv->set('disabled',false);
            $value = $this->tv->get('value');
            if (!empty($value)) {
                $params['openTo'] = $source->getOpenTo($value,$params);
            }
            $this->tv->set('relativeValue',$value);
        }

        $this->setPlaceholder('params',$params);
        $this->setPlaceholder('tv',$this->tv);
        $this->setPlaceholder('res_id',$res_id);        
    }
    public function getTemplate() {
        return 'element/tv/renders/input/file.tpl';
    }
}
return 'modTemplateVarInputRenderFile';