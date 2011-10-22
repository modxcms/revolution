<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$urls= array(''=>'--', 'http://'=>'http://', 'https://'=>'https://', 'ftp://'=>'ftp://', 'mailto:'=>'mailto:');
$this->xpdo->controller->setPlaceholder('urls',$urls);
foreach ($urls as $k => $v) {
    $test = $this->xpdo->getOption('use_multibyte',null,false) ? mb_strpos($value,$v,null,$this->xpdo->getOption('modx_charset',null,'UTF-8')) : strpos($value,$v);
    if ($test !== false) {
        $this->set('processedValue',str_replace($v,'',$value));
        $this->set('value',str_replace($v,'',$value));
        $this->xpdo->controller->setPlaceholder('selected',$v);
    }
}
return $this->xpdo->controller->fetchTemplate('element/tv/renders/input/url.tpl');
