<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderUrl extends modTemplateVarInputRender {
    public function getTemplate() {
        return 'element/tv/renders/input/url.tpl';
    }
    public function process($value,array $params = array()) {
        $urls= array(''=>'--', 'http://'=>'http://', 'https://'=>'https://', 'ftp://'=>'ftp://', 'mailto:'=>'mailto:');
        $this->setPlaceholder('urls',$urls);
        foreach ($urls as $k => $v) {
            $test = $this->modx->getOption('use_multibyte',null,false) ? mb_strpos($value,$v,null,$this->modx->getOption('modx_charset',null,'UTF-8')) : strpos($value,$v);
            if ($test !== false) {
                $this->tv->set('processedValue',str_replace($v,'',$value));
                $this->tv->set('value',str_replace($v,'',$value));
                $this->setPlaceholder('selected',$v);
            }
        }
    }
}
return 'modTemplateVarInputRenderUrl';