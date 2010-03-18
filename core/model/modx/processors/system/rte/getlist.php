<?php
/**
 * Get a list of registered RTEs
 *
 * @package modx
 * @subpackage processors.system.rte
 */

/* invoke OnRichTextEditorRegister event */
$rs = $modx->invokeEvent('OnRichTextEditorRegister');
if ($rs == '') $rs == array();

$rtes = array();
$rtes[] = array('value' => $modx->lexicon('none'));
if (is_array($rs)) {
    foreach ($rs as $r) {
	   $rtes[] = array('value' => $r);
    }
} elseif (is_string($rs)) {
    $rtes[] = array('value' => $rs);
}
return $this->outputArray($rtes,$count);