<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
$value= $this->parseInput($value);
$w= $params['w'] ? $params['w'] : '100%';
$h= $params['h'] ? $params['h'] : '400px';
$richtexteditor= $params['edt'] ? $params['edt'] : "";
$o= '<div class="MODX_RichTextWidget"><textarea id="' . $id . '" name="' . $id . '" style="width:' . $w . '; height:' . $h . ';">';
$o .= htmlspecialchars($value);
$o .= '</textarea></div>';
$replace_richtext= array (
    $id
);
// setup editors
if (!empty ($replace_richtext) && !empty ($richtexteditor)) {
    // invoke OnRichTextEditorInit event
    $evtOut= $this->xpdo->invokeEvent("OnRichTextEditorInit", array (
        'editor' => $richtexteditor,
        'elements' => $replace_richtext,
        'forfrontend' => 1,
        'width' => $w,
        'height' => $h
    ));
    if (is_array($evtOut))
        $o .= implode("", $evtOut);
}

return $o;