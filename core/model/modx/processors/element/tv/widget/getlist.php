<?php
/**
 * Grabs a list of widget types for the tv.
 *
 * @package modx
 * @subpackage processors.element.template.widget
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_widget');

/* TODO: Eventually make TV widget types dynamic */
$types = array(
    'text',
    'rawtext',
    'textarea',
    'rawtextarea',
    'textareamini',
    'richtext',
    'dropdown',
    'listbox',
    'listbox-multiple',
    'option',
    'checkbox',
    'image',
    'file',
    'url',
    'email',
    'number',
    'date',
    'string',
);

$list = array();
foreach ($types as $type) {
    $typeArray = array(
        'name' => $modx->lexicon($type),
        'value' => $type,
    );
    $list[] = $typeArray;
}
return $this->outputArray($list);