<?php
/**
 * Grabs a list of input types for TVs
 *
 * @package modx
 * @subpackage processors.element.template.tv.inputtype
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_input_types');

/* TODO: Eventually make this dynamic so users can add their own */
$types = array(
    'text','textarea','textareamini','richtext','dropdown',
    'listbox','listbox-multiple','option','checkbox',
    'image','file','url','email','number','date',
);

$ar = array();
foreach ($types as $type) {
    $ar[] = array(
        'name' => $modx->lexicon($type),
        'value' => $type,
    );
}

return $this->outputArray($ar);