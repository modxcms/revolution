<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');


$urls= array(''=>'--', 'http://'=>'http://', 'https://'=>'https://', 'ftp://'=>'ftp://', 'mailto:'=>'mailto:');
$this->xpdo->smarty->assign('urls',$urls);
foreach($urls as $k => $v){
    if (strpos($this->get('value'),$v)!==false) {
        $this->set('value',str_replace($v,'',$this->get('value')));
        $this->xpdo->smarty->assign('selected',$v);
    }
}
return $this->xpdo->smarty->fetch('element/tv/renders/input/url.tpl');